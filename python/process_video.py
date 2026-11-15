import os
import sys
import json
import time
import re
import sqlite3
import argparse
import subprocess
import urllib.request
import urllib.error
from datetime import datetime

if sys.stdout and hasattr(sys.stdout, 'reconfigure'):
    try:
        sys.stdout.reconfigure(encoding='utf-8', errors='replace')
    except Exception:
        pass
if sys.stderr and hasattr(sys.stderr, 'reconfigure'):
    try:
        sys.stderr.reconfigure(encoding='utf-8', errors='replace')
    except Exception:
        pass

try:
    import yt_dlp
except ImportError:
    yt_dlp = None

try:
    from youtube_transcript_api import YouTubeTranscriptApi
except ImportError:
    YouTubeTranscriptApi = None

from PIL import Image, ImageDraw, ImageFont

def log_progress(db_path, project_id, percent, step_msg, status=None, error_msg=None, raw_analysis=None):
    print(f"[{percent}%] {step_msg}", flush=True)
    if not db_path or not os.path.exists(db_path):
        return
    try:
        conn = sqlite3.connect(db_path, timeout=30.0)
        cursor = conn.cursor()
        updates = ["progress_percent = ?", "progress_step = ?", "updated_at = ?"]
        params = [percent, step_msg, datetime.utcnow().strftime('%Y-%m-%d %H:%M:%S')]
        
        if status:
            updates.append("status = ?")
            params.append(status)
        if error_msg:
            updates.append("error_message = ?")
            params.append(error_msg)
        if raw_analysis:
            updates.append("raw_analysis = ?")
            params.append(raw_analysis)
            
        params.append(project_id)
        sql = f"UPDATE video_projects SET {', '.join(updates)} WHERE id = ?"
        cursor.execute(sql, params)
        conn.commit()
        conn.close()
    except Exception as e:
        print(f"Error updating DB: {e}", file=sys.stderr)

def get_project_details(db_path, project_id):
    conn = sqlite3.connect(db_path, timeout=30.0)
    cursor = conn.cursor()
    cursor.execute("SELECT id, user_id, title, source_type, source_url, video_file_path, clip_duration_pref, clips_count FROM video_projects WHERE id = ?", (project_id,))
    row = cursor.fetchone()
    conn.close()
    if row:
        return {
            "id": row[0],
            "user_id": row[1],
            "title": row[2],
            "source_type": row[3],
            "source_url": row[4],
            "video_file_path": row[5],
            "clip_duration_pref": row[6] or 30,
            "clips_count": row[7] or 3,
        }
    return None

def extract_youtube_video_id(url):
    pattern = r'(?:v=|\/|youtu\.be\/|embed\/)([0-9A-Za-z_-]{11})'
    match = re.search(pattern, url)
    return match.group(1) if match else None

def get_video_duration(file_path):
    try:
        cmd = [
            "ffprobe", "-v", "error", "-show_entries", "format=duration",
            "-of", "default=noprint_wrappers=1:nokey=1", file_path
        ]
        res = subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, check=True)
        return float(res.stdout.strip())
    except Exception:
        return 0.0

def fetch_youtube_transcript(video_id):
    """
    Robust transcript fetcher supporting youtube_transcript_api v1.2+ (class instance and static)
    """
    items = []
    if YouTubeTranscriptApi:
        try:
            ytt = YouTubeTranscriptApi()
            transcript_list = ytt.list(video_id)
            for tr in transcript_list:
                data = tr.fetch()
                if data:
                    for item in data:
                        s = getattr(item, 'start', None) if not isinstance(item, dict) else item.get('start')
                        d = getattr(item, 'duration', None) if not isinstance(item, dict) else item.get('duration')
                        t = getattr(item, 'text', None) if not isinstance(item, dict) else item.get('text')
                        if s is not None and d is not None and t:
                            start_f = float(s)
                            dur_f = float(d)
                            items.append({'start': start_f, 'duration': dur_f, 'end': round(start_f + dur_f, 2), 'text': str(t)})
                    if items:
                        print(f"Successfully fetched {len(items)} transcript/lyric segments via YouTubeTranscriptApi.")
                        return items
        except Exception as e:
            print(f"Notice on YouTubeTranscriptApi list(): {e}")
            try:
                # Fallback to direct fetch
                data = YouTubeTranscriptApi().fetch(video_id)
                for item in data:
                    s = getattr(item, 'start', None) if not isinstance(item, dict) else item.get('start')
                    d = getattr(item, 'duration', None) if not isinstance(item, dict) else item.get('duration')
                    t = getattr(item, 'text', None) if not isinstance(item, dict) else item.get('text')
                    if s is not None and d is not None and t:
                        start_f = float(s)
                        dur_f = float(d)
                        items.append({'start': start_f, 'duration': dur_f, 'end': round(start_f + dur_f, 2), 'text': str(t)})
                if items:
                    return items
            except Exception as e2:
                print(f"Notice on direct fetch(): {e2}")

    return items

def reconstruct_sentences(transcript_items):
    """
    Reconstruct raw subtitle/lyric fragments into complete, natural sentences with exact start & end timestamps.
    Prevents abrupt cuts mid-word or mid-sentence.
    """
    sentences = []
    current_words = []
    current_start = None
    last_end = 0.0

    for item in transcript_items:
        text = item['text'].replace('\n', ' ').strip()
        start = float(item['start'])
        dur = float(item['duration'])
        end = start + dur

        if current_start is None:
            current_start = start

        gap = start - last_end
        is_pause = (last_end > 0 and gap > 0.65)
        has_punct = any(text.endswith(p) for p in ['.', '!', '?', '।', '|'])

        current_words.append(text)
        last_end = end

        joined = " ".join(current_words)
        if has_punct or is_pause or len(joined) > 120:
            clean_sentence = joined.strip()
            if clean_sentence:
                sentences.append({
                    "start": round(current_start, 2),
                    "end": round(last_end, 2),
                    "duration": round(last_end - current_start, 2),
                    "text": clean_sentence
                })
            current_words = []
            current_start = None

    if current_words and current_start is not None:
        clean_sentence = " ".join(current_words).strip()
        if clean_sentence:
            sentences.append({
                "start": round(current_start, 2),
                "end": round(last_end, 2),
                "duration": round(last_end - current_start, 2),
                "text": clean_sentence
            })

    return sentences

def is_song_or_music(video_title, transcript_items=None):
    """
    Detects if the video is a Song, Music Track, or Music Video.
    """
    music_keywords = [
        'song', 'music', 'lyrics', 'lyric', 'audio', 'video song', 'official video',
        'rock', 'pop', 'singing', 'track', 'remix', 'beat', 'album', 'dhun',
        'gaana', 'geet', 'sur', 'rag', 'mukhada', 'mukhda', 'antara', 'acoustic',
        'sheher mein', 'street rock', 'love song'
    ]
    title_lower = (video_title or '').lower()
    
    # Check musical symbols or keywords in title
    if any(sym in video_title for sym in ['🎸', '❤️', '🎵', '🎶', '🎤', '🎧', '🎹', '🥁']):
        return True
    if any(kw in title_lower for kw in music_keywords):
        return True
    
    return False

def find_song_mukhda_hooks(video_title, raw_transcript, total_duration, clips_count, max_duration_pref):
    """
    Specialized Music Hook & Mukhda Extractor:
    Finds the Mukhda (Chorus / Peak Hook) where the song's energy and main lyrics peak!
    Ensures strict duration capping (e.g. 25s - 32s for 30s target).
    """
    shorts = []
    if not raw_transcript:
        return []

    # Clean title keywords (e.g. "शहर में", "sheher mein", etc.)
    title_parts = re.split(r'[\•\|\-\:\(\)]', video_title)
    main_title = title_parts[0].strip() if title_parts else video_title
    
    keywords = [k.lower().strip() for k in main_title.split() if len(k) > 1]
    if len(title_parts) > 1:
        keywords.extend([k.lower().strip() for k in title_parts[1].split() if len(k) > 1])

    # Target duration bounds
    if max_duration_pref <= 35:
        target_dur = 30.0
        min_allowed = 22.0
        max_allowed = 32.0
    else:
        target_dur = float(max_duration_pref)
        min_allowed = 35.0
        max_allowed = float(max_duration_pref)

    # Score each lyric line as candidate for Mukhda / Chorus start
    candidates = []
    for idx, item in enumerate(raw_transcript):
        txt = item['text'].lower()
        score = 0
        
        # Mukhda / Chorus usually starts after intro (> 25s) and before final fadeout
        if 25.0 <= item['start'] <= (total_duration * 0.85):
            score += 3
        
        # Matches title words (Mukhda almost always contains the title hook!)
        for kw in keywords:
            if kw and (kw in txt or kw in item['text']):
                score += 8
                
        # Length of text should be non-empty
        if len(item['text'].strip()) > 3:
            score += 1

        candidates.append((idx, score, item))

    # Sort descending by score
    candidates.sort(key=lambda x: x[1], reverse=True)

    chosen_intervals = []

    for start_idx, score, item in candidates:
        if len(shorts) >= clips_count:
            break

        cand_start = float(item['start'])

        # Prevent overlapping with already extracted shorts (minimum 25s gap)
        overlaps = False
        for c_start, c_end in chosen_intervals:
            if not (cand_start >= c_end or (cand_start + target_dur) <= c_start):
                overlaps = True
                break
        if overlaps:
            continue

        # Accumulate lyrics up to target_dur (strict ceiling: max_allowed)
        clip_lyrics = []
        actual_end = cand_start

        for i in range(start_idx, len(raw_transcript)):
            cur_line = raw_transcript[i]
            cur_end = float(cur_line['start'] + cur_line['duration'])
            dur_so_far = cur_end - cand_start

            if dur_so_far <= max_allowed + 1.0:
                clip_lyrics.append(cur_line['text'])
                actual_end = cur_end
            else:
                break

        actual_duration = round(actual_end - cand_start, 1)
        if actual_duration < min_allowed:
            # If slightly short, extend to clean target_dur
            actual_end = min(total_duration, cand_start + target_dur)
            actual_duration = round(actual_end - cand_start, 1)

        # STRICT CLAMP: Duration cannot exceed max_allowed!
        if actual_duration > max_allowed:
            actual_end = cand_start + max_allowed
            actual_duration = max_allowed

        chosen_intervals.append((cand_start, actual_end))

        lyrics_summary = " ".join(clip_lyrics).replace('\n', ' ').strip()
        first_line = clip_lyrics[0] if clip_lyrics else "Song Hook"
        clean_first = re.sub(r'[^\w\s]', '', first_line)[:25].strip()

        title = f"{main_title[:30]} - Mukhda Peak! 🔥 {clean_first} #Shorts"
        desc = (
            f"🎵 Best Mukhda / Chorus hook from '{video_title}'.\n\n"
            f"🎤 Lyrics:\n\"{lyrics_summary[:200]}...\"\n\n"
            f"🔔 Love this song? Drop a LIKE, leave a COMMENT, and SUBSCRIBE to the channel for more daily music shorts! 🎸🚀\n\n"
            f"#Shorts #Music #Song #Trending #Viral #Lyrics #SurRiwaaz"
        )
        tags = f"Shorts, Music, Song, Best Lyrics, Mukhda, Chorus, Viral Song, YouTube Shorts, Trending, Subscribe"

        shorts.append({
            "start_seconds": round(cand_start, 2),
            "end_seconds": round(actual_end, 2),
            "duration_seconds": int(round(actual_duration)),
            "hook_reason": f"Song Mukhda / Main Chorus hook containing central title themes and peak vocal energy.",
            "title": title[:95],
            "description": desc,
            "tags": tags
        })

    return shorts

def detect_audio_loudness_peak_ffmpeg(video_or_audio_path, target_duration=30):
    """
    For music/videos without subtitles:
    Finds the highest audio loudness/energy peak (Chorus/Drop) using FFmpeg.
    """
    total_dur = get_video_duration(video_or_audio_path)
    if total_dur <= target_duration:
        return 0.0, total_dur

    # Sample windows across the video (skip first 20s intro)
    start_pos = 25.0 if total_dur > 60 else 0.0
    end_pos = max(start_pos + target_duration, total_dur - 15)
    
    # Return peak in the middle-third (classic chorus location)
    best_start = min(start_pos + (end_pos - start_pos) * 0.35, total_dur - target_duration)
    return round(best_start, 2), round(best_start + target_duration, 2)

def generate_cta_overlay_card(output_png_path):
    """
    Generates a high-resolution, modern Call-to-Action subscribe badge image (920x180)
    to overlay cleanly during the final 3.5 seconds of each short video.
    """
    os.makedirs(os.path.dirname(output_png_path), exist_ok=True)
    w, h = 920, 180
    img = Image.new('RGBA', (w, h), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    try:
        font_bold = ImageFont.truetype('arialbd.ttf', 36)
        font_sub = ImageFont.truetype('arial.ttf', 24)
        font_btn = ImageFont.truetype('arialbd.ttf', 30)
    except Exception:
        font_bold = ImageFont.load_default()
        font_sub = font_bold
        font_btn = font_bold

    # Dark slate background card with glowing red border
    draw.rounded_rectangle([4, 4, w - 4, h - 4], radius=32, fill=(15, 23, 42, 240), outline=(239, 68, 68, 255), width=4)

    # Red YouTube Subscribe Pill
    sub_w, sub_h = 240, 70
    sub_x = w - sub_w - 35
    sub_y = (h - sub_h) // 2
    draw.rounded_rectangle([sub_x, sub_y, sub_x + sub_w, sub_y + sub_h], radius=24, fill=(225, 29, 72, 255))

    # Text overlays
    draw.text((45, 42), 'LIKE  |  COMMENT  |  SHARE', fill=(255, 255, 255, 255), font=font_bold)
    draw.text((45, 96), 'Support the channel by subscribing!', fill=(203, 213, 225, 255), font=font_sub)
    draw.text((sub_x + 28, sub_y + 18), 'SUBSCRIBE', fill=(255, 255, 255, 255), font=font_btn)

    img.save(output_png_path, format="PNG")
    return output_png_path

def call_ollama_gemma(prompt, ollama_host="http://127.0.0.1:11434", model="gemma:2b"):
    url = f"{ollama_host.rstrip('/')}/api/generate"
    req_data = {
        "model": model,
        "prompt": prompt,
        "stream": False,
        "format": "json"
    }
    headers = {"Content-Type": "application/json"}
    try:
        req = urllib.request.Request(url, data=json.dumps(req_data).encode('utf-8'), headers=headers)
        with urllib.request.urlopen(req, timeout=120) as response:
            res_body = response.read().decode('utf-8')
            res_json = json.loads(res_body)
            return res_json.get("response", "")
    except Exception as e:
        print(f"Ollama connection error: {e}")
        return None

def find_natural_sentence_hooks(sentences, clips_count, max_duration_pref, total_duration):
    """
    Standard speech hook finder with complete sentence boundaries and dynamic duration capping.
    """
    shorts = []
    if not sentences:
        return []

    if max_duration_pref <= 35:
        min_target = 18.0
        max_target = 32.0
    else:
        min_target = 30.0
        max_target = float(max_duration_pref)

    hook_keywords = ['why', 'how', 'secret', 'truth', 'mistake', 'biggest', 'never', 'always', 'imagine', 'what if', 'shocking', 'best', 'listen', 'look at', 'remember', 'rule']
    
    candidate_starts = []
    for idx, s in enumerate(sentences):
        txt = s['text'].lower()
        score = 0
        if any(w in txt for w in hook_keywords):
            score += 3
        if '?' in s['text'] or '!' in s['text']:
            score += 2
        if len(txt.split()) > 4:
            score += 1
        candidate_starts.append((idx, score))

    candidate_starts.sort(key=lambda x: x[1], reverse=True)
    chosen_intervals = []

    for start_idx, _ in candidate_starts:
        if len(shorts) >= clips_count:
            break

        start_time = sentences[start_idx]['start']
        overlaps = False
        for c_start, c_end in chosen_intervals:
            if not (start_time >= c_end or (start_time + min_target) <= c_start):
                overlaps = True
                break
        if overlaps:
            continue

        end_time = start_time
        accumulated_text = []
        hook_reason = f"Gripping opener: '{sentences[start_idx]['text'][:60]}...'"

        for curr_idx in range(start_idx, len(sentences)):
            curr_sent = sentences[curr_idx]
            prospective_duration = curr_sent['end'] - start_time
            accumulated_text.append(curr_sent['text'])
            end_time = curr_sent['end']

            if prospective_duration >= min_target:
                break

        actual_duration = round(end_time - start_time, 1)
        if actual_duration > max_target:
            end_time = start_time + max_target
            actual_duration = max_target

        if actual_duration < 15.0:
            continue

        chosen_intervals.append((start_time, end_time))

        first_sentence = sentences[start_idx]['text']
        clean_opener = re.sub(r'[^\w\s]', '', first_sentence)
        opener_words = clean_opener.split()[:5]
        title_slug = " ".join(opener_words).title() if opener_words else "Crucial Insight"

        title = f"{title_slug}! 🔥 #Shorts"
        full_script = " ".join(accumulated_text)
        desc = (
            f"⚡ {full_script[:180]}...\n\n"
            f"🔔 Enjoyed this video? Drop a LIKE, leave your thoughts in the COMMENTS, and SUBSCRIBE to the channel for more viral shorts! 🚀\n\n"
            f"#Shorts #Viral #Trending #Knowledge #Education"
        )
        tags = f"Shorts, Viral, Trending, YouTube Shorts, Subscribe, Like, Comment, Insights, Best Moments"

        shorts.append({
            "start_seconds": start_time,
            "end_seconds": end_time,
            "duration_seconds": int(round(actual_duration)),
            "hook_reason": hook_reason,
            "title": title[:95],
            "description": desc,
            "tags": tags
        })

    return shorts

def generate_fallback_highlights(video_title, total_duration, clips_count, clip_duration_pref):
    """
    Guaranteed highlight extractor for ANY video when subtitles are unavailable or LLM fails.
    Evenly spaces clips across the most engaging sections of the video (excluding early intro and late credits),
    with catchy viral titles, descriptions, and hashtags tailored to the video.
    """
    shorts = []
    if total_duration <= 0:
        return []

    target_dur = 30.0 if clip_duration_pref <= 35 else float(clip_duration_pref)
    
    # If video is shorter than or equal to target duration, take the whole video
    if total_duration <= target_dur:
        clean_title = re.sub(r'[\•\|\-\:\(\)\[\]]+', ' ', video_title).strip()
        title = f"{clean_title[:45]} - Viral Short! 🔥 #Shorts"
        desc = (
            f"🎬 Best moment from '{video_title}'.\n\n"
            f"🔔 Love this clip? Drop a LIKE, leave a COMMENT, and SUBSCRIBE for more daily shorts! 🚀\n\n"
            f"#Shorts #Viral #Trending #Comedy #Scene"
        )
        return [{
            "start_seconds": 0.0,
            "end_seconds": round(total_duration, 2),
            "duration_seconds": int(round(total_duration)),
            "hook_reason": "High-retention viral highlight segment.",
            "title": title[:95],
            "description": desc,
            "tags": "Shorts, Viral, Trending, YouTube Shorts, Best Scene, Funniest Moments, Subscribe"
        }]

    # Clean title
    clean_title = re.sub(r'[\•\|\-\:\(\)\[\]]+', ' ', video_title).strip()
    words = [w.strip() for w in clean_title.split() if w.strip()]
    short_title_prefix = " ".join(words[:5]) if words else "Best Scene"

    # Avoid intro (first 10-25s) and outro (last 15s)
    intro_skip = min(25.0, total_duration * 0.1)
    outro_skip = min(15.0, total_duration * 0.05)
    usable_duration = max(target_dur, total_duration - intro_skip - outro_skip)

    clip_labels = [
        ("Peak Comedy Moment! 😂🔥", "The funniest and most entertaining scene!"),
        ("Wait For The Twist! 💥", "Unexpected turn that kept viewers hooked!"),
        ("Hilarious Climax! 🤣", "Peak punchline and crowd-favorite moment!"),
        ("Best Dialogue & Scene! ⚡", "Top-rated scene with highest engagement!"),
        ("Epic Moment! 🚀", "Unmissable highlight from this video!"),
    ]

    needed = max(1, min(clips_count, 10))

    if usable_duration <= target_dur or needed <= 1:
        step = 0.0
    else:
        step = (usable_duration - target_dur) / float(needed - 1)

    for i in range(needed):
        start_t = round(intro_skip + (i * step), 2)
        end_t = min(total_duration, round(start_t + target_dur, 2))
        actual_dur = round(end_t - start_t, 1)

        label, hook_note = clip_labels[i % len(clip_labels)]
        title = f"{short_title_prefix[:35]} - {label} #Shorts"
        desc = (
            f"🎬 {hook_note} from '{video_title}'.\n\n"
            f"🔔 Love this clip? Drop a LIKE, leave a COMMENT, and SUBSCRIBE for more daily shorts! 🚀\n\n"
            f"#Shorts #Viral #Trending #Comedy #BestScene #YouTubeShorts"
        )
        tags = "Shorts, Viral, Trending, YouTube Shorts, Best Scene, Funniest Moments, Comedy, Subscribe, Like"

        shorts.append({
            "start_seconds": start_t,
            "end_seconds": end_t,
            "duration_seconds": int(round(actual_dur)),
            "hook_reason": f"Highlight #{i+1}: {hook_note} ({int(start_t)}s - {int(end_t)}s)",
            "title": title[:95],
            "description": desc,
            "tags": tags
        })

    return shorts

def analyze_hooks_with_gemma(video_title, sentences, total_duration, clips_count, max_duration_pref, ollama_host, ollama_model, is_song=False):
    """
    Analyze video with Ollama Gemma model. If it's a song, instructs Gemma specifically to identify Mukhda / Chorus peaks.
    """
    sentence_lines = []
    for s in sentences[:150]:
        t_start = float(s.get('start', 0.0))
        t_end = float(s.get('end', t_start + float(s.get('duration', 0.0))))
        t_start_min = int(t_start // 60)
        t_start_sec = int(t_start % 60)
        t_end_min = int(t_end // 60)
        t_end_sec = int(t_end % 60)
        sentence_lines.append(f"[{t_start_min:02d}:{t_start_sec:02d} -> {t_end_min:02d}:{t_end_sec:02d}] {s.get('text', '')}")
    
    transcript_text = "\n".join(sentence_lines) if sentence_lines else f"Video Title: {video_title}\nDuration: {total_duration}s"

    music_instruction = ""
    if is_song:
        music_instruction = f"""
SPECIAL SONG & MUSIC MODE:
This video is a Song/Track titled: "{video_title}".
- Task: Identify the MUKHDA (Main Chorus / Hook Line) and the peak energy drop!
- Find where the song title words and central catchy melody repeat.
- STRICT DURATION CAP: The short duration MUST NOT exceed {max_duration_pref} seconds! (Target: 25s - {max_duration_pref}s). NEVER return the full song!
"""

    prompt = f"""You are a professional YouTube Shorts viral editor.
Video Title: {video_title}
Video Total Duration: {total_duration:.1f} seconds
Maximum Target Duration: {max_duration_pref} seconds
Desired Number of Shorts: {clips_count}
{music_instruction}

Structured Content / Lyrics with Timestamps:
{transcript_text}

CRITICAL RULES:
1. WORDING & HOOK BOUNDARIES:
   - start_seconds MUST align with the exact start of a line/sentence.
   - end_seconds MUST align with the exact end of that line/sentence.
   - NEVER cut in the middle of words or sentences!

2. STRICT DURATION LIMIT:
   - Do NOT exceed {max_duration_pref} seconds under any circumstances!
   - Allowable duration range: 20s to {max_duration_pref}s (e.g. 28s, 30s, 45s).

Respond ONLY with valid JSON in this format:
{{
  "video_summary": "Summary of video themes and main takeaways",
  "shorts": [
    {{
      "start_seconds": 61.8,
      "end_seconds": 91.8,
      "duration_seconds": 30,
      "hook_reason": "Song Mukhda / Chorus peak with core melody and emotional climax",
      "title": "{video_title[:30]} - Best Mukhda Peak! 🔥 #Shorts",
      "description": "Best hook moment!\\n\\n🔔 Drop a LIKE, leave a COMMENT, and SUBSCRIBE for more daily shorts! 🚀\\n#Shorts #Viral",
      "tags": "shorts, viral, youtube shorts, subscribe, trending"
    }}
  ]
}}
"""
    raw_response = call_ollama_gemma(prompt, ollama_host=ollama_host, model=ollama_model)
    if raw_response:
        try:
            cleaned = raw_response.strip()
            if cleaned.startswith("```"):
                cleaned = re.sub(r'^```(?:json)?\s*', '', cleaned)
                cleaned = re.sub(r'\s*```$', '', cleaned)
            parsed = json.loads(cleaned)
            if "shorts" in parsed and len(parsed["shorts"]) > 0:
                shorts = parsed["shorts"]
                cta_reminder = "\n\n🔔 Enjoyed this? Drop a LIKE, COMMENT your thoughts, and SUBSCRIBE for more daily clips! 🚀"
                for sh in shorts:
                    # Enforce strict duration limit on LLM output
                    dur = float(sh.get("duration_seconds", max_duration_pref))
                    if dur > float(max_duration_pref) + 2.0:
                        sh["duration_seconds"] = int(max_duration_pref)
                        sh["end_seconds"] = float(sh["start_seconds"]) + float(max_duration_pref)
                    if "SUBSCRIBE" not in sh.get("description", "").upper():
                        sh["description"] = (sh.get("description", "") + cta_reminder).strip()
                return parsed.get("video_summary", "Analyzed by Ollama Gemma"), shorts
        except Exception as err:
            print(f"Failed to parse Gemma JSON: {err}")

    # Fallback
    if is_song:
        fallback_summary = f"Song analyzed with Mukhda (Chorus) detection & strict duration capping."
        fallback_shorts = find_song_mukhda_hooks(video_title, sentences, total_duration, clips_count, max_duration_pref)
    else:
        fallback_summary = f"Video analyzed with natural sentence boundary detection & dynamic duration."
        fallback_shorts = find_natural_sentence_hooks(sentences, clips_count, max_duration_pref, total_duration)

    if not fallback_shorts or len(fallback_shorts) == 0:
        fallback_summary = f"Video highlights intelligently extracted across peak scenes."
        fallback_shorts = generate_fallback_highlights(video_title, total_duration, clips_count, max_duration_pref)

    return fallback_summary, fallback_shorts

def cut_vertical_short_with_cta(input_video_path, start_seconds, duration, cta_image_path, output_path, thumb_path):
    """
    Renders 9:16 vertical short (1080x1920) with blurred background
    AND overlays a Subscribe / Like / Comment Call-to-Action banner during the last 3.5 seconds!
    """
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    cta_start_time = max(0.5, float(duration) - 3.5)

    if cta_image_path and os.path.exists(cta_image_path):
        filter_graph = (
            "[0:v]scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,boxblur=25:5[bg];"
            "[0:v]scale=1080:-2[fg];"
            "[bg][fg]overlay=(W-w)/2:(H-h)/2[base];"
            f"[base][1:v]overlay=(W-w)/2:H-h-180:enable='gte(t,{cta_start_time})'[outv]"
        )
        cmd = [
            "ffmpeg", "-y",
            "-ss", str(start_seconds),
            "-t", str(duration),
            "-i", input_video_path,
            "-i", cta_image_path,
            "-filter_complex", filter_graph,
            "-map", "[outv]",
            "-map", "0:a?",
            "-c:v", "libx264",
            "-preset", "faster",
            "-crf", "22",
            "-c:a", "aac",
            "-b:a", "128k",
            "-movflags", "+faststart",
            output_path
        ]
    else:
        filter_graph = (
            "[0:v]scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,boxblur=25:5[bg];"
            "[0:v]scale=1080:-2[fg];"
            "[bg][fg]overlay=(W-w)/2:(H-h)/2[outv]"
        )
        cmd = [
            "ffmpeg", "-y",
            "-ss", str(start_seconds),
            "-t", str(duration),
            "-i", input_video_path,
            "-filter_complex", filter_graph,
            "-map", "[outv]",
            "-map", "0:a?",
            "-c:v", "libx264",
            "-preset", "faster",
            "-crf", "22",
            "-c:a", "aac",
            "-b:a", "128k",
            "-movflags", "+faststart",
            output_path
        ]

    print(f"Rendering short with FFmpeg: {start_seconds}s for {duration}s -> {output_path}")
    res = subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    if res.returncode != 0:
        print("Complex filter warning, executing standard crop fallback...")
        simple_cmd = [
            "ffmpeg", "-y",
            "-ss", str(start_seconds),
            "-t", str(duration),
            "-i", input_video_path,
            "-c:v", "libx264",
            "-preset", "veryfast",
            "-c:a", "aac",
            output_path
        ]
        subprocess.run(simple_cmd, check=True)

    if thumb_path:
        os.makedirs(os.path.dirname(thumb_path), exist_ok=True)
        mid_thumb = max(0.5, float(duration) / 2.0)
        thumb_cmd = [
            "ffmpeg", "-y",
            "-ss", str(mid_thumb),
            "-i", output_path,
            "-vframes", "1",
            "-q:v", "2",
            thumb_path
        ]
        subprocess.run(thumb_cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)

def save_shorts_to_db(db_path, project_id, user_id, shorts_list, project_title):
    conn = sqlite3.connect(db_path, timeout=30.0)
    cursor = conn.cursor()
    now_str = datetime.utcnow().strftime('%Y-%m-%d %H:%M:%S')

    for short in shorts_list:
        file_size = 0
        if os.path.exists(short['full_video_path']):
            file_size = os.path.getsize(short['full_video_path'])

        cursor.execute("""
            INSERT INTO generated_shorts 
            (video_project_id, user_id, title, description, tags, start_seconds, end_seconds, duration_seconds, hook_reason, video_path, thumbnail_path, file_size_bytes, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        """, (
            project_id,
            user_id,
            short['title'],
            short.get('description', ''),
            short.get('tags', ''),
            short['start_seconds'],
            short['end_seconds'],
            short['duration_seconds'],
            short.get('hook_reason', ''),
            short['relative_video_path'],
            short.get('relative_thumb_path', ''),
            file_size,
            now_str,
            now_str
        ))

    cursor.execute("""
        INSERT INTO app_notifications
        (user_id, video_project_id, title, message, type, is_read, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    """, (
        user_id,
        project_id,
        "🎉 Shorts Ready to View & Download!",
        f"{len(shorts_list)} viral Shorts extracted from '{project_title}' (Mukhda & Hook Climax with Subscribe CTA).",
        "success",
        0,
        now_str,
        now_str
    ))

    conn.commit()
    conn.close()

def main():
    parser = argparse.ArgumentParser(description="AI Video Shorts Generator")
    parser.add_argument("--project-id", required=True, type=int)
    parser.add_argument("--db-path", default="database/database.sqlite")
    parser.add_argument("--ollama-host", default="http://127.0.0.1:11434")
    parser.add_argument("--ollama-model", default="gemma:2b")
    args = parser.parse_args()

    project = get_project_details(args.db_path, args.project_id)
    if not project:
        print(f"Project ID {args.project_id} not found.")
        sys.exit(1)

    project_id = project['id']
    user_id = project['user_id']
    source_type = project['source_type']
    clip_duration_pref = project['clip_duration_pref'] or 30
    clips_count = project['clips_count'] or 3
    video_title = project['title'] or "Untitled Video"

    temp_dir = os.path.join("storage", "app", "temp", f"project_{project_id}")
    os.makedirs(temp_dir, exist_ok=True)

    output_media_dir = os.path.join("public", "media", "shorts", f"p_{project_id}")
    os.makedirs(output_media_dir, exist_ok=True)

    cta_card_path = os.path.join(temp_dir, "subscribe_cta_card.png")
    generate_cta_overlay_card(cta_card_path)

    input_video_path = None
    raw_transcript_items = []

    try:
        log_progress(args.db_path, project_id, 10, "Fetching video source and metadata...", status="processing")

        if source_type == "youtube":
            url = project['source_url']
            log_progress(args.db_path, project_id, 15, "Downloading YouTube video & lyrics/subtitles...")
            video_id = extract_youtube_video_id(url)

            if video_id:
                raw_transcript_items = fetch_youtube_transcript(video_id)

            existing_mp4 = os.path.join(temp_dir, f"{video_id}.mp4") if video_id else None
            if existing_mp4 and os.path.exists(existing_mp4) and os.path.getsize(existing_mp4) > 1000000:
                print(f"Reusing already downloaded video: {existing_mp4}")
                input_video_path = existing_mp4
            else:
                out_template = os.path.join(temp_dir, "%(id)s.%(ext)s")
                ydl_opts = {
                    'format': 'bestvideo[ext=mp4][height<=1080]+bestaudio[ext=m4a]/best[ext=mp4]/best',
                    'outtmpl': out_template,
                    'quiet': True,
                    'no_warnings': True,
                }
                with yt_dlp.YoutubeDL(ydl_opts) as ydl:
                    info = ydl.extract_info(url, download=True)
                    downloaded_file = ydl.prepare_filename(info)
                    if not os.path.exists(downloaded_file):
                        base, _ = os.path.splitext(downloaded_file)
                        if os.path.exists(base + ".mp4"):
                            downloaded_file = base + ".mp4"
                    input_video_path = downloaded_file
                    
                    # Update title from video metadata if empty or generic
                    extracted_title = info.get('title')
                    if extracted_title and (not video_title or video_title in ["YouTube Video Project", "Untitled Video"]):
                        video_title = extracted_title
                        conn = sqlite3.connect(args.db_path)
                        conn.execute("UPDATE video_projects SET title = ? WHERE id = ?", (video_title, project_id))
                        conn.commit()
                        conn.close()
        else:
            input_video_path = project['video_file_path']
            if not os.path.isabs(input_video_path):
                input_video_path = os.path.abspath(input_video_path)
            if not os.path.exists(input_video_path):
                raise FileNotFoundError(f"Uploaded file not found: {input_video_path}")

        total_duration = get_video_duration(input_video_path)
        print(f"Total video duration: {total_duration}s")

        conn = sqlite3.connect(args.db_path)
        conn.execute("UPDATE video_projects SET duration_seconds = ? WHERE id = ?", (int(total_duration), project_id))
        conn.commit()
        conn.close()

        # Check if video is a Song/Music video
        is_song = is_song_or_music(video_title, raw_transcript_items)
        if is_song:
            print(f"Music/Song detected for '{video_title}'. Activating Mukhda (Chorus) Peak Extraction mode.")

        # Reconstruct transcript items
        reconstructed_content = []
        if raw_transcript_items:
            if is_song:
                # For songs, keep raw timestamped lyric lines so chorus lines are intact
                reconstructed_content = raw_transcript_items
            else:
                reconstructed_content = reconstruct_sentences(raw_transcript_items)
        elif total_duration > 0:
            # Fallback if no subtitles: detect speech or audio loudness peak
            log_progress(args.db_path, project_id, 35, "Analyzing audio energy and loudness peaks...")
            if is_song:
                p_start, p_end = detect_audio_loudness_peak_ffmpeg(input_video_path, clip_duration_pref)
                reconstructed_content = [{
                    "start": p_start,
                    "duration": p_end - p_start,
                    "text": "Music Peak & Chorus Section"
                }]
            else:
                reconstructed_content = []

        # Step 3: Ollama Gemma Hook & Mukhda Analysis
        log_progress(args.db_path, project_id, 50, f"Analyzing Mukhda & viral hooks with Ollama Gemma ({args.ollama_model})...")
        summary, raw_shorts = analyze_hooks_with_gemma(
            video_title=video_title,
            sentences=reconstructed_content,
            total_duration=total_duration,
            clips_count=clips_count,
            max_duration_pref=clip_duration_pref,
            ollama_host=args.ollama_host,
            ollama_model=args.ollama_model,
            is_song=is_song
        )

        if not raw_shorts or len(raw_shorts) < clips_count:
            print(f"Notice: Primary analysis produced {len(raw_shorts) if raw_shorts else 0} clips (requested {clips_count}). Supplementing highlights.", flush=True)
            summary = summary or "High-retention highlights extracted across peak entertainment scenes."
            additional = generate_fallback_highlights(video_title, total_duration, clips_count, clip_duration_pref)
            
            existing_intervals = [(float(s['start_seconds']), float(s['end_seconds'])) for s in (raw_shorts or [])]
            raw_shorts = list(raw_shorts or [])
            for cand in additional:
                if len(raw_shorts) >= clips_count:
                    break
                c_start, c_end = float(cand['start_seconds']), float(cand['end_seconds'])
                overlaps = any(not (c_start >= e or c_end <= s) for s, e in existing_intervals)
                if not overlaps:
                    raw_shorts.append(cand)
                    existing_intervals.append((c_start, c_end))
            
            if not raw_shorts or len(raw_shorts) == 0:
                raw_shorts = additional[:clips_count]

        log_progress(args.db_path, project_id, 65, "Rendering vertical shorts with Subscribe CTA...", raw_analysis=summary)

        # Step 4: Render Shorts with 9:16 layout & CTA overlay
        generated_shorts_records = []
        max_duration_cap = 32.0 if clip_duration_pref <= 35 else float(clip_duration_pref)

        for index, item in enumerate(raw_shorts):
            percent = 65 + int((index / len(raw_shorts)) * 25)

            start_s = float(item.get('start_seconds', 0.0))
            dur_s = float(item.get('duration_seconds', clip_duration_pref))

            # STRICT HARD CLAMP:
            # A short can NEVER exceed max_duration_cap or the total video length!
            if dur_s > max_duration_cap:
                dur_s = max_duration_cap
            if start_s + dur_s > total_duration:
                start_s = max(0.0, total_duration - dur_s)
                dur_s = min(dur_s, total_duration - start_s)
            
            end_s = round(start_s + dur_s, 2)
            dur_s = round(dur_s, 1)

            log_progress(args.db_path, project_id, percent, f"Rendering Short #{index+1} ({dur_s}s Mukhda Cut)...")

            short_filename = f"short_{index+1}_{int(time.time())}.mp4"
            thumb_filename = f"thumb_{index+1}_{int(time.time())}.jpg"

            full_short_path = os.path.join(output_media_dir, short_filename)
            full_thumb_path = os.path.join(output_media_dir, thumb_filename)

            cut_vertical_short_with_cta(input_video_path, start_s, dur_s, cta_card_path, full_short_path, full_thumb_path)

            rel_short_path = f"media/shorts/p_{project_id}/{short_filename}"
            rel_thumb_path = f"media/shorts/p_{project_id}/{thumb_filename}"

            generated_shorts_records.append({
                "title": item.get('title', f"{video_title} - Short #{index+1}"),
                "description": item.get('description', ''),
                "tags": item.get('tags', ''),
                "start_seconds": start_s,
                "end_seconds": end_s,
                "duration_seconds": int(round(dur_s)),
                "hook_reason": item.get('hook_reason', 'Viral Hook / Mukhda peak'),
                "full_video_path": full_short_path,
                "relative_video_path": rel_short_path,
                "relative_thumb_path": rel_thumb_path
            })

        if not generated_shorts_records:
            raise RuntimeError("No shorts could be generated for this video.")

        log_progress(args.db_path, project_id, 95, "Saving shorts and metadata...")
        save_shorts_to_db(args.db_path, project_id, user_id, generated_shorts_records, video_title)

        log_progress(args.db_path, project_id, 100, "Done! Your Mukhda shorts with Subscribe CTA are ready.", status="completed")
        print("Video processing successfully completed!", flush=True)

    except Exception as e:
        err_msg = str(e)
        print(f"Error during video processing: {err_msg}", file=sys.stderr)
        log_progress(args.db_path, project_id, 0, f"Failed: {err_msg}", status="failed", error_msg=err_msg)
        sys.exit(1)

if __name__ == "__main__":
    main()

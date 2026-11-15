import os
import subprocess
import sys

base_dir = r"c:\short_creator\public\media\nostalgia_shorts"
audio_path = os.path.join(base_dir, "nostalgia_audio.mp3")

# 8 source vertical images
scenes_raw = [
    os.path.join(base_dir, "scene_1_shaktimaan.jpg"),
    os.path.join(base_dir, "scene_2_tiffin.jpg"),
    os.path.join(base_dir, "scene_3_cassette.jpg"),
    os.path.join(base_dir, "scene_4_paper_boats.jpg"),
    os.path.join(base_dir, "scene_5_cycle_toli.jpg"),
    os.path.join(base_dir, "scene_6_phone_loneliness.jpg"),
    os.path.join(base_dir, "scene_7_mitti_friends.jpg"),
    os.path.join(base_dir, "scene_8_empty_street.jpg"),
]

print("Step 1: Preparing 16:9 (1920x1080) landscape frames for each scene...", flush=True)

# Generate 1920x1080 versions for all 8 scenes
long_frames = []
for idx, src_img in enumerate(scenes_raw):
    out_frame = os.path.join(base_dir, f"frame_16x9_scene_{idx+1}.jpg")
    long_frames.append(out_frame)
    
    # Filter graph: blurred 16:9 backdrop + sharp centered image
    filter_complex = (
        "[0:v]scale=1920:1080:force_original_aspect_ratio=increase,crop=1920:1080,boxblur=28:5,eq=brightness=-0.1:contrast=1.08[bg];"
        "[0:v]scale=-2:1040[fg];"
        "[bg][fg]overlay=(W-w)/2:(H-h)/2[outv]"
    )
    cmd = [
        "ffmpeg", "-y",
        "-i", src_img,
        "-filter_complex", filter_complex,
        "-map", "[outv]",
        "-vframes", "1",
        "-q:v", "2",
        out_frame
    ]
    subprocess.run(cmd, check=True)

print("All 8 16:9 frames prepared successfully.", flush=True)

# Step 2: Generate SRT subtitles for the lyrics
srt_path = os.path.join(base_dir, "nostalgia_lyrics.srt")
srt_content = """1
00:00:01,000 --> 00:00:16,000
Purani Gali • 90s Memories Anthem 🎸

2
00:00:16,500 --> 00:00:21,000
Sunday ki subah woh Shaktimaan ka aana 📺

3
00:00:21,000 --> 00:00:25,000
Mom ki daant ke baad, wo tiffin churaana 🍱

4
00:00:25,000 --> 00:00:29,000
Cassette ke fite ko pen se phiraana 📼

5
00:00:29,000 --> 00:00:34,000
Wo baarish ke paani me kaagaz ki naav tairana ⛵

6
00:00:34,000 --> 00:00:38,500
Ab sab kuch paas hai, par woh baat nahi

7
00:00:38,500 --> 00:00:44,000
Raaton ko jaagne ki ab wo saugaat nahi...

8
00:00:44,000 --> 00:00:48,000
Kachhe dhaagon me bandhi thi woh yaaron ki toli 🚲

9
00:00:48,000 --> 00:00:52,000
Cycle ki ghanti pe nikalti thi rangoli! 🔔

10
00:00:52,000 --> 00:00:57,000
Aaj haath mein phone hai, par dil khaali-khaali hai 📱

11
00:00:57,000 --> 00:01:02,000
Waqt ki is daud mein, kahan wo puraani gali hai?

12
00:01:02,000 --> 00:01:21,500
O bachpan ke din... laut ke aaja kahin! ❤️✨

13
00:01:21,500 --> 00:01:26,000
Mitti ki khushboo aur doston ka shor 🌳

14
00:01:26,000 --> 00:01:30,000
Bina kisi matlab ke hanste the sabhi chaaro aur 😄

15
00:01:30,000 --> 00:01:35,000
Kaash waqt thoda peeche mud jaaye kahin ⏳

16
00:01:35,000 --> 00:01:40,000
Aao phir se wahi bachpan jeete wahin! 💫

17
00:01:40,000 --> 00:01:44,000
Kachhe dhaagon me bandhi thi woh yaaron ki toli 🚲

18
00:01:44,000 --> 00:01:48,000
Cycle ki ghanti pe nikalti thi rangoli! 🔔

19
00:01:48,000 --> 00:01:53,000
Aaj haath mein phone hai, par dil khaali-khaali hai 📱

20
00:01:53,000 --> 00:01:58,000
Waqt ki is daud mein, kahan wo puraani gali hai?

21
00:01:58,000 --> 00:02:07,000
O bachpan ke din... laut ke aaja kahin! 🎶❤️

22
00:02:07,000 --> 00:02:16,000
Bas woh sab hi badal gaye... 🍂

23
00:02:16,000 --> 00:02:29,000
Wahi gali hai, par ab koi nahi... 🥺
"""

with open(srt_path, "w", encoding="utf-8") as f:
    f.write(srt_content.strip())

print("SRT Subtitles generated.", flush=True)

# Step 3: Concat timeline for the full 149 seconds
timeline = [
    # 00:00 - 00:16.5: Intro music & nostalgia atmosphere
    (long_frames[7], 8.0),   # Scene 8: Empty street intro
    (long_frames[4], 8.5),   # Scene 5: Cycle toli
    # 00:16.5 - 00:21: Shaktimaan
    (long_frames[0], 4.5),   # Scene 1: Shaktimaan
    # 00:21 - 00:25: Tiffin
    (long_frames[1], 4.0),   # Scene 2: Tiffin chori
    # 00:25 - 00:29: Cassette
    (long_frames[2], 4.0),   # Scene 3: Cassette tape
    # 00:29 - 00:34: Paper boats
    (long_frames[3], 5.0),   # Scene 4: Paper boats
    # 00:34 - 00:44: Ab sab kuch paas hai par wo baat nahi
    (long_frames[5], 10.0),  # Scene 6: Modern phone loneliness
    # 00:44 - 00:52: Kacche dhaagon mein bandhi thi wo yaaron ki toli
    (long_frames[4], 8.0),   # Scene 5: Cycle toli
    # 00:52 - 01:02: Aaj haath mein phone hai
    (long_frames[5], 10.0),  # Scene 6: Phone loneliness
    # 01:02 - 01:21.5: Wo bachpan ke din (Chorus & Music)
    (long_frames[6], 9.5),   # Scene 7: Mitti friends
    (long_frames[3], 10.0),  # Scene 4: Paper boats
    # 01:21.5 - 01:30: Mitti ki khushboo aur doston ka shor
    (long_frames[6], 8.5),   # Scene 7: Mitti friends
    # 01:30 - 01:40: Kaash waqt thoda peeche mud jaaye
    (long_frames[0], 5.0),   # Scene 1: Shaktimaan
    (long_frames[1], 5.0),   # Scene 2: Tiffin
    # 01:40 - 01:48: Yaaron ki toli
    (long_frames[4], 8.0),   # Scene 5: Cycle toli
    # 01:48 - 01:58: Aaj haath mein phone hai
    (long_frames[5], 10.0),  # Scene 6: Phone loneliness
    # 01:58 - 02:07: Wo bachpan ke din laut ke aaja kahin
    (long_frames[6], 9.0),   # Scene 7: Mitti friends
    # 02:07 - 02:29: Wahi gali hai par ab koi nahi (Outro)
    (long_frames[7], 22.2),  # Scene 8: Empty street outro
]

concat_txt = os.path.join(base_dir, "long_concat.txt")
with open(concat_txt, "w") as f:
    for img, dur in timeline:
        f.write(f"file '{img}'\n")
        f.write(f"duration {dur}\n")
    f.write(f"file '{timeline[-1][0]}'\n")

out_long_mp4 = os.path.join(base_dir, "90s_Nostalgia_Full_Song_Long_16x9.mp4")

# Format SRT path for ffmpeg subtitles filter (escape backslashes and colons)
escaped_srt = srt_path.replace("\\", "/").replace(":", "\\:")
subtitles_filter = f"subtitles='{escaped_srt}':force_style='FontSize=22,FontName=Arial,PrimaryColour=&H00FFFFFF,OutlineColour=&H00000000,BackColour=&H80000000,BorderStyle=4,Outline=1,Shadow=1,MarginV=45'"

cmd_long = [
    "ffmpeg", "-y",
    "-f", "concat", "-safe", "0", "-i", concat_txt,
    "-i", audio_path,
    "-vf", f"scale=1920:1080:force_original_aspect_ratio=increase,crop=1920:1080,fps=25,{subtitles_filter}",
    "-c:v", "libx264",
    "-preset", "faster",
    "-crf", "19",
    "-c:a", "aac",
    "-b:a", "192k",
    "-pix_fmt", "yuv420p",
    "-shortest",
    "-movflags", "+faststart",
    out_long_mp4
]

print("Rendering 1920x1080 Long Format Video with burned-in subtitles...", flush=True)
res = subprocess.run(cmd_long, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
if res.returncode != 0:
    print("Warning with subtitles filter, retrying without burned-in subtitles...", flush=True)
    cmd_fallback = [
        "ffmpeg", "-y",
        "-f", "concat", "-safe", "0", "-i", concat_txt,
        "-i", audio_path,
        "-vf", "scale=1920:1080:force_original_aspect_ratio=increase,crop=1920:1080,fps=25",
        "-c:v", "libx264",
        "-preset", "faster",
        "-crf", "19",
        "-c:a", "aac",
        "-b:a", "192k",
        "-pix_fmt", "yuv420p",
        "-shortest",
        "-movflags", "+faststart",
        out_long_mp4
    ]
    subprocess.run(cmd_fallback, check=True)

print(f"Long Format 16:9 Video successfully rendered -> {out_long_mp4}", flush=True)

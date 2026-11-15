import os
import subprocess
import sys

base_dir = r"c:\short_creator\public\media\nostalgia_shorts"
audio_path = os.path.join(base_dir, "nostalgia_audio.mp3")

# Scene images
img_shaktimaan = os.path.join(base_dir, "scene_1_shaktimaan.jpg")
img_tiffin = os.path.join(base_dir, "scene_2_tiffin.jpg")
img_cassette = os.path.join(base_dir, "scene_3_cassette.jpg")
img_boats = os.path.join(base_dir, "scene_4_paper_boats.jpg")
img_cycle = os.path.join(base_dir, "scene_5_cycle_toli.jpg")
img_phone = os.path.join(base_dir, "scene_6_phone_loneliness.jpg")
img_mitti = os.path.join(base_dir, "scene_7_mitti_friends.jpg")
img_empty = os.path.join(base_dir, "scene_8_empty_street.jpg")

print("Rendering 90s Nostalgia Vertical Videos...", flush=True)

# -------------------------------------------------------------
# Video 1: 60-Second Viral Short (00:16 to 01:16)
# -------------------------------------------------------------
# Timeline in the 60s short:
# 0s - 5s: Shaktimaan Sunday Morning ("Sunday ki subah wo Shaktimaan ka aana")
# 5s - 9s: Tiffin stealing ("Mom ke daant ke baad wo tiffin churana")
# 9s - 13s: Cassette rewind ("Cassette ke fite ko pen se firana")
# 13s - 18s: Paper boats ("Wo baarish ke paani mein kagaz ki naav tairana")
# 18s - 28s: Phone loneliness ("Ab sab kuch paas hai par wo baat nahi...")
# 28s - 36s: Cycle toli ("Kacche dhaagon mein bandhi thi wo yaaron ki toli...")
# 36s - 46s: Phone contrast ("Aaj haath mein phone hai par dil khaali khaali hai...")
# 46s - 60s: Mitti friends chorus peak ("Wo bachpan ke din laut ke aaja kahin...")

short_audio_temp = os.path.join(base_dir, "temp_short_audio.aac")
cmd_trim_audio = [
    "ffmpeg", "-y",
    "-ss", "16.5",
    "-t", "59.5",
    "-i", audio_path,
    "-c:a", "aac",
    "-b:a", "192k",
    short_audio_temp
]
subprocess.run(cmd_trim_audio, check=True)

# Build FFmpeg filter_complex for the 60s Short with smooth Ken Burns zoom
# 1080x1920 vertical format
out_short_mp4 = os.path.join(base_dir, "90s_Nostalgia_Childhood_Short.mp4")

# Scene segments in 60s short:
# 0: Shaktimaan (5s)
# 1: Tiffin (4s)
# 2: Cassette (4s)
# 3: Boats (5s)
# 4: Phone (10s)
# 5: Cycle (8s)
# 6: Phone (10s)
# 7: Mitti (13.5s)

short_inputs = [
    img_shaktimaan,
    img_tiffin,
    img_cassette,
    img_boats,
    img_phone,
    img_cycle,
    img_phone,
    img_mitti
]

durations = [5.0, 4.0, 4.0, 5.0, 10.0, 8.0, 10.0, 13.5]

filter_parts = []
concat_inputs = []

for i, (img, dur) in enumerate(zip(short_inputs, durations)):
    pts_dur = int(dur * 25)
    # Slow zoom-in effect (Ken Burns)
    filter_parts.append(
        f"[{i}:v]scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,"
        f"zoompan=z='min(zoom+0.0012,1.15)':d={pts_dur}:s=1080x1920:fps=25[v{i}];"
    )
    concat_inputs.append(f"[v{i}]")

filter_str = "".join(filter_parts) + "".join(concat_inputs) + f"concat=n={len(short_inputs)}:v=1:a=0[vout]"

cmd_short = ["ffmpeg", "-y"]
for img in short_inputs:
    cmd_short.extend(["-loop", "1", "-t", str(durations[short_inputs.index(img)]), "-i", img])

cmd_short.extend([
    "-i", short_audio_temp,
    "-filter_complex", filter_str,
    "-map", "[vout]",
    "-map", f"{len(short_inputs)}:a",
    "-c:v", "libx264",
    "-preset", "faster",
    "-crf", "20",
    "-c:a", "aac",
    "-b:a", "192k",
    "-pix_fmt", "yuv420p",
    "-shortest",
    "-movflags", "+faststart",
    out_short_mp4
])

print("Rendering 60s Short MP4...", flush=True)
res_short = subprocess.run(cmd_short, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
if res_short.returncode != 0:
    print("Warning on complex zoom filter, falling back to clean scaled cross-clip...", flush=True)
    # Simplified high-speed direct render
    concat_txt_path = os.path.join(base_dir, "short_concat.txt")
    with open(concat_txt_path, "w") as f:
        for img, dur in zip(short_inputs, durations):
            f.write(f"file '{img}'\n")
            f.write(f"duration {dur}\n")
        f.write(f"file '{short_inputs[-1]}'\n")

    cmd_simple = [
        "ffmpeg", "-y",
        "-f", "concat", "-safe", "0", "-i", concat_txt_path,
        "-i", short_audio_temp,
        "-vf", "scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,fps=25",
        "-c:v", "libx264",
        "-preset", "faster",
        "-crf", "20",
        "-c:a", "aac",
        "-b:a", "192k",
        "-pix_fmt", "yuv420p",
        "-shortest",
        "-movflags", "+faststart",
        out_short_mp4
    ]
    subprocess.run(cmd_simple, check=True)

print(f"60s Short rendered successfully -> {out_short_mp4}", flush=True)

# -------------------------------------------------------------
# Video 2: Full Song Vertical Video (00:00 to 02:29 - 149 seconds)
# -------------------------------------------------------------
out_full_mp4 = os.path.join(base_dir, "90s_Nostalgia_Full_Song_Vertical.mp4")

full_scenes = [
    # 00:00 - 00:16.5: Intro music & nostalgia atmosphere
    (img_empty, 8.0),
    (img_cycle, 8.5),
    # 00:16.5 - 00:21: Shaktimaan
    (img_shaktimaan, 4.5),
    # 00:21 - 00:25: Tiffin
    (img_tiffin, 4.0),
    # 00:25 - 00:29: Cassette
    (img_cassette, 4.0),
    # 00:29 - 00:34: Paper boats
    (img_boats, 5.0),
    # 00:34 - 00:44: Ab sab kuch paas hai par wo baat nahi
    (img_phone, 10.0),
    # 00:44 - 00:52: Kacche dhaagon mein bandhi thi wo yaaron ki toli
    (img_cycle, 8.0),
    # 00:52 - 01:02: Aaj haath mein phone hai
    (img_phone, 10.0),
    # 01:02 - 01:22: Wo bachpan ke din laut ke aaja kahin (Chorus)
    (img_mitti, 10.0),
    (img_boats, 10.0),
    # 01:22 - 01:30: Mitti ki khushboo aur doston ka shor
    (img_mitti, 8.0),
    # 01:30 - 01:40: Kaash waqt thoda peeche mud jaaye
    (img_shaktimaan, 5.0),
    (img_tiffin, 5.0),
    # 01:40 - 01:48: Kacche dhaagon mein yaaron ki toli
    (img_cycle, 8.0),
    # 01:48 - 01:58: Aaj haath mein phone hai
    (img_phone, 10.0),
    # 01:58 - 02:07: Wo bachpan ke din
    (img_mitti, 9.0),
    # 02:07 - 02:29: Wahi gali hai par ab koi nahi (Outro)
    (img_empty, 22.0)
]

concat_full_txt = os.path.join(base_dir, "full_concat.txt")
with open(concat_full_txt, "w") as f:
    for img, dur in full_scenes:
        f.write(f"file '{img}'\n")
        f.write(f"duration {dur}\n")
    f.write(f"file '{full_scenes[-1][0]}'\n")

cmd_full = [
    "ffmpeg", "-y",
    "-f", "concat", "-safe", "0", "-i", concat_full_txt,
    "-i", audio_path,
    "-vf", "scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,fps=25",
    "-c:v", "libx264",
    "-preset", "faster",
    "-crf", "20",
    "-c:a", "aac",
    "-b:a", "192k",
    "-pix_fmt", "yuv420p",
    "-shortest",
    "-movflags", "+faststart",
    out_full_mp4
]

print("Rendering Full Song Vertical Video MP4...", flush=True)
subprocess.run(cmd_full, check=True)
print(f"Full Song Vertical Video rendered successfully -> {out_full_mp4}", flush=True)

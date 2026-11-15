# 🎬 AI Shorts Creator (Laravel + Python + FFmpeg + Ollama Gemma)

Long videos ya YouTube video links se automatically engaging, high-retention 30s ya 60s ke YouTube Shorts / Reels generate karne wala complete web platform.

---

## 🚀 Features (Sabhi Features Included Hain)

1. **YouTube Link ya Direct Video Upload**:
   - Kisi bhi public YouTube video ka URL paste karein.
   - Ya apne computer se raw long video file (MP4, MOV, MKV up to 500MB) upload karein.
2. **Ollama - Gemma AI Video & Hook Analysis**:
   - Video ke content aur audio transcript ko Ollama Gemma model analyze karta hai.
   - Video ke best viral hook points (emotional peaks, curiosity gaps, key punchlines) detect karta hai.
3. **30 Sec ya 60 Sec Clips Decision**:
   - User decide kar sakta hai ki use 30s ke shorts chahiye ya 60s ke, aur kitne clips extract karne hain (1, 2, 3, ya 5).
4. **9:16 Vertical Video Shorts Rendering**:
   - FFmpeg background worker automatically segments cut karta hai aur modern 9:16 vertical shorts format (1080x1920) me format karta hai (aesthetic blurred background + centered video).
5. **AI Generated Viral Titles, Descriptions & Tags**:
   - Har short ke sath scroll-stopping Title (with emojis), 2-3 line Description (with viral hashtags), aur SEO Tags automatically generate hote hain.
6. **1-Click Copy Buttons**:
   - Title, Description, aur Tags ke samne instant copy buttons hain with toast notification feedback.
7. **Direct Video Download**:
   - Generated short video ko preview karne ke liye video player aur 1-click Direct Download button.
8. **History Tracking**:
   - User ke dashboard me pehle ke sabhi processed videos aur unke shorts safe rehte hain.
9. **Background Queue Processing**:
   - Agar user video submit karke browser band kar de ya doosre page par chala jaye, tab bhi background queue worker video download, transcription, AI analysis aur video rendering complete karta hai.
10. **In-App Notifications & Dashboard Status Tracking**:
    - Real-time progress bar (0% - 100%) aur current step status (e.g. *Downloading*, *Speech Analysis*, *Ollama Gemma Hook Analysis*, *Rendering FFmpeg shorts*).
    - Kaam khatam hone par automatic notification create hota hai.
11. **User Registration & Login**:
    - Secure user authentication system (Registration, Login, Remember Me, User Dashboard).

---

## 🛠️ Tech Stack

- **Backend / Web Framework**: Laravel 13 (PHP 8.3)
- **Database**: SQLite (Ready to use, zero configuration needed)
- **Queue System**: Laravel Database Queue (`php artisan queue:listen`)
- **Video & Audio Engine**: FFmpeg (8.1) + yt-dlp
- **Speech-to-Text / Transcription**: `youtube-transcript-api` + `openai-whisper`
- **AI / LLM**: Ollama (`gemma:2b`, `gemma`, `gemma2`)
- **Frontend**: Blade + Tailwind CSS (via CDN) + Alpine.js + FontAwesome 6

---

## ⚡ Quick Start (Chalane Ka Tarika)

### Tarika 1: 1-Click Startup Script (Sabse Aasan)
Project root folder me `run_app.bat` file ko double-click karein:
```bat
run_app.bat
```
Yeh script automatically:
1. Database check karega
2. Background queue worker ko alag window me start karega
3. Laravel web server (`http://127.0.0.1:8000`) launch karega
4. Browser me application open kar dega

---

### Tarika 2: Manual Commands

**Terminal 1 (Background Queue Worker):**
```bash
php artisan queue:listen --timeout=1800
```

**Terminal 2 (Laravel Web Server):**
```bash
php artisan serve --port=8000
```

Browser open karein:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 👤 Test / Demo Login Credentials

Aap direct naya account register kar sakte hain, ya pehle se bani demo user ID se login kar sakte hain:

- **Account 1**:
  - **Email**: `creator@shortstudio.com`
  - **Password**: `Shorts@1234`
- **Account 2 (Admin)**:
  - **Email**: `admin@example.com`
  - **Password**: `password123`

---

## 🧠 Ollama Gemma Model Setup

Ollama Gemma model use karne ke liye:

1. Terminal me Ollama start karein:
   ```bash
   ollama serve
   ```
2. Gemma model pull karein:
   ```bash
   ollama pull gemma:2b
   ```
   *(Aap `.env` me `OLLAMA_MODEL=gemma:2b` ya `gemma:7b` ya `gemma2` set kar sakte hain)*.

> **Note**: Agar kisi karan Ollama service band ho, toh hamara intelligent smart-heuristic fallback engine automatically video ke energy peaks detect karke viral titles/descriptions generate kar deta hai — is wajah se background worker kabhi fail nahi hota!

---

## 📁 Project Structure

```
c:\short_creator/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # Login, Register, Logout
│   │   ├── DashboardController.php     # User dashboard & metrics
│   │   ├── VideoProjectController.php  # Video submission, status API & shorts view
│   │   └── NotificationController.php  # User alerts & notifications
│   ├── Jobs/
│   │   └── ProcessVideoJob.php         # Background Queue worker job
│   └── Models/
│       ├── User.php
│       ├── VideoProject.php
│       ├── GeneratedShort.php
│       └── AppNotification.php
├── python/
│   └── process_video.py                # Video download, transcript, Ollama Gemma & FFmpeg rendering
├── resources/views/
│   ├── layouts/app.blade.php           # Sleek dark-mode master layout
│   ├── welcome.blade.php               # Landing page
│   ├── auth/                           # Login & Register views
│   ├── dashboard/index.blade.php       # History, status & stats
│   ├── projects/
│   │   ├── create.blade.php            # YouTube URL / Video Upload form
│   │   └── show.blade.php              # Real-time progress & Shorts Showcase Studio
│   └── notifications/index.blade.php   # In-app notifications
├── public/media/
│   ├── uploads/                        # Uploaded source videos
│   └── shorts/                         # Extracted 9:16 vertical shorts & thumbnails
├── run_app.bat                         # 1-Click application launcher
└── run_worker.bat                      # Background queue launcher
```

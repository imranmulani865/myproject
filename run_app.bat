@echo off
title AI Shorts Creator - Web Server
echo ========================================================
echo       AI Shorts Creator - Starting Application
echo ========================================================
echo.

cd /d "%~dp0"

echo [1/3] Checking environment & migrations...
php artisan migrate --force

echo.
echo [2/3] Starting Background Queue Worker in a separate window...
start "AI Shorts Creator - Queue Worker" cmd /k "php artisan queue:listen --timeout=1800"

echo.
echo [3/3] Starting Laravel Web Server on http://127.0.0.1:1996 ...
echo.
echo Opening browser to http://127.0.0.1:1996
start http://127.0.0.1:1996
echo.
php artisan serve --port=1996
pause

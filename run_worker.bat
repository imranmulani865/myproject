@echo off
title AI Shorts Creator - Queue Worker
echo ========================================================
echo       AI Shorts Creator - Background Queue Worker
echo ========================================================
echo.

cd /d "%~dp0"
php artisan queue:listen --timeout=1800
pause

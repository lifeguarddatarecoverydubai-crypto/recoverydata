@echo off
title Recovery Data Dubai - Laravel CMS Server
echo ========================================================
echo Starting Recovery Data Server on http://127.0.0.1:8000
echo ========================================================
echo.

set PATH=C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64;C:\laragon\bin\composer;C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin;%PATH%

cd /d "%~dp0"
php artisan serve --host=127.0.0.1 --port=8000

pause

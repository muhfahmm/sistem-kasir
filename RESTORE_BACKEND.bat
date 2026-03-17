@echo off
set PHP_EXE="C:\Users\fahim\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"

set SRC_BACKUP="C:\fhm\PROJECT - ANTIGRAVITY\SISTEM KASIR\backend_backup_final"
set DST_BACKEND="C:\fhm\PROJECT - ANTIGRAVITY\SISTEM KASIR\backend"

echo Restoring files...
copy /Y %SRC_BACKUP%\.env %DST_BACKEND%\.env
xcopy /E /I /Y %SRC_BACKUP%\app %DST_BACKEND%\app
xcopy /E /I /Y %SRC_BACKUP%\routes %DST_BACKEND%\routes

echo Generating Key...
pushd %DST_BACKEND%
%PHP_EXE% artisan key:generate
echo Finalizing...
%PHP_EXE% artisan migrate --force

echo Backend Restoration Complete!
echo You can now run: %PHP_EXE% artisan serve
pause

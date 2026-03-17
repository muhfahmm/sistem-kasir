@echo off
set PHP_EXE="C:\Users\fahim\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
set COMPOSER_PHAR="C:\fhm\PROJECT - ANTIGRAVITY\SISTEM KASIR\composer.phar"

echo Initializing Laravel Project at backend...
%PHP_EXE% %COMPOSER_PHAR% create-project laravel/laravel backend --prefer-dist --no-interaction --ignore-platform-reqs

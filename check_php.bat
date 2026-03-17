@echo off
set PHP_EXE="C:\Users\fahim\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
echo Checking PHP INI:
%PHP_EXE% --ini
echo.
echo Checking PHP Modules:
%PHP_EXE% -m

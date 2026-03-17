@echo off
set PHP_EXE="C:\Users\fahim\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"

echo Cleaning up and reorganizing backend...
if exist backend\backend_new (
    move "backend\backend_new" "backend_temp"
    rmdir /S /Q "backend"
    move "backend_temp" "backend"
)

echo Finalizing Setup...
if exist backend (
    pushd backend
    %PHP_EXE% artisan key:generate
    echo Starting Backend Server...
    %PHP_EXE% artisan serve
    popd
) else (
    echo BACKEND FOLDER NOT FOUND!
)

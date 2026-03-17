@echo off
set PHP_EXE="C:\Users\fahim\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
set COMPOSER_PHAR="C:\fhm\PROJECT - ANTIGRAVITY\SISTEM KASIR\composer.phar"

echo Creating Fresh Laravel Project in backend_new...
%PHP_EXE% %COMPOSER_PHAR% create-project laravel/laravel backend_new --prefer-dist --no-interaction

if exist backend_new (
    echo Project created successfully.
    echo Copying custom files from backend to backend_new...
    
    if exist backend\.env copy /Y "backend\.env" "backend_new\.env"
    if exist backend\app\Models xcopy /E /I /Y "backend\app\Models" "backend_new\app\Models"
    if exist backend\app\Http\Controllers xcopy /E /I /Y "backend\app\Http\Controllers" "backend_new\app\Http\Controllers"
    if exist backend\routes\api.php copy /Y "backend\routes\api.php" "backend_new\routes\api.php"
    
    echo Finalizing backend_new setup...
    pushd backend_new
    %PHP_EXE% artisan key:generate
    popd
    
    echo Switching directories...
    move backend backend_old
    move backend_new backend
    
    echo Full backend setup complete.
) else (
    echo FAILED to create Laravel project.
)

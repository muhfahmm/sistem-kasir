@echo off
set PHP_EXE="C:\Users\fahim\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
set COMPOSER_PHAR="C:\fhm\PROJECT - ANTIGRAVITY\SISTEM KASIR\composer.phar"

echo Creating Laravel Project...
%PHP_EXE% %COMPOSER_PHAR% create-project laravel/laravel backend --prefer-dist

echo Project created. Customizing...
copy /Y "backend_backup\.env" "backend\.env"
copy /Y "backend_backup\app\Models\*" "backend\app\Models\"
copy /Y "backend_backup\app\Http\Controllers\*" "backend\app\Http\Controllers\"
copy /Y "backend_backup\routes\api.php" "backend\routes\api.php"

pushd backend
echo Generating Key...
%PHP_EXE% artisan key:generate
echo Running Migrations...
%PHP_EXE% artisan migrate --force
popd

echo Full backend setup complete.

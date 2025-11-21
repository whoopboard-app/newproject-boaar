@echo off
echo ================================================
echo Installing Laravel Commentable Package (Free)
echo ================================================
echo.

cd /d C:\wamp64\www\whoopboard

echo Step 1: Installing package via Composer...
C:\wamp64\bin\php\php8.1.13\php.exe C:\wamp64\www\composer.phar require laraeast/laravel-commentable
if %errorlevel% neq 0 (
    echo Error: Composer installation failed.
    pause
    exit /b 1
)
echo.

echo Step 2: Publishing migrations...
C:\wamp64\bin\php\php8.1.13\php.exe artisan vendor:publish --provider="Laraeast\LaravelCommentable\Providers\LaravelCommentableServiceProvider" --tag="migrations"
if %errorlevel% neq 0 (
    echo Error: Failed to publish migrations.
    pause
    exit /b 1
)
echo.

echo Step 3: Running migrations...
C:\wamp64\bin\php\php8.1.13\php.exe artisan migrate
if %errorlevel% neq 0 (
    echo Error: Failed to run migrations.
    pause
    exit /b 1
)
echo.

echo ================================================
echo Installation Complete!
echo ================================================
pause

@echo off
echo ================================================
echo Running App Settings Migration
echo ================================================
echo.

cd /d C:\wamp64\www\whoopboard

echo Step 1: Running migration...
C:\wamp64\bin\php\php8.1.13\php.exe artisan migrate
echo.

echo Step 2: Creating storage link for logo uploads...
C:\wamp64\bin\php\php8.1.13\php.exe artisan storage:link
echo.

echo ================================================
echo Setup Complete!
echo ================================================
echo.
echo You can now access General Settings from:
echo Settings -^> General Settings
echo.
pause

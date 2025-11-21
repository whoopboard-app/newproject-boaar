@echo off
echo ================================================
echo Running Roadmap Items Migration
echo ================================================
echo.

cd /d C:\wamp64\www\whoopboard

echo Running migration...
C:\wamp64\bin\php\php8.1.13\php.exe artisan migrate
echo.

echo ================================================
echo Migration Complete!
echo ================================================
echo.
echo Roadmap Items tables have been created.
echo.
pause

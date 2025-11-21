@echo off
echo ================================================
echo Running Show in Roadmap Field Migration
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
echo The "Enable this feedback in Roadmap" field has been added to the feedback form.
echo.
pause

@echo off
echo Checking database existence and required tables...

REM Check if database exists and has required tables
mysql -u root -N -e "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'kdobrota'" > nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo Error: Database 'kdobrota' not found!
    echo Please create the database and run migrations first.
    pause
    exit /b 1
)

REM Check for essential tables (adjust table names as needed)
mysql -u root -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'kdobrota' AND table_name IN ('migrations', 'users', 'categories', 'products')" > temp.txt
set /p TABLE_COUNT=<temp.txt
del temp.txt

if %TABLE_COUNT% LSS 4 (
    echo Error: Required tables are missing in the database!
    echo Please ensure all migrations have been run.
    pause
    exit /b 1
)

echo Database check passed successfully!

echo Installing Composer dependencies...
call composer install

echo Checking .env file...
if not exist .env (
    echo Creating .env file from example...
    copy .env.example .env
)

echo Checking application key...
php artisan key:status > nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo Generating application key...
    php artisan key:generate
)

echo Checking storage link...
if not exist "public\storage" (
    echo Creating storage link...
    php artisan storage:link
)

echo Installing NPM dependencies...
call npm install

echo Building assets...
call npm run build

echo Starting PHP server on localhost:8000...
echo You can now access the application at http://localhost:8000
php artisan serve 
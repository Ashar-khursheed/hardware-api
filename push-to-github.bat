@echo off
echo ========================================
echo GitHub Repository Setup
echo ========================================
echo.
echo This script will help you push your code to GitHub.
echo.
echo IMPORTANT: Before running this script, you must:
echo 1. Create a repository on GitHub at https://github.com/new
echo 2. Repository name: hardware-graphql
echo 3. DO NOT initialize with README, .gitignore, or license
echo.
echo ========================================
echo.

set /p GITHUB_USERNAME="Enter your GitHub username: "
echo.

echo Your repository URL will be:
echo https://github.com/%GITHUB_USERNAME%/hardware-graphql.git
echo.

set /p CONFIRM="Is this correct? (Y/N): "
if /i not "%CONFIRM%"=="Y" (
    echo Cancelled. Please run the script again.
    pause
    exit /b
)

echo.
echo ========================================
echo Step 1: Checking Git status...
echo ========================================
git status

echo.
echo ========================================
echo Step 2: Adding GitHub remote...
echo ========================================
git remote add origin https://github.com/%GITHUB_USERNAME%/hardware-graphql.git

if %errorlevel% neq 0 (
    echo.
    echo Remote already exists. Updating URL...
    git remote set-url origin https://github.com/%GITHUB_USERNAME%/hardware-graphql.git
)

echo.
echo ========================================
echo Step 3: Verifying remote...
echo ========================================
git remote -v

echo.
echo ========================================
echo Step 4: Pushing to GitHub...
echo ========================================
git push -u origin main

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo SUCCESS! Your code is now on GitHub!
    echo ========================================
    echo.
    echo Repository URL:
    echo https://github.com/%GITHUB_USERNAME%/hardware-graphql
    echo.
    echo You can view your repository at the URL above.
    echo.
) else (
    echo.
    echo ========================================
    echo ERROR: Push failed!
    echo ========================================
    echo.
    echo This might be because:
    echo 1. The repository doesn't exist on GitHub yet
    echo 2. You need to authenticate with GitHub
    echo 3. You don't have permission to push
    echo.
    echo Please check the error message above and try again.
    echo.
)

pause

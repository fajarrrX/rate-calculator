@echo off
echo =================================================
echo Bitegarden SonarCloud Report Generator
echo =================================================

:: Check if Java is installed
java -version >nul 2>&1
if errorlevel 1 (
    echo ❌ Java is not installed or not in PATH
    echo Please install Java 11 or higher from https://adoptium.net/
    pause
    exit /b 1
)

:: Configuration
set ORGANIZATION=asdordigital
set PROJECT_KEY=fajarrrX_rate-calculator
set JAR_FILE=bitegarden-sonarcloud-report-1.8.1.jar
set OUTPUT_FILE=rate-calculator-sonarcloud-report.pdf

:: Check if SONAR_TOKEN is set
if "%SONAR_TOKEN%"=="" (
    echo ❌ SONAR_TOKEN environment variable is not set
    echo Please set your SonarCloud token:
    echo   set SONAR_TOKEN=your_token_here
    echo You can get your token from: https://sonarcloud.io/account/security/
    pause
    exit /b 1
)

:: Check if JAR file exists, if not try to download
if not exist "%JAR_FILE%" (
    echo 📁 JAR file not found. Downloading from Google Drive...
    powershell -Command "Invoke-WebRequest -Uri 'https://drive.usercontent.google.com/download?id=1nDR-9dDqr3WwDHBYvgOZQycmS5UDylYS&export=download&confirm=t' -OutFile '%JAR_FILE%' -UseBasicParsing"
    
    if not exist "%JAR_FILE%" (
        echo ❌ Download failed
        echo Please download manually from your Google Drive
        pause
        exit /b 1
    ) else (
        echo ✅ Downloaded successfully
    )
)

echo 🚀 Generating SonarCloud PDF Report...
echo 📊 Organization: %ORGANIZATION%
echo 📁 Project: %PROJECT_KEY%
echo 📄 Output: %OUTPUT_FILE%
echo.

:: Generate the report
java -Dsonar.token=%SONAR_TOKEN% ^
    -Dsonar.organizationKey=%ORGANIZATION% ^
    -Dsonar.projectKey=%PROJECT_KEY% ^
    -Dreport.type=0 ^
    -Doutput=%OUTPUT_FILE% ^
    -jar "%JAR_FILE%"

if exist "%OUTPUT_FILE%" (
    echo ✅ Report generated successfully: %OUTPUT_FILE%
    echo 📂 Opening report...
    start "" "%OUTPUT_FILE%"
) else (
    echo ❌ Report generation failed
    pause
)

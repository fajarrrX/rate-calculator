# Bitegarden SonarCloud Report Generator
# This script generates a PDF report from your SonarCloud project

# Check if Java is installed
if (!(Get-Command java -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Java is not installed or not in PATH" -ForegroundColor Red
    Write-Host "Please install Java 11 or higher from https://adoptium.net/" -ForegroundColor Yellow
    exit 1
}

# Check Java version
$javaVersion = java -version 2>&1 | Select-String "version" | ForEach-Object { $_.ToString() }
Write-Host "☕ Java version: $javaVersion" -ForegroundColor Green

# Configuration
$SONAR_TOKEN = $env:SONAR_TOKEN
$ORGANIZATION = "asdordigital"
$PROJECT_KEY = "fajarrrX_rate-calculator"
$JAR_FILE = "bitegarden-sonarcloud-report-1.8.1.jar"
$OUTPUT_FILE = "rate-calculator-sonarcloud-report.pdf"
$GDRIVE_URL = "https://drive.usercontent.google.com/download?id=1nDR-9dDqr3WwDHBYvgOZQycmS5UDylYS&export=download&confirm=t"

# Check if SONAR_TOKEN is set
if (-not $SONAR_TOKEN) {
    Write-Host "❌ SONAR_TOKEN environment variable is not set" -ForegroundColor Red
    Write-Host "Please set your SonarCloud token:" -ForegroundColor Yellow
    Write-Host "  `$env:SONAR_TOKEN = 'your_token_here'" -ForegroundColor Cyan
    Write-Host "You can get your token from: https://sonarcloud.io/account/security/" -ForegroundColor Yellow
    exit 1
}

# Check if JAR file exists, if not download it
if (!(Test-Path $JAR_FILE)) {
    Write-Host "📁 JAR file not found. Downloading from Google Drive..." -ForegroundColor Yellow
    
    try {
        Invoke-WebRequest -Uri $GDRIVE_URL -OutFile $JAR_FILE -UseBasicParsing
        $fileSize = (Get-Item $JAR_FILE).Length
        Write-Host "✅ Downloaded successfully (Size: $fileSize bytes)" -ForegroundColor Green
        
        if ($fileSize -lt 1000000) {
            Write-Host "❌ File seems too small, might be an error page" -ForegroundColor Red
            Remove-Item $JAR_FILE -ErrorAction SilentlyContinue
            exit 1
        }
    }
    catch {
        Write-Host "❌ Download failed: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host "Please download manually from your Google Drive" -ForegroundColor Yellow
        exit 1
    }
}

Write-Host "🚀 Generating SonarCloud PDF Report..." -ForegroundColor Blue
Write-Host "📊 Organization: $ORGANIZATION" -ForegroundColor Cyan
Write-Host "📁 Project: $PROJECT_KEY" -ForegroundColor Cyan
Write-Host "📄 Output: $OUTPUT_FILE" -ForegroundColor Cyan

# Generate the report
try {
    java -Dsonar.token=$SONAR_TOKEN `
        -Dsonar.organizationKey=$ORGANIZATION `
        -Dsonar.projectKey=$PROJECT_KEY `
        -Dreport.type=0 `
        -Doutput=$OUTPUT_FILE `
        -jar $JAR_FILE

    if (Test-Path $OUTPUT_FILE) {
        Write-Host "✅ Report generated successfully: $OUTPUT_FILE" -ForegroundColor Green
        Write-Host "📂 Opening report..." -ForegroundColor Blue
        Start-Process $OUTPUT_FILE
    } else {
        Write-Host "❌ Report generation failed" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Error generating report: $($_.Exception.Message)" -ForegroundColor Red
}

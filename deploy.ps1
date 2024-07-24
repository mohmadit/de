param (
    [string]$changedFiles,
    [string]$mysqlCmd = "D:\xampp\mysql\bin\mysql.exe",
    [string]$database = "de",
    [string]$user = "root",
    [string]$password = ""
)

Write-Host "Changed Files: $changedFiles"

# التحقق من وجود ملف MySQL
if (-Not (Test-Path $mysqlCmd)) {
    Write-Host "MySQL command not found at $mysqlCmd"
    exit 1
}

# التحقق من $changedFiles
if (-Not $changedFiles) {
    Write-Host "No files to deploy."
    exit 1
}

# معالجة كل ملف
$changedFiles -split "n" | ForEach-Object {
    $filePath = "D:/xampp/htdocs/aqdevops/upload/$_"
    Write-Host "Deploying $_"
    Write-Host "File path: $filePath"

    # التحقق من وجود مسار الدليل وإنشائه إذا لم يكن موجودًا
    if (-Not (Test-Path -Path "D:/xampp/htdocs/aqdevops/upload")) {
        Write-Host "Deployment path not found! Creating directory."
        New-Item -ItemType Directory -Path "D:/xampp/htdocs/aqdevops/upload"
    }

    # نسخ الملف إلى مسار النشر
    Copy-Item -Path $_ -Destination "D:/xampp/htdocs/aqdevops/upload" -Force
    Write-Host "Copied $_ to $filePath"

    # هروب الأحرف المفردة في أسماء الملفات لتجنب أخطاء SQL
    $escapedFileName = $_.Split('/')[-1] -replace "'", "''"
    $escapedFilePath = $filePath -replace "'", "''"

    $query = "INSERT INTO deployed_files (filename, filepath) VALUES ('$escapedFileName', '$escapedFilePath');"
    Write-Host "Executing query: $query"

    # تنفيذ استعلام SQL
    $arguments = "-u$user -p$password -D$database -e ""$query"""
    Start-Process -FilePath $mysqlCmd -ArgumentList $arguments -NoNewWindow -Wait -RedirectStandardError "error.log"

    if ($?) {
        Write-Host "File $_ deployed and recorded in database."
    } else {
        Write-Host "Error deploying file $_. Please check the database connection and query."
        Write-Host "Error details:"
        Get-Content "error.log"
    }
}

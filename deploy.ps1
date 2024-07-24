param (
    [string]$mysqlCmd = "D:\xampp\mysql\bin\mysql.exe",
    [string]$database = "de",
    [string]$user = "root",
    [string]$password = "",
    [string]$changedFiles  # هذا المعامل سيتم تعيينه من GitHub Actions
)

# التحقق مما إذا كان $changedFiles قد تم توفيره
if (-Not $changedFiles) {
    Write-Host "No files to deploy."
    exit 1
}

# معالجة كل ملف
$changedFiles -split "`n" | ForEach-Object {
    $filePath = "D:/xampp/htdocs/aqdevops/upload/$_"
    Write-Host "Processing file: $filePath"

    # هروب الأحرف المفردة في أسماء الملفات لتجنب أخطاء SQL
    $escapedFileName = $_.Split('/')[-1] -replace "'", "''"
    $escapedFilePath = $filePath -replace "'", "''"

    $query = "INSERT INTO deployed_files (filename, filepath) VALUES ('$escapedFileName', '$escapedFilePath');"

    Write-Host "Executing query: $query"

    # تنفيذ استعلام SQL
    $arguments = "-u$user -p$password -D$database -e `"$query`""
    Start-Process -FilePath $mysqlCmd -ArgumentList $arguments -NoNewWindow -Wait -RedirectStandardError "error.log"

    if ($?) {
        Write-Host "File $_ deployed and recorded in database."
    } else {
        Write-Host "Error deploying file $_. Please check the database connection and query."
        Write-Host "Error details:"
        Get-Content "error.log"
    }
}

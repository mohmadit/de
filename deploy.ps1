param (
    [string]$mysqlCmd = "D:\xampp\mysql\bin\mysql.exe",
    [string]$database = "aqdevops",
    [string]$user = "root",
    [string]$password = ""
)

$changedFiles = "fdg/devops_pok_90_156_front.php"  # مثال على متغير يحتوي على اسم ملف واحد

# تحقق من أن المتغير يحتوي على أسماء الملفات الصحيحة
Write-Host "Changed files: $changedFiles"

$changedFiles -split "`n" | ForEach-Object {
    $filePath = "D:/xampp/htdocs/aqdevops/upload/$_"
    Write-Host "Processing file: $filePath"

    # تحقق من أن الملف موجود
    if (-Not (Test-Path -Path $filePath)) {
        Write-Host "File $filePath does not exist."
        continue
    }

    # تأكد من عدم وجود مشاكل في أسماء الملفات عند إدخالها في قاعدة البيانات
    $escapedFileName = $_.Split('/')[-1] -replace "'", "''"
    $escapedFilePath = $filePath -replace "'", "''"

    $query = "INSERT INTO deployed_files (filename, filepath) VALUES ('$escapedFileName', '$escapedFilePath');"
    Write-Host "Executing query: $query"

    # تأكد من أن MySQL موجود وقابل للتنفيذ
    if (Test-Path -Path $mysqlCmd) {
        Start-Process -FilePath $mysqlCmd -ArgumentList "-u$user -p$password -D$database -e `"$query`"" -NoNewWindow -Wait

        if ($?) {
            Write-Host "File $_ deployed and recorded in database."
        } else {
            Write-Host "Error deploying file $_. Please check the database connection and query."
        }
    } else {
        Write-Host "MySQL executable not found at $mysqlCmd."
    }
}

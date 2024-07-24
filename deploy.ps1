param (
    [string]$mysqlCmd = "D:\xampp\mysql\bin\mysql.exe",
    [string]$database = "de",
    [string]$user = "root",
    [string]$password = ""
)

# تأكد من تعيين المتغير بشكل صحيح إذا لم يتم تعيينه
if (-not $changedFiles) {
    Write-Host "No changed files provided."
    exit 1
}

$changedFiles -split "`n" | ForEach-Object {
    $filePath = "D:/xampp/htdocs/aqdevops/upload/$_"
    Write-Host "Processing file: $filePath"

    # Escape single quotes in filenames to prevent SQL errors
    $escapedFileName = $_.Split('/')[-1] -replace "'", "''"
    $escapedFilePath = $filePath -replace "'", "''"

    $query = "INSERT INTO deployed_files (filename, filepath) VALUES ('$escapedFileName', '$escapedFilePath');"

    Write-Host "Executing query: $query"

    # Execute the query and handle potential errors
    Start-Process -FilePath $mysqlCmd -ArgumentList "-u$user -p$password -D$database -e `"$query`"" -NoNewWindow -Wait

    if ($?) {
        Write-Host "File $_ deployed and recorded in database."
    } else {
        Write-Host "Error deploying file $_. Please check the database connection and query."
    }
}

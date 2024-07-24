param (
    [string]$mysqlCmd = "D:\xampp\mysql\bin\mysql.exe",
    [string]$database = "aqdevops",
    [string]$user = "root",
    [string]$password = ""
)

$changedFiles -split "`n" | ForEach-Object {
    $filePath = "D:/xampp/htdocs/aqdevops/upload/$_"
    Write-Host "Processing file: $filePath"

    # Escape single quotes in filenames to prevent SQL errors
    $escapedFileName = $_.Split('/')[-1] -replace "'", "''"
    $escapedFilePath = $filePath -replace "'", "''"

    $query = "INSERT INTO deployed_files (filename, filepath) VALUES ('$escapedFileName', '$escapedFilePath');"

    Write-Host "Executing query: $query"

    Start-Process -FilePath $mysqlCmd -ArgumentList "-u$user -p$password -D$database -e `"$query`"" -NoNewWindow -Wait

    if ($?) {
        Write-Host "File $_ deployed and recorded in database."
    } else {
        Write-Host "Error deploying file $_. Please check the database connection and query."
    }
}

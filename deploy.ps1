# إعداد متغيرات المشروع وقاعدة البيانات
$projectPath = "D:/xampp/htdocs/aqdevops"
$deployPath = "$projectPath/upload"
$dbUser = "root"
$dbPassword = ""
$dbName = "de"

# نسخ الملفات إلى مجلد النشر
Write-Host "Copying project files to deployment path..."
Copy-Item -Recurse -Force "$projectPath/*" $deployPath

# الحصول على قائمة الملفات المنشورة
$files = Get-ChildItem -Path $deployPath

# إعداد استعلام إدخال بيانات الملفات
$insertQueryBase = "INSERT INTO deployed_files (filename, filepath) VALUES ('{0}', '{1}');"

foreach ($file in $files) {
    if ($file.PSIsContainer -eq $false) {
        $filename = $file.Name
        $filepath = $file.FullName -replace '\\', '/'

        # صياغة استعلام الإدخال
        $insertQuery = [string]::Format($insertQueryBase, $filename, $filepath)

        # تنفيذ استعلام الإدخال باستخدام MySQL CLI من XAMPP
        $mysqlCmd = "D:\xampp\mysql\bin\mysql.exe"
        $arguments = "-u $dbUser -p$dbPassword $dbName -e `" $insertQuery `""
        
        # تنفيذ استعلام الإدخال
        Start-Process -FilePath $mysqlCmd -ArgumentList $arguments -NoNewWindow -Wait

        if ($LASTEXITCODE -ne 0) {
            Write-Host "Error executing query: $insertQuery"
        }
    }
}

Write-Host "Deployment completed successfully."

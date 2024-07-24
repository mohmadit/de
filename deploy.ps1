# إعداد متغيرات المشروع وقاعدة البيانات
$projectPath = "D:/xampp/htdocs/aqdevops"
$deployPath = "$projectPath/upload"
$dbUser = "root"
$dbPassword = ""
$dbName = "de"
$dbServer = "localhost"

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

        # حفظ الاستعلام في ملف مؤقت
        $queryFile = "$deployPath/temp_query.sql"
        $insertQuery | Out-File -FilePath $queryFile -Encoding utf8

        # قراءة محتوى الملف
        $queryContent = Get-Content -Path $queryFile

        # تحديد مسار mysql.exe ضمن XAMPP
        $mysqlCmd = "D:\xampp\mysql\bin\mysql.exe -u $dbUser -p$dbPassword $dbName"
        
        # تنفيذ استعلام الإدخال باستخدام MySQL CLI
        $process = Start-Process -FilePath $mysqlCmd -ArgumentList "-e `" $queryContent `" -NoNewWindow -Wait

        if ($process.ExitCode -ne 0) {
            Write-Host "Error executing query: $queryContent"
        }
    }
}

Write-Host "Deployment completed successfully."

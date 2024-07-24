# إعداد متغيرات المشروع وقاعدة البيانات
$projectPath = "D:/xampp/htdocs/aqdevops"
$deployPath = "$projectPath/upload"
$dbUser = "root"
$dbPassword = ""
$dbName = "de"
$dbServer = "localhost"

# التحقق من وجود مسار النشر وإنشائه إذا لم يكن موجودًا
if (-Not (Test-Path -Path $deployPath)) {
    Write-Host "Deployment path not found! Creating directory."
    New-Item -ItemType Directory -Path $deployPath
} else {
    Write-Host "Deployment path exists."
}

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
        $queryContent = Get-Content -Path $queryFile -Raw

        # تحديد مسار mysql.exe ضمن XAMPP
        $mysqlCmd = "D:\xampp\mysql\bin\mysql.exe -u $dbUser -p$dbPassword $dbName"

        # تنفيذ استعلام الإدخال باستخدام MySQL CLI
        $process = Start-Process -FilePath $mysqlCmd -ArgumentList "-e $queryContent" -NoNewWindow -Wait -PassThru

        if ($process.ExitCode -ne 0) {
            Write-Host "Error executing query: $queryContent"
        } else {
            Write-Host "Query executed successfully: $insertQuery"
        }
    }
}

Write-Host "Deployment completed successfully."

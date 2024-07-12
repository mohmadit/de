<?php

use PHPUnit\Framework\TestCase;

class DevOpsFileTest extends TestCase
{
    private $filePaths = [];

    protected function setUp(): void
    {
        // مسار الجذر للمستودع على GitHub
        $baseDirectory = __DIR__ . '/../..'; // عدل هذا حسب المسار الفعلي لمستودعك

        // العثور على جميع الملفات devops_*.php في جميع مجلدات المشروع
        $files = glob($baseDirectory . '/**/devops_*.php');

        foreach ($files as $file) {
            $this->filePaths[] = $file;
        }
    }

    public function testFilesAreValidPhp()
    {
        foreach ($this->filePaths as $filePath) {
            $output = [];
            $returnCode = null;
            exec("php -l " . escapeshellarg($filePath), $output, $returnCode);

            $this->assertEquals(0, $returnCode, "PHP file syntax error detected in file: {$filePath}. Output: " . implode("\n", $output));
        }
    }
}

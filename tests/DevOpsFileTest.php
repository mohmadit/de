<?php

use PHPUnit\Framework\TestCase;

class DevOpsFileTest extends TestCase
{
    private $filePaths = [];

    protected function setUp(): void
    {
        // تحديد مسار مجلد المشروع داخل المستودع
        $directory = __DIR__ . '/../'; // تأكد من تعديل هذا المسار بناءً على موقعك الفعلي
        $this->filePaths = glob($directory . '**/devops_*.php');

        if (empty($this->filePaths)) {
            $this->markTestSkipped('No devops_*.php files found for testing.');
        }
    }

    public function testFilesExist()
    {
        $this->assertNotEmpty($this->filePaths, "No files found for testing.");
        foreach ($this->filePaths as $filePath) {
            $this->assertFileExists($filePath, "File does not exist: {$filePath}");
        }
    }

    public function testFilesArePhp()
    {
        foreach ($this->filePaths as $filePath) {
            $this->assertStringEndsWith('.php', $filePath, "File is not a PHP file: {$filePath}");
        }
    }

    public function testFilesHavePhpOpeningTag()
    {
        foreach ($this->filePaths as $filePath) {
            $fileContent = file_get_contents($filePath);
            $this->assertStringStartsWith('<?php', $fileContent, "File does not start with <?php tag: {$filePath}");
        }
    }

    public function testFilesContainBasicPhpFunctions()
    {
        $essentialFunctions = [
            'echo',
            'print',
            'include',
            'require',
            'function',
            'class',
            'return'
        ];

        foreach ($this->filePaths as $filePath) {
            $fileContent = file_get_contents($filePath);
            foreach ($essentialFunctions as $function) {
                $this->assertStringContainsString($function, $fileContent, "File does not contain the {$function} keyword: {$filePath}");
            }
        }
    }

    public function testFilesAreValidPhp()
    {
        foreach ($this->filePaths as $filePath) {
            $output = null;
            $returnCode = null;
            exec("php -l {$filePath}", $output, $returnCode);
            $this->assertEquals(0, $returnCode, "PHP file syntax error detected: {$filePath}");
        }
    }
}

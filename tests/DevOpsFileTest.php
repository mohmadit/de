<?php
use PHPUnit\Framework\TestCase;

class DevOpsFileTest extends TestCase
{
    private $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = __DIR__ . '/../de/'; // ضع هنا المسار الصحيح لمجلد المشروع
    }

    public function testFilesExist()
    {
        $files = glob($this->projectDir . '/devops_*.php');
        foreach ($files as $file) {
            $this->assertFileExists($file, "File $file does not exist.");
        }
    }

    public function testFilesHavePhpOpeningTag()
    {
        $files = glob($this->projectDir . '/devops_*.php');
        foreach ($files as $file) {
            $contents = file_get_contents($file);
            $this->assertStringStartsWith('<?php', $contents, "File $file does not have PHP opening tag.");
        }
    }

    public function testFilesContainBasicPhpFunctions()
    {
        $files = glob($this->projectDir . '/devops_*.php');
        foreach ($files as $file) {
            $contents = file_get_contents($file);
            $this->assertStringContainsString('function ', $contents, "File $file does not contain any functions.");
        }
    }
}

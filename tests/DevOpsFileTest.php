<?php

use PHPUnit\Framework\TestCase;

class DevOpsFileTest extends TestCase
{
    private $projectDirectories = [
        'cris', // أضف أسماء مجلدات مشاريعك هنا
        // 'project2',
        // 'project3'
    ];

    private $filePattern = '/devops_*.php';

    protected function setUp(): void
    {
        $this->baseDir = dirname(__DIR__); // جذر المشروع
    }

    public function testFilesExist()
    {
        foreach ($this->projectDirectories as $project) {
            $files = glob($this->baseDir . '/' . $project . $this->filePattern);
            $this->assertNotEmpty($files, "No devops files found in project directory: $project");
        }
    }

    public function testFilesHavePhpOpeningTag()
    {
        foreach ($this->projectDirectories as $project) {
            $files = glob($this->baseDir . '/' . $project . $this->filePattern);
            foreach ($files as $file) {
                $content = file_get_contents($file);
                $this->assertStringContainsString('<?php', $content, "File $file does not contain PHP opening tag");
            }
        }
    }
}

<?php

use PHPUnit\Framework\TestCase;

class DevOpsFileTest extends TestCase
{
    private $targetFile;

    protected function setUp(): void
    {
        $this->targetFile = getenv('TARGET_FILE');
        if (!$this->targetFile) {
            $this->markTestSkipped('No target file specified');
        }
    }

    public function testFileExists()
    {
        $this->assertFileExists($this->targetFile, "File {$this->targetFile} does not exist");
    }

    public function testFileHasPhpOpeningTag()
    {
        $content = file_get_contents($this->targetFile);
        $this->assertStringContainsString('<?php', $content, "File {$this->targetFile} does not contain PHP opening tag");
    }

    public function testFileContainsOnlyPhpCode()
    {
        $result = shell_exec("php -l {$this->targetFile}");
        $this->assertStringContainsString('No syntax errors detected', $result, "File {$this->targetFile} contains syntax errors");
    }
}

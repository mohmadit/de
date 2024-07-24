<?php
use PHPUnit\Framework\TestCase;

class DevopsFileTest extends TestCase
{
    protected $file;

    protected function setUp(): void
    {
        // تعيين المسار إلى أحدث ملف devops
        $this->file = getenv('LATEST_FILE');
    }

    public function testFileExists()
    {
        $this->assertFileExists($this->file, 'The file does not exist.');
    }

    public function testFileContainsBasicTag()
    {
        $content = file_get_contents($this->file);
        $this->assertStringContainsString('<tag>', $content, 'The file does not contain the expected basic tag.');
    }
}

<?php

use PHPUnit\Framework\TestCase;

class SimpleFileTest extends TestCase
{
    public function testFileExists()
    {
        $file = getenv('LATEST_FILE');
        $this->assertFileExists($file, "The file $file does not exist.");
    }

    public function testFileContainsTags()
    {
        $file = getenv('LATEST_FILE');
        $content = file_get_contents($file);
        $this->assertStringContainsString('<?php', $content, "The file $file does not contain the PHP opening tag.");
    }
}

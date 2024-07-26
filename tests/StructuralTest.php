<?php

use PHPUnit\Framework\TestCase;

class StructuralTest extends TestCase
{
    public function testClassStructure()
    {
        $filePath = __DIR__ . '/../src/SomeClass.php';
        $this->assertFileExists($filePath, "File $filePath does not exist.");

        $content = file_get_contents($filePath);
        $this->assertStringContainsString('class SomeClass', $content, 'Class SomeClass not found.');
        $this->assertStringContainsString('function someMethod', $content, 'Method someMethod not found.');
    }
}

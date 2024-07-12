<?php

use PHPUnit\Framework\TestCase;

class DevOpsFileTest extends TestCase
{
    private $filePaths = [];

    protected function setUp(): void
    {
        // Base directory for the GitHub repository
        $baseDirectory = __DIR__ . '/../..'; // Adjust this to point to the root of your repository

        // Find all devops_*.php files in all project folders
        $files = glob($baseDirectory . '/**/devops_*.php');

        foreach ($files as $file) {
            $this->filePaths[] = $file;
        }
    }

    public function testFilesExist()
    {
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
        foreach ($this->filePaths as $filePath) {
            $fileContent = file_get_contents($filePath);

            // List of essential PHP functions
            $essentialFunctions = [
                'echo',
                'print',
                'include',
                'require',
                'function',
                'class',
                'return'
            ];

            $containsFunction = false;
            foreach ($essentialFunctions as $function) {
                if (strpos($fileContent, $function) !== false) {
                    $containsFunction = true;
                    break;
                }
            }
            $this->assertTrue($containsFunction, "File does not contain any of the essential PHP functions: {$filePath}");
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

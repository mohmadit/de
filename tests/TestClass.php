// tests/TestClass.php
<?php
use PHPUnit\Framework\TestCase;

class TestClass extends TestCase {
   

    public function testExample() {
        $this->assertTrue(true);
    }

    public function testAnotherExample() {
        $expected = 42;
        $actual = 42;
        $this->assertEquals($expected, $actual, "Expected value does not match the actual value.");
    }

    // أضف المزيد من الاختبارات هنا
}
?>

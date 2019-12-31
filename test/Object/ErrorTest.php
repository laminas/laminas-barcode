<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;

/**
 * @category   Laminas
 * @package    Laminas_Barcode
 * @subpackage UnitTests
 * @group      Laminas_Barcode
 */
class ErrorTest extends TestCommon
{
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Error($options);
    }

    public function testType()
    {
        $this->assertSame('error', $this->object->getType());
    }

    public function testSetText()
    {
        $this->object->setText('This is an error text');
        $this->assertSame('This is an error text', $this->object->getRawText());
        $this->assertSame('This is an error text', $this->object->getText());
        $this->assertSame('This is an error text', $this->object->getTextToDisplay());
    }

    public function testCheckGoodParams()
    {
        $this->object->setText('This is an error text');
        $this->assertTrue($this->object->checkParams());
    }

    public function testGetDefaultHeight()
    {
        $this->assertEquals(40, $this->object->getHeight());
    }

    public function testGetDefaultWidth()
    {
        $this->assertEquals(400, $this->object->getWidth());
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('This is an error text');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Error_errortext_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }
}

<?php

declare(strict_types=1);

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;
use Traversable;

/**
 * @group      Laminas_Barcode
 */
class Code128Test extends AbstractTest
{
    /**
     * @param array|Traversable $options
     * @return Barcode\Object\Code128
     */
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Code128($options);
    }

    public function testType()
    {
        $this->assertSame('code128', $this->object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame(33, $this->object->getChecksum('BarCode 1'));
        $this->assertSame(47, $this->object->getChecksum('CODE-128'));
        $this->assertSame(32, $this->object->getChecksum('FRAMEWORK-ZEND-COM'));
    }

    public function testKnownBarcodeConversion()
    {
        $barcode = new TestAsset\Code128Test();
        $this->assertSame([104, 13, 17, 18, 19], $barcode->convertToBarcodeChars(-123));
        $this->assertSame([104, 40, 41, 99, 34, 56, 78], $barcode->convertToBarcodeChars('HI345678'));
    }

    public function testSetText()
    {
        $this->object->setText('0123456789');
        $this->assertSame('0123456789', $this->object->getRawText());
        $this->assertSame('0123456789', $this->object->getText());
        $this->assertSame('0123456789', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 0123456789 ');
        $this->assertSame(' 0123456789 ', $this->object->getRawText());
        $this->assertSame(' 0123456789 ', $this->object->getText());
        $this->assertSame(' 0123456789 ', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksum()
    {
        $this->object->setText('0123456789');
        $this->object->setWithChecksum(true);
        $this->assertSame('0123456789', $this->object->getRawText());
        $this->assertSame('0123456789', $this->object->getText());
        $this->assertSame('0123456789', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumDisplayed()
    {
        $this->object->setText('0123456789');
        $this->object->setWithChecksum(true);
        $this->object->setWithChecksumInText(true);
        $this->assertSame('0123456789', $this->object->getRawText());
        $this->assertSame('0123456789', $this->object->getText());
        $this->assertSame('0123456789', $this->object->getTextToDisplay());
    }

    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('CODE-128');
        $this->assertEquals(143, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(123, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('HI345678');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Code128_HI345678_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithStretchText()
    {
        $this->object->setText('HI345678');
        $this->object->setStretchText(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code128_HI345678_stretchtext_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('HI345678');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code128_HI345678_border_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('HI345678');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code128_HI345678_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithStretchTextWithOrientation()
    {
        $this->object->setText('HI345678');
        $this->object->setOrientation(60);
        $this->object->setStretchText(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code128_HI345678_stretchtext_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('HI345678');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code128_HI345678_border_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }
}

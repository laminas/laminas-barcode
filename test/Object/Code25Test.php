<?php

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;
use Laminas\Barcode\Object\Exception\ExceptionInterface;
use Traversable;

/**
 * @group      Laminas_Barcode
 */
class Code25Test extends AbstractTest
{
    /**
     * @param array|Traversable $options
     * @return Barcode\Object\Code25
     */
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Code25($options);
    }

    public function testType()
    {
        $this->assertSame('code25', $this->object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame(5, $this->object->getChecksum('0123456789'));
        $this->assertSame(0, $this->object->getChecksum('13'));
    }

    public function testSetText()
    {
        $this->object->setText('0123456789');
        $this->assertSame('0123456789', $this->object->getRawText());
        $this->assertSame('0123456789', $this->object->getText());
        $this->assertSame('0123456789', $this->object->getTextToDisplay());
    }

    public function testSetTextWithOddNumberOfCharacters()
    {
        $this->object->setText('123456789');
        $this->assertSame('123456789', $this->object->getRawText());
        $this->assertSame('123456789', $this->object->getText());
        $this->assertSame('123456789', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 0123456789 ');
        $this->assertSame('0123456789', $this->object->getRawText());
        $this->assertSame('0123456789', $this->object->getText());
        $this->assertSame('0123456789', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksum()
    {
        $this->object->setText('0123456789');
        $this->object->setWithChecksum(true);
        $this->assertSame('0123456789', $this->object->getRawText());
        $this->assertSame('01234567895', $this->object->getText());
        $this->assertSame('0123456789', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumDisplayed()
    {
        $this->object->setText('0123456789');
        $this->object->setWithChecksum(true);
        $this->object->setWithChecksumInText(true);
        $this->assertSame('0123456789', $this->object->getRawText());
        $this->assertSame('01234567895', $this->object->getText());
        $this->assertSame('01234567895', $this->object->getTextToDisplay());
    }

    public function testBadTextAlwaysAllowed()
    {
        $this->object->setText('a');
        $this->assertSame('a', $this->object->getText());
    }

    public function testBadTextDetectedIfChecksumWished()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setText('a');
        $this->object->setWithChecksum(true);
        $this->object->getText();
    }

    public function testCheckGoodParams()
    {
        $this->object->setText('0123456789');
        $this->assertTrue($this->object->checkParams());
    }

    public function testCheckParamsWithLowRatio()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setText('0123456789');
        $this->object->setBarThinWidth(21);
        $this->object->setBarThickWidth(40);
        $this->object->checkParams();
    }

    public function testCheckParamsWithHighRatio()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setText('0123456789');
        $this->object->setBarThinWidth(20);
        $this->object->setBarThickWidth(61);
        $this->object->checkParams();
    }

    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('0123456789');
        $this->assertEquals(180, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(160, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('0123456789');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Code25_0123456789_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithStretchText()
    {
        $this->object->setText('0123456789');
        $this->object->setStretchText(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code25_0123456789_stretchtext_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('0123456789');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code25_0123456789_border_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('0123456789');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code25_0123456789_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithStretchTextWithOrientation()
    {
        $this->object->setText('0123456789');
        $this->object->setOrientation(60);
        $this->object->setStretchText(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code25_0123456789_stretchtext_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('0123456789');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Code25_0123456789_border_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }
}

<?php

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;
use Laminas\Barcode\Object\Exception\ExceptionInterface;
use Traversable;

/**
 * @group      Laminas_Barcode
 */
class PlanetTest extends AbstractTest
{
    /**
     * @param array|Traversable $options
     * @return Barcode\Object\Planet
     */
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Planet($options);
    }

    public function testType()
    {
        $this->assertSame('planet', $this->object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame(5, $this->object->getChecksum('00000012345'));
        $this->assertSame(0, $this->object->getChecksum('00000001234'));
    }

    public function testSetText()
    {
        $this->object->setText('00000012345');
        $this->assertSame('00000012345', $this->object->getRawText());
        $this->assertSame('000000123455', $this->object->getText());
        $this->assertSame('000000123455', $this->object->getTextToDisplay());
    }

    public function testSetTextWithoutGoodNumberOfCharacters()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setText('1234');
        $this->object->getText();
    }

    public function testSetTextWithoutChecksumHasNoEffect()
    {
        $this->object->setText('00000012345');
        $this->object->setWithChecksum(false);
        $this->assertSame('00000012345', $this->object->getRawText());
        $this->assertSame('000000123455', $this->object->getText());
        $this->assertSame('000000123455', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 00000012345 ');
        $this->assertSame('00000012345', $this->object->getRawText());
        $this->assertSame('000000123455', $this->object->getText());
        $this->assertSame('000000123455', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumNotDisplayed()
    {
        $this->object->setText('00000012345');
        $this->object->setWithChecksumInText(false);
        $this->assertSame('00000012345', $this->object->getRawText());
        $this->assertSame('000000123455', $this->object->getText());
        $this->assertSame('000000123455', $this->object->getTextToDisplay());
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
        $this->object->setText('00000012345');
        $this->assertTrue($this->object->checkParams());
    }

    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('00000012345');
        $this->assertEquals(286, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(246, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('00000012345');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Planet_012345_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('00000012345');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Planet_012345_border_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('00000012345');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Planet_012345_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('00000012345');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Planet_012345_border_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testGetDefaultHeight()
    {
        // Checksum activated => text needed
        $this->object->setText('00000012345');
        $this->assertEquals(20, $this->object->getHeight(true));
    }
}

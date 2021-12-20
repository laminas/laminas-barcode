<?php

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;
use Laminas\Barcode\Object\Exception\BarcodeValidationException;
use Laminas\Barcode\Object\Exception\ExceptionInterface;
use Traversable;

/**
 * @group      Laminas_Barcode
 */
class RoyalmailTest extends AbstractTest
{
    /**
     * @param array|Traversable $options
     * @return Barcode\Object\Royalmail
     */
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Royalmail($options);
    }

    public function testType()
    {
        $this->assertSame('royalmail', $this->object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame('W', $this->object->getChecksum('012345'));
        $this->assertSame('H', $this->object->getChecksum('06CIOU'));
        $this->assertSame('K', $this->object->getChecksum('SN34RD1A'));
    }

    public function testSetText()
    {
        $this->object->setText('012345');
        $this->assertSame('012345', $this->object->getRawText());
        $this->assertSame('012345W', $this->object->getText());
        $this->assertSame('012345W', $this->object->getTextToDisplay());
    }

    public function testSetTextWithoutChecksumHasNoEffect()
    {
        $this->object->setText('012345');
        $this->object->setWithChecksum(false);
        $this->assertSame('012345', $this->object->getRawText());
        $this->assertSame('012345W', $this->object->getText());
        $this->assertSame('012345W', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 012345 ');
        $this->assertSame('012345', $this->object->getRawText());
        $this->assertSame('012345W', $this->object->getText());
        $this->assertSame('012345W', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumNotDisplayed()
    {
        $this->object->setText('012345');
        $this->object->setWithChecksumInText(false);
        $this->assertSame('012345', $this->object->getRawText());
        $this->assertSame('012345W', $this->object->getText());
        $this->assertSame('012345W', $this->object->getTextToDisplay());
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
        $this->object->setText('012345');
        $this->assertTrue($this->object->checkParams());
    }

    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('012345');
        $this->assertEquals(158, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(118, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('012345');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Royalmail_012345_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('012345');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Royalmail_012345_border_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('012345');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Royalmail_012345_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('012345');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Royalmail_012345_border_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testGetDefaultHeight()
    {
        // Checksum activated => text needed
        $this->object->setText('012345');
        $this->assertEquals(20, $this->object->getHeight(true));
    }

    public function testChecksumIsNotProvided()
    {
        $this->object->setText('123456');
        self::assertSame('1234562', $this->object->getTextToDisplay());
    }

    public function testProvidedChecksum()
    {
        $this->object->setProvidedChecksum(true);
        $this->object->setText('1234562');
        self::assertSame('1234562', $this->object->getTextToDisplay());
    }

    public function testProvidedChecksumInvalid()
    {
        $this->object->setProvidedChecksum(true);
        $this->object->setText('1234567');

        $this->expectException(BarcodeValidationException::class);
        $this->expectExceptionMessage('The input failed checksum validation');
        $this->object->getTextToDisplay();
    }
}

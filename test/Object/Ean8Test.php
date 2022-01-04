<?php

declare(strict_types=1);

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode\Object\Ean8;
use Laminas\Barcode\Object\Exception\BarcodeValidationException;
use Laminas\Barcode\Object\Exception\ExceptionInterface;
use Traversable;

/**
 * @group      Laminas_Barcode
 */
class Ean8Test extends AbstractTest
{
    /**
     * @param array|Traversable $options
     * @return Ean8
     */
    protected function getBarcodeObject($options = null)
    {
        return new Ean8($options);
    }

    public function testType()
    {
        $this->assertSame('ean8', $this->object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame(5, $this->object->getChecksum('2345678'));
    }

    public function testSetText()
    {
        $this->object->setText('0123456');
        $this->assertSame('0123456', $this->object->getRawText());
        $this->assertSame('01234565', $this->object->getText());
        $this->assertSame('01234565', $this->object->getTextToDisplay());
    }

    public function testSetTextWithout8Characters()
    {
        $this->object->setText('12345');
        $this->assertSame('12345', $this->object->getRawText());
        $this->assertSame('00123457', $this->object->getText());
        $this->assertSame('00123457', $this->object->getTextToDisplay());
    }

    public function testSetTextWithoutChecksumHasNoEffect()
    {
        $this->object->setText('0123456');
        $this->object->setWithChecksum(false);
        $this->assertSame('0123456', $this->object->getRawText());
        $this->assertSame('01234565', $this->object->getText());
        $this->assertSame('01234565', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 123456 ');
        $this->assertSame('123456', $this->object->getRawText());
        $this->assertSame('01234565', $this->object->getText());
        $this->assertSame('01234565', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumNotDisplayed()
    {
        $this->object->setText('123456');
        $this->object->setWithChecksumInText(false);
        $this->assertSame('123456', $this->object->getRawText());
        $this->assertSame('01234565', $this->object->getText());
        $this->assertSame('01234565', $this->object->getTextToDisplay());
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
        $this->object->setText('123456');
        $this->assertTrue($this->object->checkParams());
    }

    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('123456');
        $this->assertEquals(87, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(67, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('123456');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Ean8_123456_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('123456');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Ean8_123456_border_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('123456');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Ean8_123456_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('123456');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Ean8_123456_border_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testGetDefaultHeight()
    {
        // Checksum activated => text needed
        $this->object->setText('123456');
        $this->assertEquals(62, $this->object->getHeight(true));
    }

    public function testChecksumIsNotProvided()
    {
        $this->object->setText('1234567');
        self::assertSame('12345670', $this->object->getTextToDisplay());
    }

    public function testProvidedChecksum()
    {
        $this->object->setProvidedChecksum(true);
        $this->object->setText('12345670');
        self::assertSame('12345670', $this->object->getTextToDisplay());
    }

    public function testProvidedChecksumInvalid()
    {
        $this->object->setProvidedChecksum(true);
        $this->object->setText('12345678');

        $this->expectException(BarcodeValidationException::class);
        $this->expectExceptionMessage('The input failed checksum validation');
        $this->object->getTextToDisplay();
    }
}

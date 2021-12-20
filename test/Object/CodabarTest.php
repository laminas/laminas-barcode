<?php

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;
use Traversable;

/**
 * @group      Laminas_Barcode
 */
class CodabarTest extends AbstractTest
{
    /**
     * @param array|Traversable $options
     * @return Barcode\Object\Codabar
     */
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Codabar($options);
    }

    public function testType()
    {
        $this->assertSame('codabar', $this->object->getType());
    }

    public function testSetText()
    {
        $this->object->setText('A0123456789B');
        $this->assertSame('A0123456789B', $this->object->getRawText());
        $this->assertSame('A0123456789B', $this->object->getText());
        $this->assertSame('A0123456789B', $this->object->getTextToDisplay());
    }

    public function testSetTextWithOddNumberOfCharacters()
    {
        $this->object->setText('A123456789B');
        $this->assertSame('A123456789B', $this->object->getRawText());
        $this->assertSame('A123456789B', $this->object->getText());
        $this->assertSame('A123456789B', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' A0123456789B ');
        $this->assertSame('A0123456789B', $this->object->getRawText());
        $this->assertSame('A0123456789B', $this->object->getText());
        $this->assertSame('A0123456789B', $this->object->getTextToDisplay());
    }

    public function testBadTextAlwaysAllowed()
    {
        $this->object->setText('a');
        $this->assertSame('a', $this->object->getText());
    }

    public function testCheckGoodParams()
    {
        $this->object->setText('A0123456789B');
        $this->assertTrue($this->object->checkParams());
    }

    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('A0123456789B');
        $this->assertEquals(141, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(121, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('A0123456789B');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Codabar_A0123456789B_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithStretchText()
    {
        $this->object->setText('A0123456789B');
        $this->object->setStretchText(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Codabar_A0123456789B_stretchtext_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('A0123456789B');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Codabar_A0123456789B_border_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('A0123456789B');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Codabar_A0123456789B_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithStretchTextWithOrientation()
    {
        $this->object->setText('A0123456789B');
        $this->object->setOrientation(60);
        $this->object->setStretchText(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Codabar_A0123456789B_stretchtext_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('A0123456789B');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Codabar_A0123456789B_border_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }
}

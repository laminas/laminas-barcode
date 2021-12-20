<?php

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;
use Laminas\Barcode\Object\AbstractObject;
use Laminas\Barcode\Object\Exception\ExceptionInterface;
use Laminas\Config;
use PHPUnit\Framework\TestCase;
use Traversable;

use function extension_loaded;

abstract class AbstractTest extends TestCase
{
    /** @var AbstractObject */
    protected $object;

    /**
     * @param array|Traversable $options
     * @return Barcode\Object\AbstractObject
     */
    abstract protected function getBarcodeObject($options = null);

    /**
     * @param string $fileName
     * @return array
     */
    protected function loadInstructionsFile($fileName)
    {
        return include_once __DIR__ . "/TestAsset/$fileName.php";
    }

    public function setUp(): void
    {
        $this->object = $this->getBarcodeObject();
    }

    public function tearDown(): void
    {
        $this->object = null;
    }

    public function testStaticFontAsString()
    {
        Barcode\Barcode::setBarcodeFont('my_static_font.ttf');
        $this->assertEquals('', $this->object->getFont());
        $object = $this->getBarcodeObject();
        $this->assertEquals('my_static_font.ttf', $object->getFont());
        Barcode\Barcode::setBarcodeFont(null);
    }

    public function testStaticFontAsNumber()
    {
        for ($i = 1; $i < 5; $i++) {
            Barcode\Barcode::setBarcodeFont($i);
            $this->assertEquals('', $this->object->getFont());
            $object = $this->getBarcodeObject();
            $this->assertEquals($i, $object->getFont());
            Barcode\Barcode::setBarcodeFont('');
        }
    }

    public function testConstructorWithArray()
    {
        $object = $this->getBarcodeObject(
            [
                'barHeight'      => 150,
                'unkownProperty' => 'aValue',
            ]
        );
        $this->assertEquals(150, $object->getBarHeight());
    }

    public function testConstructorWithLaminasConfig()
    {
        $config = new Config\Config(
            [
                'barHeight'      => 150,
                'unkownProperty' => 'aValue',
            ]
        );
        $object = $this->getBarcodeObject($config);
        $this->assertEquals(150, $object->getBarHeight());
    }

    public function testSetOptions()
    {
        $this->object->setOptions(
            [
                'barHeight'      => 150,
                'unkownProperty' => 'aValue',
            ]
        );
        $this->assertEquals(150, $this->object->getBarHeight());
    }

    public function testBarcodeNamespace()
    {
        $this->object->setBarcodeNamespace('My_Namespace');
        $this->assertEquals('My_Namespace', $this->object->getBarcodeNamespace());
    }

    public function testBarHeight()
    {
        $this->object->setBarHeight(1);
        $this->assertSame(1, $this->object->getBarHeight());
        $this->object->setBarHeight(true);
        $this->assertSame(1, $this->object->getBarHeight());
        $this->object->setBarHeight('200a');
        $this->assertSame(200, $this->object->getBarHeight());
    }

    public function testNegativeBarHeight()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setBarHeight(- 1);
    }

    public function testBarThinWidth()
    {
        $this->object->setBarThinWidth(1);
        $this->assertSame(1, $this->object->getBarThinWidth());
        $this->object->setBarThinWidth(true);
        $this->assertSame(1, $this->object->getBarThinWidth());
        $this->object->setBarThinWidth('200a');
        $this->assertSame(200, $this->object->getBarThinWidth());
    }

    public function testNegativeBarThinWidth()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setBarThinWidth(- 1);
    }

    public function testBarThickWidth()
    {
        $this->object->setBarThickWidth(1);
        $this->assertSame(1, $this->object->getBarThickWidth());
        $this->object->setBarThickWidth(true);
        $this->assertSame(1, $this->object->getBarThickWidth());
        $this->object->setBarThickWidth('200a');
        $this->assertSame(200, $this->object->getBarThickWidth());
    }

    public function testNegativeBarThickWidth()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setBarThickWidth(- 1);
    }

    public function testFactor()
    {
        $this->object->setFactor(1);
        $this->assertSame(1.0, $this->object->getFactor());
        $this->object->setFactor(1.25);
        $this->assertSame(1.25, $this->object->getFactor());
        $this->object->setFactor(true);
        $this->assertSame(1.0, $this->object->getFactor());
        $this->object->setFactor('200a');
        $this->assertSame(200.0, $this->object->getFactor());
    }

    public function testNegativeFactor()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setFactor(- 1);
    }

    public function testForeColor()
    {
        $this->object->setForeColor('#333333');
        $this->assertSame(3355443, $this->object->getForeColor());
        $this->object->setForeColor(1000);
        $this->assertSame(1000, $this->object->getForeColor());
    }

    public function testNegativeForeColor()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setForeColor(- 1);
    }

    public function testTooHighForeColor()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setForeColor(16777126);
    }

    public function testBackgroundColor()
    {
        $this->object->setBackgroundColor('#333333');
        $this->assertSame(3355443, $this->object->getBackgroundColor());
        $this->object->setBackgroundColor(1000);
        $this->assertSame(1000, $this->object->getBackgroundColor());
    }

    public function testNegativeBackgroundColor()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setBackgroundColor(- 1);
    }

    public function testTooHighBackgroundColor()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setBackgroundColor(16777126);
    }

    public function testWithBorder()
    {
        $this->object->setWithBorder(1);
        $this->assertSame(true, $this->object->getWithBorder());
        $this->object->setWithBorder(true);
        $this->assertSame(true, $this->object->getWithBorder());
    }

    public function testReverseColor()
    {
        $this->object->setForeColor(11);
        $this->object->setBackgroundColor(111);
        $this->object->setReverseColor();
        $this->assertSame(111, $this->object->getForeColor());
        $this->assertSame(11, $this->object->getBackgroundColor());
    }

    public function testOrientation()
    {
        $this->object->setOrientation(1);
        $this->assertSame(1.0, $this->object->getOrientation());
        $this->object->setOrientation(1.25);
        $this->assertSame(1.25, $this->object->getOrientation());
        $this->object->setOrientation(true);
        $this->assertSame(1.0, $this->object->getOrientation());
        $this->object->setOrientation('200a');
        $this->assertSame(200.0, $this->object->getOrientation());
        $this->object->setOrientation(360);
        $this->assertSame(0.0, $this->object->getOrientation());
    }

    public function testDrawText()
    {
        $this->object->setDrawText(1);
        $this->assertSame(true, $this->object->getDrawText());
        $this->object->setDrawText(true);
        $this->assertSame(true, $this->object->getDrawText());
    }

    public function testStretchText()
    {
        $this->object->setStretchText(1);
        $this->assertSame(true, $this->object->getStretchText());
        $this->object->setStretchText(true);
        $this->assertSame(true, $this->object->getStretchText());
    }

    public function testWithChecksum()
    {
        $this->object->setWithChecksum(1);
        $this->assertSame(true, $this->object->getWithChecksum());
        $this->object->setWithChecksum(true);
        $this->assertSame(true, $this->object->getWithChecksum());
    }

    public function testWithChecksumInText()
    {
        $this->object->setWithChecksumInText(1);
        $this->assertSame(true, $this->object->getWithChecksumInText());
        $this->object->setWithChecksumInText(true);
        $this->assertSame(true, $this->object->getWithChecksumInText());
    }

    public function testProvidedChecksumFalseByDefault()
    {
        self::assertFalse($this->object->getProvidedChecksum());
    }

    public function testProvidedChecksum()
    {
        $this->object->setProvidedChecksum(1);
        self::assertTrue($this->object->getProvidedChecksum());
        $this->object->setProvidedChecksum(true);
        self::assertTrue($this->object->getProvidedChecksum());
    }

    public function testWithoutQuietZones()
    {
        $this->object->setWithQuietZones(0);
        $this->assertSame(false, $this->object->getWithQuietZones());
        $this->object->setWithQuietZones(false);
        $this->assertSame(false, $this->object->getWithQuietZones());
    }

    public function testSetFontAsNumberForGdImage()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped(
                'GD extension is required to run this test'
            );
        }
        $gdFontSize = [8, 13, 13, 16, 15];
        for ($i = 1; $i <= 5; $i++) {
            $this->object->setFont($i);
            $this->assertSame($i, $this->object->getFont());
            $this->assertSame(
                $gdFontSize[$i - 1],
                $this->object->getFontSize()
            );
        }
    }

    public function testSetLowFontAsNumberForGdImage()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setFont(0);
    }

    public function testSetHighFontAsNumberForGdImage()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setFont(6);
    }

    public function testSetFontAsString()
    {
        $this->object->setFont('my_font.ttf');
        $this->assertSame('my_font.ttf', $this->object->getFont());
    }

    public function testSetFontAsBoolean()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setFont(true);
    }

    public function testFontAsNumberWithoutGd()
    {
        if (extension_loaded('gd')) {
            $this->markTestSkipped(
                'GD extension must not be loaded to run this test'
            );
        }
        $this->expectException(ExceptionInterface::class);
        $this->object->setFont(1);
    }

    public function testFontSize()
    {
        $this->object->setFontSize(22);
        $this->assertSame(22, $this->object->getFontSize());
    }

    public function testFontSizeWithoutEffectWithGdInternalFont()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped(
                'GD extension is required to run this test'
            );
        }
        $this->object->setFont(1);
        $this->object->setFontSize(22);
        $this->assertSame(8, $this->object->getFontSize());
    }

    public function testStringFontSize()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setFontSize('22a');
    }

    public function testStandardQuietZone()
    {
        $this->object->setBarThinWidth(3);
        $this->object->setFactor(4);
        $this->assertSame(120.0, $this->object->getQuietZone());
    }

    public function testAddInstruction()
    {
        $object       = new TestAsset\BarcodeTest();
        $instructions = [
            'type'        => 'text',
            'text'        => 'text',
            'size'        => 10,
            'position'    => [5, 5],
            'font'        => 'my_font.ttf',
            'color'       => '#123456',
            'alignment'   => 'center',
            'orientation' => 45,
        ];
        $object->addTestInstruction($instructions);
        $this->assertSame([$instructions], $object->getInstructions());
    }

    public function testAddPolygon()
    {
        $object       = new TestAsset\BarcodeTest();
        $points       = [];
        $color        = '#123456';
        $filled       = false;
        $instructions = [
            'type'   => 'polygon',
            'points' => $points,
            'color'  => $color,
            'filled' => $filled,
        ];
        $object->addTestPolygon($points, $color, $filled);
        $this->assertSame([$instructions], $object->getInstructions());
    }

    public function testAddPolygonWithDefaultColor()
    {
        $object = new TestAsset\BarcodeTest();
        $points = [];
        $color  = 123456;
        $object->setForeColor($color);
        $filled       = false;
        $instructions = [
            'type'   => 'polygon',
            'points' => $points,
            'color'  => $color,
            'filled' => $filled,
        ];
        $object->addTestPolygon($points, null, $filled);
        $this->assertSame([$instructions], $object->getInstructions());
    }

    public function testAddText()
    {
        $object       = new TestAsset\BarcodeTest();
        $size         = 10;
        $text         = 'foobar';
        $position     = [];
        $font         = 'my_font.ttf';
        $color        = '#123456';
        $alignment    = 'right';
        $orientation  = 45;
        $instructions = [
            'type'        => 'text',
            'text'        => $text,
            'size'        => $size,
            'position'    => $position,
            'font'        => $font,
            'color'       => $color,
            'alignment'   => $alignment,
            'orientation' => $orientation,
        ];
        $object->addTestText(
            $text,
            $size,
            $position,
            $font,
            $color,
            $alignment,
            $orientation
        );
        $this->assertSame([$instructions], $object->getInstructions());
    }

    public function testAddTextWithDefaultColor()
    {
        $object   = new TestAsset\BarcodeTest();
        $size     = 10;
        $text     = 'foobar';
        $position = [];
        $font     = 'my_font.ttf';
        $color    = 123456;
        $object->setForeColor($color);
        $alignment    = 'right';
        $orientation  = 45;
        $instructions = [
            'type'        => 'text',
            'text'        => $text,
            'size'        => $size,
            'position'    => $position,
            'font'        => $font,
            'color'       => $color,
            'alignment'   => $alignment,
            'orientation' => $orientation,
        ];
        $object->addTestText($text, $size, $position, $font, null, $alignment, $orientation);
        $this->assertSame([$instructions], $object->getInstructions());
    }

    public function testCheckParamsFontWithOrientation()
    {
        $this->expectException(ExceptionInterface::class);
        $this->object->setText('0');
        $this->object->setFont(1);
        $this->object->setOrientation(45);
        $this->object->checkParams();
    }

    public function testGetDefaultHeight()
    {
        $this->assertEquals(62, $this->object->getHeight());
    }
}

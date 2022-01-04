<?php

declare(strict_types=1);

namespace LaminasTest\Barcode\Renderer;

use Laminas\Barcode;
use Laminas\Barcode\Object\Code39;
use Laminas\Barcode\Renderer\AbstractRenderer;
use Laminas\Barcode\Renderer\Exception\ExceptionInterface;
use Laminas\Config;
use LaminasTest\Barcode\Object\TestAsset;
use PHPUnit\Framework\TestCase;
use Traversable;

use function date_default_timezone_get;
use function date_default_timezone_set;

abstract class AbstractTest extends TestCase
{
    /** @var AbstractRenderer */
    protected $renderer;

    /**
     * Stores the original set timezone
     *
     * @var string
     */
    private $originaltimezone;

    /**
     * @param array|Traversable $options
     * @return AbstractRenderer
     */
    abstract protected function getRendererObject($options = null);

    public function setUp(): void
    {
        $this->originaltimezone = date_default_timezone_get();

        // Set timezone to avoid "It is not safe to rely on the system's timezone settings."
        // message if timezone is not set within php.ini
        date_default_timezone_set('GMT');

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $this->renderer = $this->getRendererObject();
    }

    public function tearDown(): void
    {
        Barcode\Barcode::setBarcodeFont(null);
        if (! empty($this->originaltimezone)) {
            date_default_timezone_set($this->originaltimezone);
        }
    }

    public function testSetBarcodeObject()
    {
        $barcode = new Code39();
        $this->renderer->setBarcode($barcode);
        $this->assertSame($barcode, $this->renderer->getBarcode());
    }

    public function testGoodModuleSize()
    {
        $this->renderer->setModuleSize(2.34);
        $this->assertSame(2.34, $this->renderer->getModuleSize());
    }

    public function testModuleSizeAsString()
    {
        $this->expectException(ExceptionInterface::class);
        $this->renderer->setModuleSize('abc');
    }

    public function testModuleSizeLessThan0()
    {
        $this->expectException(ExceptionInterface::class);
        $this->renderer->setModuleSize(-0.5);
    }

    public function testAutomaticRenderError()
    {
        $this->renderer->setAutomaticRenderError(true);
        $this->assertSame(true, $this->renderer->getAutomaticRenderError());
        $this->renderer->setAutomaticRenderError(1);
        $this->assertSame(true, $this->renderer->getAutomaticRenderError());
    }

    public function testGoodHorizontalPosition()
    {
        foreach (['left', 'center', 'right'] as $position) {
            $this->renderer->setHorizontalPosition($position);
            $this->assertSame(
                $position,
                $this->renderer->getHorizontalPosition()
            );
        }
    }

    public function testBadHorizontalPosition()
    {
        $this->expectException(ExceptionInterface::class);
        $this->renderer->setHorizontalPosition('none');
    }

    public function testGoodVerticalPosition()
    {
        foreach (['top', 'middle', 'bottom'] as $position) {
            $this->renderer->setVerticalPosition($position);
            $this->assertSame(
                $position,
                $this->renderer->getVerticalPosition()
            );
        }
    }

    public function testBadVerticalPosition()
    {
        $this->expectException(ExceptionInterface::class);
        $this->renderer->setVerticalPosition('none');
    }

    public function testGoodLeftOffset()
    {
        $this->assertSame(0, $this->renderer->getLeftOffset());
        $this->renderer->setLeftOffset(123);
        $this->assertSame(123, $this->renderer->getLeftOffset());
        $this->renderer->setLeftOffset(0);
        $this->assertSame(0, $this->renderer->getLeftOffset());
    }

    public function testBadLeftOffset()
    {
        $this->expectException(ExceptionInterface::class);
        $this->renderer->setLeftOffset(- 1);
    }

    public function testGoodTopOffset()
    {
        $this->assertSame(0, $this->renderer->getTopOffset());
        $this->renderer->setTopOffset(123);
        $this->assertSame(123, $this->renderer->getTopOffset());
        $this->renderer->setTopOffset(0);
        $this->assertSame(0, $this->renderer->getTopOffset());
    }

    public function testBadTopOffset()
    {
        $this->expectException(ExceptionInterface::class);
        $this->renderer->setTopOffset(- 1);
    }

    public function testConstructorWithArray()
    {
        $renderer = $this->getRendererObject(
            [
                'automaticRenderError' => true,
                'unkownProperty'       => 'aValue',
            ]
        );
        $this->assertEquals(true, $renderer->getAutomaticRenderError());
    }

    public function testConstructorWithLaminasConfig()
    {
        $config   = new Config\Config(
            [
                'automaticRenderError' => true,
                'unkownProperty'       => 'aValue',
            ]
        );
        $renderer = $this->getRendererObject($config);
        $this->assertEquals(true, $renderer->getAutomaticRenderError());
    }

    public function testSetOptions()
    {
        $this->assertEquals(false, $this->renderer->getAutomaticRenderError());
        $this->renderer->setOptions(
            [
                'automaticRenderError' => true,
                'unkownProperty'       => 'aValue',
            ]
        );
        $this->assertEquals(true, $this->renderer->getAutomaticRenderError());
    }

    public function testRendererNamespace()
    {
        $this->renderer->setRendererNamespace('My_Namespace');
        $this->assertEquals('My_Namespace', $this->renderer->getRendererNamespace());
    }

    public function testRendererWithUnkownInstructionProvideByObject()
    {
        $this->expectException(ExceptionInterface::class);
        $object = new TestAsset\BarcodeTest();
        $object->setText('test');
        $object->addTestInstruction(['type' => 'unknown']);
        $this->renderer->setBarcode($object);
        $this->renderer->draw();
    }

    public function testBarcodeObjectProvided()
    {
        $this->expectException(ExceptionInterface::class);
        $this->renderer->draw();
    }

    abstract public function testDrawReturnResource();

    abstract public function testDrawWithExistantResourceReturnResource();

    abstract protected function getRendererWithWidth500AndHeight300();

    public function testHorizontalPositionToLeft()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(211, $barcode->getWidth());
        $renderer->setBarcode($barcode);
        $renderer->draw();
        $this->assertEquals(0, $renderer->getLeftOffset());
    }

    public function testHorizontalPositionToCenter()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(211, $barcode->getWidth());
        $renderer->setBarcode($barcode);
        $renderer->setHorizontalPosition('center');
        $renderer->draw();
        $this->assertEquals(144, $renderer->getLeftOffset());
    }

    public function testHorizontalPositionToRight()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(211, $barcode->getWidth());
        $renderer->setBarcode($barcode);
        $renderer->setHorizontalPosition('right');
        $renderer->draw();
        $this->assertEquals(289, $renderer->getLeftOffset());
    }

    public function testLeftOffsetOverrideHorizontalPosition()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(211, $barcode->getWidth());
        $renderer->setBarcode($barcode);
        $renderer->setLeftOffset(12);
        $renderer->setHorizontalPosition('center');
        $renderer->draw();
        $this->assertEquals(12, $renderer->getLeftOffset());
    }

    public function testVerticalPositionToTop()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(62, $barcode->getHeight());
        $renderer->setBarcode($barcode);
        $renderer->setVerticalPosition('top');
        $renderer->draw();
        $this->assertEquals(0, $renderer->getTopOffset());
    }

    public function testVerticalPositionToMiddle()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(62, $barcode->getHeight());
        $renderer->setBarcode($barcode);
        $renderer->setVerticalPosition('middle');
        $renderer->draw();
        $this->assertEquals(119, $renderer->getTopOffset());
    }

    public function testVerticalPositionToBottom()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(62, $barcode->getHeight());
        $renderer->setBarcode($barcode);
        $renderer->setVerticalPosition('bottom');
        $renderer->draw();
        $this->assertEquals(238, $renderer->getTopOffset());
    }

    public function testTopOffsetOverrideVerticalPosition()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $renderer->setModuleSize(1);
        $barcode = new Code39(['text' => '0123456789']);
        $this->assertEquals(62, $barcode->getHeight());
        $renderer->setBarcode($barcode);
        $renderer->setTopOffset(12);
        $renderer->setVerticalPosition('middle');
        $renderer->draw();
        $this->assertEquals(12, $renderer->getTopOffset());
    }
}

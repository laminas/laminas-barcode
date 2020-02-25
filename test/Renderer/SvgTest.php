<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Barcode\Renderer;

use Laminas\Barcode;
use Laminas\Barcode\Object\Code39;
use Laminas\Barcode\Renderer\Svg;

/**
 * @group      Laminas_Barcode
 */
class SvgTest extends TestCommon
{
    protected function getRendererObject($options = null)
    {
        return new Svg($options);
    }

    /**
     * @group 4708
     *
     * Needs to be run first due to runOnce static on drawPolygon
     */
    public function testSvgNoTransparency()
    {
        $svgCompare = file_get_contents(__DIR__ . '/_files/svg_transparency.xml');

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);

        $this->assertFalse($this->renderer->getTransparentBackground());
        $svgOutput = $this->renderer->draw()->saveXML();
        $this->assertNotEquals($svgCompare, $svgOutput);
    }

    /**
     * @group 4708
     *
     * Needs to be run first due to runOnce static on drawPolygon
     */
    public function testSvgTransparency()
    {
        $svgCompare = file_get_contents(__DIR__ . '/_files/svg_transparency.xml');

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);
        $this->renderer->setTransparentBackground(true);
        $this->assertTrue($this->renderer->getTransparentBackground());
        $svgOutput = $this->renderer->draw()->saveXML();
        $this->assertEquals($svgCompare, $svgOutput);
    }

    public function testSvgOrientation()
    {
        $svgCompare = file_get_contents(__DIR__ . '/_files/svg_oriented.xml');

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789', 'orientation' => 270]);
        $this->renderer->setBarcode($barcode);
        $this->renderer->setTransparentBackground(true);
        $this->assertTrue($this->renderer->getTransparentBackground());
        $svgOutput = $this->renderer->draw()->saveXML();
        $this->assertEquals($svgCompare, $svgOutput);
    }

    public function testType()
    {
        $this->assertSame('svg', $this->renderer->getType());
    }

    public function testGoodHeight()
    {
        $this->assertSame(0, $this->renderer->getHeight());
        $this->renderer->setHeight(123);
        $this->assertSame(123, $this->renderer->getHeight());
        $this->renderer->setHeight(0);
        $this->assertSame(0, $this->renderer->getHeight());
    }

    public function testBadHeight()
    {
        $this->expectException('\Laminas\Barcode\Renderer\Exception\ExceptionInterface');
        $this->renderer->setHeight(-1);
    }

    public function testGoodWidth()
    {
        $this->assertSame(0, $this->renderer->getWidth());
        $this->renderer->setWidth(123);
        $this->assertSame(123, $this->renderer->getWidth());
        $this->renderer->setWidth(0);
        $this->assertSame(0, $this->renderer->getWidth());
    }

    public function testBadWidth()
    {
        $this->expectException('\Laminas\Barcode\Renderer\Exception\ExceptionInterface');
        $this->renderer->setWidth(-1);
    }

    public function testGoodSvgResource()
    {
        $svgResource = new \DOMDocument();
        $renderer = $this->renderer->setResource($svgResource, 10);
        $this->assertSame($this->renderer, $renderer);
    }

    public function testDrawReturnResource()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);
        $resource = $this->renderer->draw();
        $this->assertInstanceOf('DOMDocument', $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testDrawWithExistantResourceReturnResource()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);
        $svgResource = new \DOMDocument();
        $rootElement = $svgResource->createElement('svg');
        $rootElement->setAttribute('xmlns', "http://www.w3.org/2000/svg");
        $rootElement->setAttribute('version', '1.1');
        $rootElement->setAttribute('width', 500);
        $rootElement->setAttribute('height', 300);
        $svgResource->appendChild($rootElement);
        $this->renderer->setResource($svgResource);
        $resource = $this->renderer->draw();
        $this->assertInstanceOf('DOMDocument', $resource);
        $this->assertSame($resource, $svgResource);
        Barcode\Barcode::setBarcodeFont('');
    }

    protected function getRendererWithWidth500AndHeight300()
    {
        $svg = new \DOMDocument();
        $rootElement = $svg->createElement('svg');
        $rootElement->setAttribute('xmlns', "http://www.w3.org/2000/svg");
        $rootElement->setAttribute('version', '1.1');
        $rootElement->setAttribute('width', 500);
        $rootElement->setAttribute('height', 300);
        $svg->appendChild($rootElement);
        return $this->renderer->setResource($svg);
    }
}

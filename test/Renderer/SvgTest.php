<?php

namespace LaminasTest\Barcode\Renderer;

use DOMDocument;
use Laminas\Barcode;
use Laminas\Barcode\Object\Code39;
use Laminas\Barcode\Renderer\Exception\ExceptionInterface;
use Laminas\Barcode\Renderer\Svg;
use Traversable;

/**
 * @group      Laminas_Barcode
 */
class SvgTest extends AbstractTest
{
    /**
     * @param array|Traversable $options
     * @return Svg
     */
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
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);

        $this->assertFalse($this->renderer->getTransparentBackground());
        $svgOutput = $this->renderer->draw()->saveXML();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/_files/svg_no_transparency.xml', $svgOutput);
    }

    /**
     * @group 4708
     *
     * Needs to be run first due to runOnce static on drawPolygon
     */
    public function testSvgTransparency()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);
        $this->renderer->setTransparentBackground(true);
        $this->assertTrue($this->renderer->getTransparentBackground());
        $svgOutput = $this->renderer->draw()->saveXML();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/_files/svg_transparency.xml', $svgOutput);
    }

    public function testSvgOrientation()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789', 'orientation' => 270]);
        $this->renderer->setBarcode($barcode);
        $this->renderer->setTransparentBackground(true);
        $this->assertTrue($this->renderer->getTransparentBackground());
        $svgOutput = $this->renderer->draw()->saveXML();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/_files/svg_oriented.xml', $svgOutput);
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
        $this->expectException(ExceptionInterface::class);
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
        $this->expectException(ExceptionInterface::class);
        $this->renderer->setWidth(-1);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testGoodSvgResource()
    {
        $svgResource = new DOMDocument();
        $this->renderer->setResource($svgResource, 10);
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
        $svgResource = new DOMDocument();
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

    /**
     * @return Svg
     */
    protected function getRendererWithWidth500AndHeight300()
    {
        $svg         = new DOMDocument();
        $rootElement = $svg->createElement('svg');
        $rootElement->setAttribute('xmlns', "http://www.w3.org/2000/svg");
        $rootElement->setAttribute('version', '1.1');
        $rootElement->setAttribute('width', 500);
        $rootElement->setAttribute('height', 300);
        $svg->appendChild($rootElement);
        return $this->renderer->setResource($svg);
    }
}

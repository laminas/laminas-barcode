<?php

namespace LaminasTest\Barcode\Renderer;

use Laminas\Barcode;
use Laminas\Barcode\Object\Code39;
use Traversable;
use ZendPdf as Pdf;
use ZendPdf\PdfDocument;

use function getenv;

/**
 * @group      Laminas_Barcode
 */
class PdfTest extends AbstractTest
{
    public function setUp(): void
    {
        if (! getenv('TESTS_LAMINAS_BARCODE_PDF_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_BARCODE_PDF_SUPPORT to test PDF render');
        }
        parent::setUp();
    }

    /**
     * @param array|Traversable $options
     * @return Barcode\Renderer\Pdf
     */
    protected function getRendererObject($options = null)
    {
        return new Barcode\Renderer\Pdf($options);
    }

    public function testType()
    {
        $this->assertSame('pdf', $this->renderer->getType());
    }

    public function testGoodPdfResource()
    {
        $pdfResource = new Pdf\PdfDocument();
        $this->renderer->setResource($pdfResource, 10);
    }

    public function testDrawReturnResource()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);
        $resource = $this->renderer->draw();
        $this->assertInstanceOf(PdfDocument::class, $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testDrawWithExistantResourceReturnResource()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/../Object/_fonts/Vera.ttf');
        $barcode = new Code39(['text' => '0123456789']);
        $this->renderer->setBarcode($barcode);
        $pdfResource = new Pdf\PdfDocument();
        $this->renderer->setResource($pdfResource);
        $resource = $this->renderer->draw();
        $this->assertInstanceOf(PdfDocument::class, $resource);
        $this->assertSame($resource, $pdfResource);
        Barcode\Barcode::setBarcodeFont('');
    }

    /**
     * @return Barcode\Renderer\Pdf
     */
    protected function getRendererWithWidth500AndHeight300()
    {
        $pdf          = new Pdf\PdfDocument();
        $pdf->pages[] = new Pdf\Page('500:300:');
        return $this->renderer->setResource($pdf);
    }

    public function testHorizontalPositionToCenter()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $barcode  = new Code39(['text' => '0123456789']);
        $this->assertEquals(211, $barcode->getWidth());
        $renderer->setBarcode($barcode);
        $renderer->setHorizontalPosition('center');
        $renderer->draw();
        $this->assertEquals(197, $renderer->getLeftOffset());
    }

    public function testHorizontalPositionToRight()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $barcode  = new Code39(['text' => '0123456789']);
        $this->assertEquals(211, $barcode->getWidth());
        $renderer->setBarcode($barcode);
        $renderer->setHorizontalPosition('right');
        $renderer->draw();
        $this->assertEquals(394.5, $renderer->getLeftOffset());
    }

    public function testVerticalPositionToMiddle()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $barcode  = new Code39(['text' => '0123456789']);
        $this->assertEquals(62, $barcode->getHeight());
        $renderer->setBarcode($barcode);
        $renderer->setVerticalPosition('middle');
        $renderer->draw();
        $this->assertEquals(134, $renderer->getTopOffset());
    }

    public function testVerticalPositionToBottom()
    {
        $renderer = $this->getRendererWithWidth500AndHeight300();
        $barcode  = new Code39(['text' => '0123456789']);
        $this->assertEquals(62, $barcode->getHeight());
        $renderer->setBarcode($barcode);
        $renderer->setVerticalPosition('bottom');
        $renderer->draw();
        $this->assertEquals(269, $renderer->getTopOffset());
    }
}

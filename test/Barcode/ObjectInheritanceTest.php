<?php

namespace LaminasTest\Barcode\Barcode;

use Laminas\Barcode\Barcode as NewNamespace;
use \Laminas\Barcode\Exception;
use PHPUnit\Framework\TestCase;

class ObjectInheritanceTest extends TestCase
{
    public function testAbstractBarcodeImplementsBarcodeInterface()
    {
        $mock = $this->getMockForAbstractClass(NewNamespace\AbstractBarcode::class);
        $this->assertInstanceOf(NewNamespace\BarcodeInterface::class, $mock);
    }

    public function testCodabarExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Codabar::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testCode128ExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Code128::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testCode25ExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Code25::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testCode25interleavedExtendsCode25()
    {
        $mock = $this->createMock(NewNamespace\Code25interleaved::class);
        $this->assertInstanceOf(NewNamespace\Code25::class, $mock);
    }

    public function testCode39ExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Code39::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testEan13ExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Ean13::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testEan2ExtendsEan5()
    {
        $mock = $this->createMock(NewNamespace\Ean2::class);
        $this->assertInstanceOf(NewNamespace\Ean5::class, $mock);
    }

    public function testEan5ExtendsEan13()
    {
        $mock = $this->createMock(NewNamespace\Ean5::class);
        $this->assertInstanceOf(NewNamespace\Ean13::class, $mock);
    }

    public function testEan8ExtendsEan13()
    {
        $mock = $this->createMock(NewNamespace\Ean8::class);
        $this->assertInstanceOf(NewNamespace\Ean13::class, $mock);
    }

    public function testErrorExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Error::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testIdentcodeExtendsCode25interleaved()
    {
        $mock = $this->createMock(NewNamespace\Identcode::class);
        $this->assertInstanceOf(NewNamespace\Code25interleaved::class, $mock);
    }

    public function testItf14ExtendsCode25interleaved()
    {
        $mock = $this->createMock(NewNamespace\Itf14::class);
        $this->assertInstanceOf(NewNamespace\Code25interleaved::class, $mock);
    }

    public function testLeitcodeExtendsIdentcode()
    {
        $mock = $this->createMock(NewNamespace\Leitcode::class);
        $this->assertInstanceOf(NewNamespace\Identcode::class, $mock);
    }

    public function testPlanetExtendsPostnet()
    {
        $mock = $this->createMock(NewNamespace\Planet::class);
        $this->assertInstanceOf(NewNamespace\Postnet::class, $mock);
    }

    public function testPostnetExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Postnet::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testRoyalmailExtendsAbstractBarcode()
    {
        $mock = $this->createMock(NewNamespace\Royalmail::class);
        $this->assertInstanceOf(NewNamespace\AbstractBarcode::class, $mock);
    }

    public function testUpcaExtendsEan13()
    {
        $mock = $this->createMock(NewNamespace\Upca::class);
        $this->assertInstanceOf(NewNamespace\Ean13::class, $mock);
    }

    public function testUpceExtendsEan13()
    {
        $mock = $this->createMock(NewNamespace\Upce::class);
        $this->assertInstanceOf(NewNamespace\Ean13::class, $mock);
    }

    public function testBarcodeValidationExceptionExtendsInvalidArgumentException()
    {
        $mock = $this->createMock(NewNamespace\Exception\BarcodeValidationException::class);
        $this->assertInstanceOf(NewNamespace\Exception\InvalidArgumentException::class, $mock);
    }

    public function testExceptionInterfaceExtendsExceptionInterface()
    {
        $mock = $this->createMock(NewNamespace\Exception\ExceptionInterface::class);
        $this->assertInstanceOf(Exception\ExceptionInterface::class, $mock);
    }

    public function testExtensionNotLoadedExceptionExtendsRuntimeException()
    {
        $mock = $this->createMock(NewNamespace\Exception\ExtensionNotLoadedException::class);
        $this->assertInstanceOf(NewNamespace\Exception\RuntimeException::class, $mock);
    }

    public function testInvalidArgumentExceptionExtendsInvalidArgumentException()
    {
        $mock = $this->createMock(NewNamespace\Exception\InvalidArgumentException::class);
        $this->assertInstanceOf(Exception\InvalidArgumentException::class, $mock);
        $this->assertInstanceOf(NewNamespace\Exception\ExceptionInterface::class, $mock);
    }

    public function testOutOfRangeExceptionExtendsOutOfRangeException()
    {
        $mock = $this->createMock(NewNamespace\Exception\OutOfRangeException::class);
        $this->assertInstanceOf(Exception\OutOfRangeException::class, $mock);
        $this->assertInstanceOf(NewNamespace\Exception\ExceptionInterface::class, $mock);
    }

    public function testRuntimeExceptionExtendsRuntimeException()
    {
        $mock = $this->createMock(NewNamespace\Exception\RuntimeException::class);
        $this->assertInstanceOf(Exception\RuntimeException::class, $mock);
        $this->assertInstanceOf(NewNamespace\Exception\ExceptionInterface::class, $mock);
    }
}
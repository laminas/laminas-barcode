<?php

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode\Object as OldNamespace;
use PHPUnit\Framework\TestCase;

class ObjectInheritanceTest extends TestCase
{
    public function testAbstractObjectImplementsObjectInterface()
    {
        $mock = $this->getMockForAbstractClass(OldNamespace\AbstractObject::class);
        $this->assertInstanceOf(OldNamespace\ObjectInterface::class, $mock);
    }

    public function testCodabarExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Codabar::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testCode128ExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Code128::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testCode25ExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Code25::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testCode25interleavedExtendsCode25()
    {
        $mock = $this->createMock(OldNamespace\Code25interleaved::class);
        $this->assertInstanceOf(OldNamespace\Code25::class, $mock);
    }

    public function testCode39ExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Code39::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testEan13ExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Ean13::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testEan2ExtendsEan5()
    {
        $mock = $this->createMock(OldNamespace\Ean2::class);
        $this->assertInstanceOf(OldNamespace\Ean5::class, $mock);
    }

    public function testEan5ExtendsEan13()
    {
        $mock = $this->createMock(OldNamespace\Ean5::class);
        $this->assertInstanceOf(OldNamespace\Ean13::class, $mock);
    }

    public function testEan8ExtendsEan13()
    {
        $mock = $this->createMock(OldNamespace\Ean8::class);
        $this->assertInstanceOf(OldNamespace\Ean13::class, $mock);
    }

    public function testErrorExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Error::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testIdentcodeExtendsCode25interleaved()
    {
        $mock = $this->createMock(OldNamespace\Identcode::class);
        $this->assertInstanceOf(OldNamespace\Code25interleaved::class, $mock);
    }

    public function testItf14ExtendsCode25interleaved()
    {
        $mock = $this->createMock(OldNamespace\Itf14::class);
        $this->assertInstanceOf(OldNamespace\Code25interleaved::class, $mock);
    }

    public function testLeitcodeExtendsIdentcode()
    {
        $mock = $this->createMock(OldNamespace\Leitcode::class);
        $this->assertInstanceOf(OldNamespace\Identcode::class, $mock);
    }

    public function testPlanetExtendsPostnet()
    {
        $mock = $this->createMock(OldNamespace\Planet::class);
        $this->assertInstanceOf(OldNamespace\Postnet::class, $mock);
    }

    public function testPostnetExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Postnet::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testRoyalmailExtendsAbstractObject()
    {
        $mock = $this->createMock(OldNamespace\Royalmail::class);
        $this->assertInstanceOf(OldNamespace\AbstractObject::class, $mock);
    }

    public function testUpcaExtendsEan13()
    {
        $mock = $this->createMock(OldNamespace\Upca::class);
        $this->assertInstanceOf(OldNamespace\Ean13::class, $mock);
    }

    public function testUpceExtendsEan13()
    {
        $mock = $this->createMock(OldNamespace\Upce::class);
        $this->assertInstanceOf(OldNamespace\Ean13::class, $mock);
    }
}
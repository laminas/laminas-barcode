<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Barcode;

use Laminas\Barcode;
use Laminas\Barcode\Object;
use Laminas\Barcode\Renderer;
use Laminas\Config\Config;
use ReflectionClass;
use ZendPdf as Pdf;

/**
 * @group      Laminas_Barcode
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Stores the original set timezone
     * @var string
     */
    private $_originaltimezone;

    public function setUp()
    {
        $this->originaltimezone = date_default_timezone_get();

        // Set timezone to avoid "It is not safe to rely on the system's timezone settings."
        // message if timezone is not set within php.ini
        date_default_timezone_set('GMT');

        // Reset plugin managers
        $r = new ReflectionClass('Laminas\Barcode\Barcode');

        $rObjectPlugins = $r->getProperty('objectPlugins');
        $rObjectPlugins->setAccessible(true);
        $rObjectPlugins->setValue(null);

        $rRendererPlugins = $r->getProperty('rendererPlugins');
        $rRendererPlugins->setAccessible(true);
        $rRendererPlugins->setValue(null);
    }

    public function tearDown()
    {
        date_default_timezone_set($this->originaltimezone);
    }

    public function testMinimalFactory()
    {
        $this->checkGDRequirement();
        $renderer = Barcode\Barcode::factory('code39');
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Code39', $renderer->getBarcode());
    }

    /**
     * @group fml
     */
    public function testMinimalFactoryWithRenderer()
    {
        $renderer = Barcode\Barcode::factory('code39', 'pdf');
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Pdf', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Code39', $renderer->getBarcode());
    }

    public function testFactoryWithOptions()
    {
        $this->checkGDRequirement();
        $options = array('barHeight' => 123);
        $renderer = Barcode\Barcode::factory('code25', 'image', $options);
        $this->assertEquals(123, $renderer->getBarcode()->getBarHeight());
    }

    public function testFactoryWithAutomaticExceptionRendering()
    {
        $this->checkGDRequirement();
        $options = array('barHeight' => - 1);
        $renderer = Barcode\Barcode::factory('code39', 'image', $options);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Error', $renderer->getBarcode());
    }

    public function testFactoryWithoutAutomaticObjectExceptionRendering()
    {
        $this->setExpectedException('\Laminas\Barcode\Object\Exception\ExceptionInterface');
        $options = array('barHeight' => - 1);
        $renderer = Barcode\Barcode::factory('code39', 'image', $options, array(), false);
    }

    public function testFactoryWithoutAutomaticRendererExceptionRendering()
    {
        $this->setExpectedException('\Laminas\Barcode\Renderer\Exception\ExceptionInterface');
        $this->checkGDRequirement();
        $options = array('imageType' => 'my');
        $renderer = Barcode\Barcode::factory('code39', 'image', array(), $options, false);
        $this->markTestIncomplete('Need to throw a configuration exception in renderer');
    }

    public function testFactoryWithLaminasConfig()
    {
        $this->checkGDRequirement();
        $config = new Config(array('barcode'  => 'code39',
                                   'renderer' => 'image'));
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Code39', $renderer->getBarcode());
    }

    public function testFactoryWithLaminasConfigAndObjectOptions()
    {
        $this->checkGDRequirement();
        $config = new Config(array('barcode'       => 'code25',
                                   'barcodeParams' => array(
                                   'barHeight'     => 123)));
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Code25', $renderer->getBarcode());
        $this->assertEquals(123, $renderer->getBarcode()->getBarHeight());
    }

    public function testFactoryWithLaminasConfigAndRendererOptions()
    {
        $this->checkGDRequirement();
        $config = new Config(array('barcode'        => 'code25',
                                   'rendererParams' => array(
                                   'imageType'      => 'gif')));
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Code25', $renderer->getBarcode());
        $this->assertSame('gif', $renderer->getImageType());
    }

    public function testFactoryWithoutBarcodeWithAutomaticExceptionRender()
    {
        $this->checkGDRequirement();
        $renderer = Barcode\Barcode::factory(null);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Error', $renderer->getBarcode());
    }

    public function testFactoryWithoutBarcodeWithAutomaticExceptionRenderWithLaminasConfig()
    {
        $this->checkGDRequirement();
        $config = new Config(array('barcode' => null));
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertInstanceOf('Laminas\Barcode\Object\Error', $renderer->getBarcode());
    }

    public function testFactoryWithExistingBarcodeObject()
    {
        $this->checkGDRequirement();
        $barcode = new Object\Code25();
        $renderer = Barcode\Barcode::factory($barcode);
        $this->assertSame($barcode, $renderer->getBarcode());
    }

    public function testBarcodeObjectFactoryWithExistingBarcodeObject()
    {
        $barcode = new Object\Code25();
        $generatedBarcode = Barcode\Barcode::makeBarcode($barcode);
        $this->assertSame($barcode, $generatedBarcode);
    }

    public function testBarcodeObjectFactoryWithBarcodeAsString()
    {
        $barcode = Barcode\Barcode::makeBarcode('code25');
        $this->assertInstanceOf('Laminas\Barcode\Object\Code25', $barcode);

        // ensure makeBarcode creates unique instances
        $this->assertNotSame($barcode, Barcode\Barcode::makeBarcode('code25'));
    }

    public function testBarcodeObjectFactoryWithBarcodeAsStringAndConfigAsArray()
    {
        $barcode = Barcode\Barcode::makeBarcode('code25', array('barHeight' => 123));
        $this->assertInstanceOf('Laminas\Barcode\Object\Code25', $barcode);
        $this->assertSame(123, $barcode->getBarHeight());
    }

    public function testBarcodeObjectFactoryWithBarcodeAsStringAndConfigAsLaminasConfig()
    {
        $config = new Config(array('barHeight' => 123));
        $barcode = Barcode\Barcode::makeBarcode('code25', $config);
        $this->assertInstanceOf('Laminas\Barcode\Object\Code25', $barcode);
        $this->assertSame(123, $barcode->getBarHeight());
    }

    public function testBarcodeObjectFactoryWithBarcodeAsLaminasConfig()
    {
        $config = new Config(array('barcode' => 'code25',
                                   'barcodeParams' => array(
                                   'barHeight' => 123)));
        $barcode = Barcode\Barcode::makeBarcode($config);
        $this->assertInstanceOf('Laminas\Barcode\Object\Code25', $barcode);
        $this->assertSame(123, $barcode->getBarHeight());
    }

    public function testBarcodeObjectFactoryWithBarcodeAsLaminasConfigButNoBarcodeParameter()
    {
        $this->setExpectedException('\Laminas\Barcode\Exception\ExceptionInterface');
        $config = new Config(array('barcodeParams' => array('barHeight' => 123) ));
        $barcode = Barcode\Barcode::makeBarcode($config);
    }

    public function testBarcodeObjectFactoryWithBarcodeAsLaminasConfigAndBadBarcodeParameters()
    {
        $this->setExpectedException('\Laminas\Barcode\Exception\ExceptionInterface');
        $barcode = Barcode\Barcode::makeBarcode('code25', null);
    }

    public function testBarcodeObjectFactoryWithNamespace()
    {
        $plugins = Barcode\Barcode::getObjectPluginManager();
        $plugins->setInvokableClass('barcodeNamespace', 'LaminasTest\Barcode\Object\TestAsset\BarcodeNamespace');
        $barcode = Barcode\Barcode::makeBarcode('barcodeNamespace');
        $this->assertInstanceOf('LaminasTest\Barcode\Object\TestAsset\BarcodeNamespace', $barcode);
    }

    public function testBarcodeObjectFactoryWithNamespaceExtendStandardLibray()
    {
        $plugins = Barcode\Barcode::getObjectPluginManager();
        $plugins->setInvokableClass('error', 'LaminasTest\Barcode\Object\TestAsset\Error');
        $barcode = Barcode\Barcode::makeBarcode('error');
        $this->assertInstanceOf('LaminasTest\Barcode\Object\TestAsset\Error', $barcode);
    }

    public function testBarcodeObjectFactoryWithNamespaceButWithoutExtendingObjectAbstract()
    {
        $plugins = Barcode\Barcode::getObjectPluginManager();
        $plugins->setInvokableClass('barcodeNamespaceWithoutExtendingObjectAbstract', 'LaminasTest\Barcode\Object\TestAsset\BarcodeNamespaceWithoutExtendingObjectAbstract');

        $this->setExpectedException('\Laminas\Barcode\Exception\ExceptionInterface');
        $barcode = Barcode\Barcode::makeBarcode('barcodeNamespaceWithoutExtendingObjectAbstract');
    }

    public function testBarcodeObjectFactoryWithUnexistentBarcode()
    {
        $this->setExpectedException('Laminas\ServiceManager\Exception\ServiceNotFoundException');
        $barcode = Barcode\Barcode::makeBarcode('laminas123', array());
    }

    public function testBarcodeRendererFactoryWithExistingBarcodeRenderer()
    {
        $this->checkGDRequirement();
        $renderer = new Renderer\Image();
        $generatedBarcode = Barcode\Barcode::makeRenderer($renderer);
        $this->assertSame($renderer, $generatedBarcode);
    }

    public function testBarcodeRendererFactoryWithBarcodeAsString()
    {
        $this->checkGDRequirement();
        $renderer = Barcode\Barcode::makeRenderer('image');
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);

        // ensure unique instance is created
        $this->assertNotSame($renderer, Barcode\Barcode::makeRenderer('image'));
    }

    public function testBarcodeRendererFactoryWithBarcodeAsStringAndConfigAsArray()
    {
        $this->checkGDRequirement();

        $renderer = Barcode\Barcode::makeRenderer('image', array('imageType' => 'gif'));
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertSame('gif', $renderer->getImageType());
    }

    public function testBarcodeRendererFactoryWithBarcodeAsStringAndConfigAsLaminasConfig()
    {
        $this->checkGDRequirement();
        $config = new Config(array('imageType' => 'gif'));
        $renderer = Barcode\Barcode::makeRenderer('image', $config);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertSame('gif', $renderer->getimageType());
    }

    public function testBarcodeRendererFactoryWithBarcodeAsLaminasConfig()
    {
        $this->checkGDRequirement();
        $config = new Config(array('renderer'       => 'image',
                                   'rendererParams' => array('imageType' => 'gif')));
        $renderer = Barcode\Barcode::makeRenderer($config);
        $this->assertInstanceOf('Laminas\Barcode\Renderer\Image', $renderer);
        $this->assertSame('gif', $renderer->getimageType());
    }

    public function testBarcodeRendererFactoryWithBarcodeAsLaminasConfigButNoBarcodeParameter()
    {
        $this->setExpectedException('\Laminas\Barcode\Exception\ExceptionInterface');
        $config = new Config(array('rendererParams' => array('imageType' => 'gif') ));
        $renderer = Barcode\Barcode::makeRenderer($config);
    }

    public function testBarcodeRendererFactoryWithBarcodeAsLaminasConfigAndBadBarcodeParameters()
    {
        $this->setExpectedException('\Laminas\Barcode\Exception\ExceptionInterface');
        $renderer = Barcode\Barcode::makeRenderer('image', null);
    }

    public function testBarcodeRendererFactoryWithNamespace()
    {
        $this->checkGDRequirement();
        $plugins = Barcode\Barcode::getRendererPluginManager();
        $plugins->setInvokableClass('rendererNamespace', 'LaminasTest\Barcode\Renderer\TestAsset\RendererNamespace');
        $renderer = Barcode\Barcode::makeRenderer('rendererNamespace');
        $this->assertInstanceOf('Laminas\Barcode\Renderer\RendererInterface', $renderer);
    }

    public function testBarcodeFactoryWithNamespaceButWithoutExtendingRendererAbstract()
    {
        $plugins = Barcode\Barcode::getRendererPluginManager();
        $plugins->setInvokableClass('rendererNamespaceWithoutExtendingRendererAbstract', 'LaminasTest\Barcode\Renderer\TestAsset\RendererNamespaceWithoutExtendingRendererAbstract');
        $this->setExpectedException('Laminas\Barcode\Exception\ExceptionInterface');
        $renderer = Barcode\Barcode::makeRenderer('rendererNamespaceWithoutExtendingRendererAbstract');
    }

    public function testBarcodeRendererFactoryWithUnexistentRenderer()
    {
        $this->setExpectedException('\Laminas\ServiceManager\Exception\ServiceNotFoundException');
        $renderer = Barcode\Barcode::makeRenderer('laminas', array());
    }

    public function testProxyBarcodeRendererDrawAsImage()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is required to run this test');
        }
        $resource = Barcode\Barcode::draw('code25', 'image', array('text' => '012345'));
        $this->assertInternalType('resource', $resource, 'Image must be a resource');
        $this->assertEquals('gd', get_resource_type($resource), 'Image must be a GD resource');
    }

    public function testProxyBarcodeRendererDrawAsImageAutomaticallyRenderImageIfException()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is required to run this test');
        }
        $resource = Barcode\Barcode::draw('code25', 'image');
        $this->assertInternalType('resource', $resource, 'Image must be a resource');
        $this->assertEquals('gd', get_resource_type($resource), 'Image must be a GD resource');
    }

    public function testProxyBarcodeRendererDrawAsPdf()
    {
        if (!constant('TESTS_LAMINAS_BARCODE_PDF_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_BARCODE_PDF_SUPPORT to test PDF render');
        }

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'pdf', array('text' => '012345'));
        $this->assertInstanceOf('ZendPdf\PdfDocument', $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeRendererDrawAsPdfAutomaticallyRenderPdfIfException()
    {
        if (!constant('TESTS_LAMINAS_BARCODE_PDF_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_BARCODE_PDF_SUPPORT to test PDF render');
        }

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'pdf');
        $this->assertInstanceOf('ZendPdf\PdfDocument', $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeRendererDrawAsSvg()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'svg', array('text' => '012345'));
        $this->assertInstanceOf('DOMDocument', $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeRendererDrawAsSvgAutomaticallyRenderSvgIfException()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'svg');
        $this->assertInstanceOf('DOMDocument', $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeObjectFont()
    {
        Barcode\Barcode::setBarcodeFont('my_font.ttf');
        $barcode = new Object\Code25();
        $this->assertSame('my_font.ttf', $barcode->getFont());
        Barcode\Barcode::setBarcodeFont('');
    }

    protected function checkGDRequirement()
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('This test requires the GD extension');
        }
    }
}

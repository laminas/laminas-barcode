# Barcode Renderers

Renderers support options, most of which are common across all implementations.
These options can be set in three ways:

- As an array or a [Traversable](http://php.net/traversable) object passed to the constructor.
- As an array passed to the `setOptions()` method.
- As discrete values passed to individual setters.

## Different ways to parameterize a renderer object

```php
use Laminas\Barcode\Renderer;

$options = ['topOffset' => 10];

// Case 1
$renderer = new Renderer\Pdf($options);

// Case 2
$renderer = new Renderer\Pdf();
$renderer->setOptions($options);

// Case 3
$renderer = new Renderer\Pdf();
$renderer->setTopOffset(10);
```

## Common Options

In the following list, the values have no units; as such, we will use the
generic term "unit." For example, the default value of the "thin bar" is "1
unit." The real units depend on the rendering support.

The individual setters are obtained by uppercasing the initial letter of the
option and prefixing the name with "set" (e.g. `barHeight` becomes
`setBarHeight`). All options have a correspondent getter prefixed with "get"
(e.g. `getBarHeight`).

Available options are:

Option                 | Data Type             | Default Value            | Description
:--------------------- | :-------------------: | :----------------------: | :----------
`rendererNamespace`    | string                | `Laminas\Barcode\Renderer`  | Namespace of the renderer; for example, if you need to extend the renderers.
`horizontalPosition`   | string                | "left"                   | Can be "left", "center" or "right". Can be useful with PDF or if the `setWidth()` method is used with an image renderer.
`verticalPosition`     | string                | "top"                    | Can be "top", "middle" or "bottom". Can be useful with PDF or if the `setHeight()` method is used with an image renderer.
`leftOffset`           | int                   | 0                        | Top position of the barcode inside the renderer. If used, this value will override the `horizontalPosition` option.
`topOffset`            | int                   | 0                        | Top position of the barcode inside the renderer. If used, this value will override the `verticalPosition` option.
`automaticRenderError` | bool                  | `FALSE`                  | Whether or not to automatically render errors. If an exception occurs, the provided barcode object will be replaced with an `Error` representation. Note that some errors (or exceptions) can not be rendered.
`moduleSize`           | float                 | 1                        | Size of a rendering module in the support.
`barcode`              | `Laminas\Barcode\Object` | `NULL`                   | The barcode object to render.

An additional getter exists: `getType()`. It returns the name of the renderer
class without the namespace (e.g.  `Laminas\Barcode\Renderer\Image` returns
"image").

## Laminas\\Barcode\\Renderer\\Image

The image renderer will draw the instruction list of the barcode object in an
image resource.  The default width of a module is 1 pixel.

> MISSING: **Installation Requirements**
>
> The [PHP extension GD](https://www.php.net/manual/book.image.php) is required for the image renderer, so be sure to have it installed before getting started.

Available options are:

Option      | Data Type | Default Value | Description
----------- | :-------: | ------------- | -----------
`height`    | int       | 0             | Allow you to specify the height of the result image. If "0", the height will be calculated by the barcode object.
`width`     | int       | 0             | Allow you to specify the width of the result image. If "0", the width will be calculated by the barcode object.
`imageType` | string    | "png"         | Specify the image format. Can be "png", "jpeg", "jpg" or "gif".

## Laminas\\Barcode\\Renderer\\Pdf

> WARNING: **Deprecated**
>
> Deprecated since 2.8.0, will be removed in 3.0.0.
>
> The PDF renderer is marked as deprecated because it uses the abandoned [zendframework/zendpdf](https://github.com/zendframework/zendpdf) component.

> MISSING: **Installation Requirements**
> The PDF renderer depends on the [zendframework/zendpdf](https://github.com/zendframework/zendpdf) component, so be sure to have it installed before getting started:
>
> ```bash
> $ composer require zendframework/zendpdf
> ```

The PDF renderer will draw the instruction list of the barcode object in a PDF
document. The default width of a module is 0.5 point.

There are no additional options for this renderer.

# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.7.0 - 2017-12-11

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- [zendframework/zend-barcode#25](https://github.com/zendframework/zend-barcode/pull/25) removes support
  for PHP 5.5.

- [zendframework/zend-barcode#38](https://github.com/zendframework/zend-barcode/pull/38) removes support
  for HHVM.

### Fixed

- Nothing.

## 2.6.1 - 2017-12-11

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-barcode#24](https://github.com/zendframework/zend-barcode/pull/24) updates the SVG
  renderer to remove extraneous whitespace in `rgb()` declarations, as the
  specification dis-allows whitespace, and many PDF readers/manipulators will
  not correctly consume SVG definitions that include them.

- [zendframework/zend-barcode#36](https://github.com/zendframework/zend-barcode/pull/36) provides several
  minor changes to namespace imports for the `Laminas\Barcode\Object` namespace to
  ensure the package works on PHP 7.2.

## 2.6.0 - 2016-02-17

### Added

- [zendframework/zend-barcode#23](https://github.com/zendframework/zend-barcode/pull/23) prepares and
  publishes the documentation to https://docs.laminas.dev/laminas-barcode/

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-barcode#12](https://github.com/zendframework/zend-barcode/pull/12) and
  [zendframework/zend-barcode#16](https://github.com/zendframework/zend-barcode/pull/16) update the code
  base to be forwards-compatible with laminas-servicemanager and laminas-stdlib v3.

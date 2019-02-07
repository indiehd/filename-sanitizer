# Filename Sanitizer
[![Build Status](https://travis-ci.org/indiehd/filename-sanitizer.svg?branch=master)](https://travis-ci.org/indiehd/filename-sanitizer)
[![Coverage Status](https://coveralls.io/repos/github/indiehd/filename-sanitizer/badge.svg?branch=master)](https://coveralls.io/github/indiehd/filename-sanitizer?branch=master)
[![Latest Stable Version](https://poser.pugx.org/indiehd/filename-sanitizer/v/stable)](https://packagist.org/packages/indiehd/filename-sanitizer)
[![Total Downloads](https://poser.pugx.org/indiehd/filename-sanitizer/downloads)](https://packagist.org/packages/indiehd/filename-sanitizer)
[![License](https://poser.pugx.org/indiehd/filename-sanitizer/license)](https://packagist.org/packages/indiehd/filename-sanitizer)

## About ##

This lightweight library provides a means by which to sanitize strings to be used in filenames.

Web applications commonly prompt users to download files with specific names, and these names should adhere to the target operating system's (and attendant filesystem's) naming conventions, or errors may result.

While it's possible to detect the target operating system via browser metadata, there is no practical means by which to detect the target *filesystem*, which is the ultimate arbiter of which file-naming conventions apply.

Conveniently, most browsers perform string replacements to ensure that downloaded files do not violate the target operating system's conventions, but developers cannot rely on this capability alone, because oftentimes they're tasked with naming files that are packed into an archive of some sort, in which case the browser is of no help in this regard. It is only when the archive is unpacked that filesystem errors may result if care is not taken to prevent them.

Given that the application cannot determine reliably which naming conventions the target filesystem will enforce, it is most practical to avoid characters that violate *any* commonly-used filesystem's conventions. This library aims to provide precisely that capability.

## Supported Operating Systems and Filesystems ##

The vast majority of end-user systems are running GNU/Linux, Windows, or MacOS, and for those not running one of the aforementioned operating systems, they're running an OS that supports the same filesystems as one of them. For this reason, it is most practical to sanitize only the characters that these operating systems forbid.

## Additional Safeguards ##

There are some characters that while not forbidden at the filesystem level could be "risky" to allow in filenames generated within the application. This is true especially for filenames derived from user input, and even more so when the filenames in question have the potential to be processed elsewhere, particularly in code that is outside of the developer's control (third-party extensions, etc.). Care must be taken when unpacking archives that contain certain filenames, for example.

To avoid some of the risks associated with malicious filenames, this library provides optional methods for stripping risky characters, too, as well as PHP code.

## Installation ##

Simply require the library in your project using Composer:

```
composer require indiehd/filename-sanitizer
```

## Usage Examples ##

```php
use IndieHD\FilenameSanitizer\FilenameSanitizer;

// Add illegal characters and a null-byte at the end of the name.

$sanitizer = new FilenameSanitizer('On / Off Again: My Journey to Stardom.jpg' . chr(0));

$sanitizer->stripIllegalFilesystemCharacters();

// The resultant string is free of the offending characters.

var_dump($sanitizer->getFilename());

// Output:
// "On  Off Again My Journey to Stardom.jpg"
```

A couple additional methods are available for further sanitizing the filename. These methods may be chained in any order.

```php

$sanitizer = new FilenameSanitizer('<?php malicious_function(); ?>`rm -rf /`' . chr(0));

$sanitizer->stripPhp()
    ->stripRiskyCharacters()
    ->stripIllegalFilesystemCharacters();
    
var_dump($sanitizer->getFilename());

// Output:
// "rm -rf "
```

## Limitations ##

This library makes no effort to validate the length of a given filename because a valid length can be extremely difficult to determine, given the many factors involved, especially when dealing with directory structures within archives.

For example, when a file is packed into an archive, its filename length is largely irrelevant because when the archive is unpacked, the length limit includes the present working directory depth, and the archive itself may include any hierarchy of arbitrary length in addition.

Even in consideration of the above, target filesystem limits may vary depending on the API used to access the filesystem.

The bottom-line is that filename length must be considered in the context of the full filesystem path, which is beyond this library's scope and should be implemented given the specific application's business needs.

## Versioning ##

This library makes every effort to observe [Semantic Versioning](https://semver.org/).

## Contributing ##

Pull-requests are welcome and should observe the guidelines described in the [indieHD Project Documentation](https://docs.indiehd.com/#/home/PULL-REQ).

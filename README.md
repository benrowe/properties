# Properties

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Adds the ability to add properties to a class at runtime.

## Install

Via Composer

``` bash
$ composer require benrowe/properties
```

## Usage

``` php
class Sample extends AbstractBase
{
    public function __construct()
    {
        // do stuff
        $this->configureProperties();
        // properties now available
    }

    /**
     * This method is automatically called by AbstractBase once
     * the constructor has finished executing
     */
    public function configureProperties()
    {
        $this->
            addProperty('simple')

        $this
            ->addProperty('complex', 'string', 'default value')
            ->setter(function($value) {
                return trim($value);
            })
            ->getter(function($value) {
                return str_reverse($value);
            })
            ->validate(function ($value) {
                return strlen($value) > 0;
            });

    }
}

$sample = new Sample;
$sample->simple = 'test';
$sample->undefined = 'newval'; // throws PropertyException "Undefined property 'undefined'"
$sample->complex; // 'default value'
$sample->complex = ''; // throws PropertyException "property 'complex' invalid value on set"
$sample->complex = ' hello world';
echo $sample->complex; //'dlrow olleh';

```

Each property is defined programatically (at runtime), and requires the class to run this code (with a hook).

Each property can have a

- name:string
- data type(s): string
- validation: string|closure
- setter: closure
- getter:string
- default value (null): mixed
- value: mixed (depends on data type/validation/setter)

## TODO
- build custom validation support for setting the value

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ben.rowe.83@gmail.com instead of using the issue tracker.

## Credits

- [Ben Rowe][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/benrowe/properties.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/benrowe/properties/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/benrowe/properties.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/benrowe/properties.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/benrowe/properties.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/benrowe/properties
[link-travis]: https://travis-ci.org/benrowe/properties
[link-scrutinizer]: https://scrutinizer-ci.com/g/benrowe/properties/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/benrowe/properties
[link-downloads]: https://packagist.org/packages/benrowe/properties
[link-author]: https://github.com/benrowe
[link-contributors]: ../../contributors

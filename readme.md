# Properties

Adds the ability to add properties to a class at runtime.

## Designed for

This package was designed for classes extending an abstraacted base where each concrete class requires unique data or data subject to specific conditions.

It was to bypass the need for a more complicated object to control this data. The command pattern was in mind when designing this package

## Basic Example

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
                })

        }
    }

    $sample = new Sample;
    $sample->simple = 'test';
    $sample->undefined = 'newval'; // throws PropertyException "Undefined property 'undefined'"
    $sample->complex; // 'default value'
    $sample->complex = ''; // throws PropertyException "property 'complex' invalid value on set"
    $sample->complex = ' hello world';
    echo $sample->complex; //'dlrow olleh';

## Properties

Each property is defined programatically (at runtime), and requires the class to run this code (with a hook).

Each property can have a

- name:string
- data type(s): string
- validation: string|closure
- setter: closure
- getter:string
- default value (null): mixed
- value: mixed (depends on data type/validation/setter)

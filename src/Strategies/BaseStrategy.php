<?php

namespace Mincer\Strategies
{

    use Mincer\IConverter;
    use Mincer\IConverterStrategy;

    class BaseStrategy implements IConverterStrategy
    {

        function serialize($value, IConverter $converter)
        {
            return $value;
        }

        function deserialize($value, IConverter $converter)
        {
            return $value;
        }

    }

}
<?php

namespace Mincer\Strategies
{

    use Mincer\ConverterInterface;
    use Mincer\ConverterStrategyInterface;

    class BaseStrategy implements ConverterStrategyInterface
    {

        function serialize($value, ConverterInterface $converter)
        {
            return $value;
        }

        function deserialize($value, ConverterInterface $converter)
        {
            return $value;
        }

    }

}
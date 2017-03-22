<?php


namespace Mincer
{

    interface ConverterStrategyInterface
    {

        function serialize($value, ConverterInterface $converter);

        function deserialize($value, ConverterInterface $converter);

    }

}
<?php


namespace Mincer
{

    interface IConverterStrategy
    {

        function serialize($value, IConverter $converter);

        function deserialize($value, IConverter $converter);

    }

}
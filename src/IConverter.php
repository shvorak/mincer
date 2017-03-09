<?php

namespace Mincer
{

    interface IConverter
    {

        function register(ConverterProfile $profile);

        function serialize($data);

        function serializeCollection($data);

        function deserialize($data, $className);

        function deserializeCollection($data, $className);

    }

}
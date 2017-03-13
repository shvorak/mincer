<?php

namespace Mincer
{

    interface IConverter
    {

        /**
         * Register converting profile
         *
         * @param ConverterProfile $profile
         *
         * @return IConverter
         */
        function register(ConverterProfile $profile);

        /**
         * Serializing object into array by registered profiles
         *
         * @param object $data
         *
         * @return array
         */
        function serialize($data);

        /**
         * Serializing collection of objects by registered profiles
         *
         * @param object[] $data
         *
         * @return array[] Collection of arrays
         */
        function serializeCollection($data);

        /**
         * Deserialize array into instance of given class name
         *
         * @param array     $data
         * @param string    $className Class name to deserialize
         *
         * @return object   Given class name instance
         */
        function deserialize($data, $className);

        /**
         * Deserialize collection of arrays into instance of given class name inside collection
         *
         * @param array[]   $data
         * @param string    $className Class name to deserialize
         *
         * @return object[] Given class name instance
         */
        function deserializeCollection($data, $className);

    }

}
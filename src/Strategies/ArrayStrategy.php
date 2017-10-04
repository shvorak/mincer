<?php

namespace Mincer\Strategies
{

    use Mincer\ConverterInterface;

    class ArrayStrategy extends BaseStrategy
    {

        /**
         * @var ScalarStrategy
         */
        private $_scalar;

        /**
         * ScalarRule constructor.
         *
         * @param string $type
         */
        public function __construct($type)
        {
            $this->_scalar = new ScalarStrategy($type);
        }

        function serialize($value, ConverterInterface $converter)
        {
            return array_map(function ($item) use ($converter) {
                return $this->_scalar->serialize($item, $converter);
            }, (array) $value);
        }

        function deserialize($value, ConverterInterface $converter)
        {
            return array_map(function ($item) use ($converter) {
                return $this->_scalar->deserialize($item, $converter);
            }, (array) $value);
        }

    }

}
<?php

namespace Mincer\Strategies
{

    use Mincer\IConverter;

    class ClassStrategy extends BaseStrategy
    {

        /**
         * @var string
         */
        private $_class;

        /**
         * @var bool
         */
        private $_collection;

        /**
         * TypeRule constructor.
         * @param string $class
         * @param bool $collection
         * @throws \Exception
         */
        public function __construct($class, $collection = false)
        {
            if (false === class_exists($class)) {
                throw new \Exception(sprintf('Class %s not found', $class));
            }
            $this->_class = $class;
            $this->_collection = $collection;
        }

        function serialize($value, IConverter $converter)
        {
            return $this->_collection === false
                ? $converter->serialize($value)
                : $converter->serializeCollection($value)
                ;
        }

        function deserialize($value, IConverter $converter)
        {
            return $this->_collection === false
                ? $converter->deserialize($value, $this->_class)
                : $converter->deserializeCollection($value, $this->_class)
                ;
        }

    }

}


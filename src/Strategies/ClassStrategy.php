<?php

namespace Mincer\Strategies
{

    use Traversable;
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
         * @var null
         */
        private $_wrapper;

        /**
         * TypeRule constructor.
         * @param string $class
         * @param bool $collection
         * @param null $wrapper
         * @throws \Exception
         */
        public function __construct($class, $collection = false, $wrapper = null)
        {
            if (false === class_exists($class)) {
                throw new \Exception(sprintf('Class %s not found', $class));
            }
            $this->_class = $class;
            $this->_wrapper = $wrapper;
            $this->_collection = $collection;
        }

        function serialize($value, IConverter $converter)
        {
            if ($value === null) {
                return $value;
            }
            if ($this->_collection) {
                if ($this->_wrapper) {
                    if ($value instanceof Traversable) {
                        $value = iterator_to_array($value);
                    } else {
                        throw new \InvalidArgumentException('Can\'t iterate value');
                    }
                }
                return $converter->serializeCollection($value);
            }

            return $converter->serialize($value);
        }

        function deserialize($value, IConverter $converter)
        {
            if ($value === null) {
                return $value;
            }
            if ($this->_collection) {
                $elements = $converter->deserializeCollection($value, $this->_class);

                if ($this->_wrapper) {
                    return new $this->_wrapper($elements);
                }

                return $elements;
            }

            return $converter->deserialize($value, $this->_class);
        }

    }

}


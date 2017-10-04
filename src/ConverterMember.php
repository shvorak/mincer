<?php

namespace Mincer
{

    use Mincer\Strategies\ArrayStrategy;
    use Mincer\Strategies\BaseStrategy;
    use Mincer\Strategies\DateStrategy;
    use Mincer\Strategies\ClassStrategy;
    use Mincer\Strategies\ScalarStrategy;

    class ConverterMember
    {

        const FALLBACK = '$$fallback';

        /**
         * @var string
         */
        private $_name;

        /**
         * Source field name
         *
         * @var string
         */
        private $_source;

        /**
         * @var ConverterStrategyInterface
         */
        private $_strategy;


        /**
         * ConverterMember constructor.
         *
         * @param string $name
         */
        public function __construct($name)
        {
            $this->_name = $name;
        }

        public function raw()
        {
            $this->_strategy = new BaseStrategy();
        }

        public function using(ConverterStrategyInterface $strategy)
        {
            $this->_strategy = $strategy;
        }

        public function string() {
            $this->_strategy = new ScalarStrategy('string');
        }

        public function typeOf($class) {
            $this->_strategy = new ClassStrategy($class);
        }

        /**
         * Use array of classes
         *
         * @param string $className
         * @param string $wrapperClassName
         */
        public function listOf($className, $wrapperClassName = null) {
            $this->_strategy = new ClassStrategy($className, true, $wrapperClassName);
        }

        /**
         * Use array of scalar
         *
         * @param string $scalarType
         */
        public function arrayOf($scalarType)
        {
            $this->_strategy = new ArrayStrategy($scalarType);
        }

        public function date($format = DATE_ISO8601) {
            $this->_strategy = new DateStrategy($format);
        }

        public function float() {
            $this->_strategy = new ScalarStrategy('float');
        }

        public function integer() {
            $this->_strategy = new ScalarStrategy('integer');
        }

        public function boolean() {
            $this->_strategy = new ScalarStrategy('boolean');
        }

        public function from($field)
        {
            $this->_source = $field;
            return $this;
        }

        /**
         * Returns member name
         *
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * @return string
         */
        public function getSource($field = null)
        {
            return $this->_source === null
                ? $field
                : $this->_source;
        }

        /**
         * Returns converter strategy
         *
         * @return ConverterStrategyInterface
         */
        public function getStrategy()
        {
            if (null === $this->_strategy) {
                throw new \InvalidArgumentException(sprintf(
                    'Strategy for member %s not defined', $this->_name
                ));
            }
            return $this->_strategy;
        }


    }

}
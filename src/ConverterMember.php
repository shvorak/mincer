<?php

namespace Mincer
{

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
         * @var IConverterStrategy
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

        public function using(IConverterStrategy $strategy)
        {
            $this->_strategy = $strategy;
        }

        public function string() {
            $this->_strategy = new ScalarStrategy('string');
        }

        public function typeOf($class) {
            $this->_strategy = new ClassStrategy($class);
        }

        public function listOf($class, $wrapper = null) {
            $this->_strategy = new ClassStrategy($class, true, $wrapper);
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

        /**
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * @return IConverterStrategy
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
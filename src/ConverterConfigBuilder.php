<?php

namespace Mincer
{

    class ConverterConfigBuilder
    {

        /**
         * @var string
         */
        private $_class;

        /**
         * @var ConverterMember[]
         */
        private $_members = array();

        /**
         * ConverterConfigBuilder constructor.
         * @param $class
         */
        public function __construct($class)
        {
            $this->_class = $class;
        }

        /**
         * @param string $name
         *
         * @return ConverterMember
         */
        public function property($name)
        {
            return $this->_members[$name] = new ConverterMember($name);
        }

        /**
         * Returns config
         *
         * @return ConverterConfig
         */
        public function getConfig()
        {
            return new ConverterConfig($this->_class, $this->_members);
        }

    }

}


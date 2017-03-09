<?php

namespace Mincer
{

    class ConverterConfig
    {

        private $_class;

        /**
         * @var ConverterMember
         */
        private $_value = false;

        /**
         * @var ConverterMember[]
         */
        private $_members = [];

        /**
         * ConverterConfig constructor.
         *
         * @param string $class
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
        public function member($name)
        {
            return $this->_members[$name] = new ConverterMember($name);
        }

        /**
         * @param string $name
         *
         * @return ConverterMember
         */
        public function value($name) {
            return $this->_value = new ConverterMember($name);
        }

        /**
         * Return all members
         * @return ConverterMember[]
         */
        public function getMembers()
        {
            return $this->_members;
        }

        /**
         * @param string $name
         * @return ConverterMember
         */
        public function getMember($name)
        {
            return $this->_members[$name];
        }

        public function getValue()
        {
            return $this->_value;
        }

        /**
         * @return ConverterProperty[]
         */
        public function getProperties()
        {
            $reflection = new \ReflectionClass($this->_class);
            $properties = array_filter($reflection->getProperties(), function (\ReflectionProperty $property) {
                return $property->isStatic() === false;
            });

            return array_map(function (\ReflectionProperty $property) {
                return new ConverterProperty($property);
            }, $properties);
        }

        /**
         * @param string $name
         * @return ConverterProperty
         */
        public function getProperty($name)
        {
            return array_filter($this->getProperties(), function (ConverterProperty $property) use ($name) {
                return $property->getName() == $name ? $property : null;
            })[0];
        }

    }

}
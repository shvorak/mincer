<?php

namespace Mincer
{

    use ReflectionClass;
    use ReflectionProperty;

    /**
     * Class ConverterConfig
     *
     * Class converter config
     *
     * @package Mincer
     */
    class ConverterConfig
    {

        /**
         * @var string
         */
        private $_class;

        /**
         * @var ConverterMember|null
         */
        private $_value = false;

        /**
         * @var ConverterMember[]
         */
        private $_members = [];

        /**
         * @var ReflectionClass
         */
        private $_reflect;

        /**
         * @var ReflectionProperty[]
         */
        private $_properties;

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
        public function property($name)
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

        /**
         * Returns member if type using single value representation
         *
         * @return ConverterMember|null
         */
        public function getValue()
        {
            return $this->_value;
        }

        /**
         * Returns specific class property
         *
         * @param string $name
         *
         * @return ConverterProperty
         */
        public function getProperty($name)
        {
            $properties = array_filter($this->getProperties(), function (ConverterProperty $property) use ($name) {
                return $property->getName() == $name ? $property : null;
            });

            if (count($properties) == 0) {
                throw new \InvalidArgumentException(sprintf(
                    'Property %s not exists in class', $name
                ));
            }
            return $properties[0];
        }

        /**
         * Returns properties for class type
         *
         * @return ConverterProperty[]
         */
        public function getProperties()
        {
            if ($this->_properties == null) {
                $list = array_filter(
                    $this->getReflection()->getProperties(),
                    function (ReflectionProperty $property) {
                        return $property->isStatic() === false;
                    }
                );
                $this->_properties = array_map(function (ReflectionProperty $property) {
                    return new ConverterProperty($property);
                }, $list);
            }

            return $this->_properties;
        }

        /**
         * Returns class reflection
         *
         * @return ReflectionClass
         */
        public function getReflection()
        {
            if ($this->_reflect == null) {
                $this->_reflect = new ReflectionClass($this->_class);
            }
            return $this->_reflect;
        }

    }

}
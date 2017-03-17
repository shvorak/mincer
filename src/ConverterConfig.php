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
         * @param string            $class
         * @param ConverterMember[] $members
         */
        public function __construct($class, array $members)
        {
            $this->_class = $class;
            $this->_members = $members;
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
         * Returns specific class property
         *
         * @param string $name
         *
         * @return ConverterProperty
         */
        public function getProperty($name)
        {
            $properties = $this->getProperties();

            if (false === array_key_exists($name, $properties)) {
                throw new \InvalidArgumentException(sprintf(
                    'Property %s not exists in class', $name
                ));
            }

            return $properties[$name];
        }

        /**
         * Returns properties for class type
         *
         * @return ConverterProperty[]
         */
        public function getProperties()
        {
            if ($this->_properties == null) {
                $properties = [];
                $parent = $this->getReflection()->getParentClass();
                while ($parent) {
                    try {
                        $properties = array_merge($properties, $parent->getProperties());
                        $parent = $parent->getParentClass();
                    } catch (\Exception $exception) { }
                }
                $properties = array_merge($this->getReflection()->getProperties(), $properties);

                $properties = array_reduce($properties, function ($list, ReflectionProperty $item) {
                    if (false === array_key_exists($item->getName(), $list)) {
                        $list[$item->getName()] = $item;
                    }
                    return $list;
                }, []);

                $list = array_filter($properties, function (ReflectionProperty $property) {
                    return $property->isStatic() === false;
                });

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
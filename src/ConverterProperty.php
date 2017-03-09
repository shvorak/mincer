<?php

namespace Mincer
{

    use ReflectionProperty;

    class ConverterProperty
    {

        /**
         * @var ReflectionProperty
         */
        private $_reflect;

        /**
         * ConverterProperty constructor.
         * @param ReflectionProperty $property
         */
        public function __construct(ReflectionProperty $property)
        {
            $this->_reflect = $property;
        }

        /**
         * Returns `true` if object has setter for property
         *
         * @return bool
         */
        public function hasSetter()
        {
            return method_exists(
                $this->_reflect->class,
                'set' . ucfirst($this->_reflect->name)
            );
        }

        /**
         * Returns setter callable for given object
         *
         * @param object $instance
         *
         * @throws \Exception
         *
         * @return callable
         */
        public function getSetter($instance)
        {
            if ($this->hasSetter()) {
                return [$instance, 'set' . ucfirst($this->_reflect->name)];
            }
            return function ($value) use ($instance) { $instance->{$this->_reflect->name} = $value;};
        }

        /**
         * Returns `true` if object has getter for property
         *
         * @return bool
         */
        public function hasGetter()
        {
            return method_exists(
                $this->_reflect->class,
                'get' . ucfirst($this->_reflect->name)
            );
        }

        /**
         * Returns getter callable for given object
         *
         * @param object $instance
         *
         * @throws \Exception
         *
         * @return callable
         */
        public function getGetter($instance)
        {
            if ($this->hasGetter()) {
                return [$instance, 'get' . ucfirst($this->_reflect->name)];
            }

            if (false === array_key_exists($this->_reflect->name, get_object_vars($instance))) {
                throw new \Exception(sprintf('Getter for property %s not defined', $this->_reflect->name));
            }

            return function () use ($instance) { return $instance->{$this->_reflect->name};};
        }

        /**
         * Returns property name
         *
         * @return string
         */
        public function getName()
        {
            return $this->_reflect->name;
        }

        /**
         * Sets value for object property using setter if present
         *
         * @param object $owner
         * @param mixed  $value
         *
         * @return void
         */
        public function set($owner, $value) {
            call_user_func($this->getSetter($owner), $value);
        }

        /**
         * Returns value from object property using getter if present
         *
         * @param object $owner
         *
         * @return mixed
         */
        public function get($owner) {
            return call_user_func($this->getGetter($owner));
        }

    }

}


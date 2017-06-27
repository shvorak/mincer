<?php

namespace Mincer
{

    use ReflectionProperty;

    /**
     * Class ConverterProperty
     *
     * @package Mincer
     */
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
                $this->getSetterName()
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
                return array($instance, $this->getSetterName());
            }

            if (false === $this->isPublic()) {
                throw new \Exception(sprintf(
                    'Property %s is not public and setter not defined', $this->_reflect->name
                ));
            }

            $self = $this;
            return function ($value) use ($instance, $self) {
                $instance->{$self->getReflection()->name} = $value;
            };
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
                $this->getGetterName()
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
                return array($instance, $this->getGetterName());
            }

            if (false === $this->isPublic()) {
                throw new \Exception(sprintf(
                    'Property %s is not public and getter not defined', $this->_reflect->name
                ));
            }

            $self = $this;
            return function () use ($instance, $self) {
                return $instance->{$self->getReflection()->name};
            };
        }

        public function isPublic()
        {
            return $this->_reflect->isPublic();
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

        /**
         * @return ReflectionProperty
         */
        public function getReflection()
        {
            return $this->_reflect;
        }

        /**
         * Returns best getter method
         *
         * @return string
         */
        private function getGetterName()
        {
            $class = $this->_reflect->getDeclaringClass();
            $method = ucfirst($this->getReflection()->name);
            if ($class->hasMethod('is' . $method)) {
                return 'is' . $method;
            }
            return 'get' . ucfirst($this->_reflect->name);
        }

        /**
         * Returns setter name
         *
         * @return string
         */
        private function getSetterName()
        {
            return 'set' . ucfirst($this->_reflect->name);
        }

    }

}


<?php

namespace Mincer
{


    abstract class ConverterProfile
    {

        /**
         * @var callable[]
         */
        private $_configs = [];

        /**
         * @var ConverterMember[]
         */
        private $_members = [];

        /**
         * Register fallback for all unhandled members in this profile
         *
         * @return ConverterMember
         */
        function others() {
            return $this->properties(ConverterMember::FALLBACK);
        }

        /**
         * Register converting strategy for all members named like passed string
         * This rule working only inside profile
         *
         * @param string|string[] $memberName
         *
         * @return ConverterMember
         */
        function properties($memberName) {
            $member = new ConverterMember($memberName);

            foreach ((array)$memberName as $name) {
                $this->_members[$name] = $member;
            }

            return $member;
        }

        /**
         * Register class converting config
         *
         * @param string $class
         * @param callable $configFactory [optional]
         *
         * @throws \Exception
         *
         * @return void
         */
        function create($class, callable $configFactory = null)
        {
            if (false === class_exists($class)) {
                throw new \Exception('Invalid class name');
            }
            $this->_configs[$class] = is_callable($configFactory) ? $configFactory : function() {};
        }


        /**
         * @return array
         */
        public function getConfigs()
        {
            return $this->_configs;
        }

        /**
         * @param string $className
         * @return bool
         */
        public function hasConfig($className)
        {
            return array_key_exists($className, $this->_configs);
        }

        /**
         * Returns configuration factory for class
         *
         * @param string $className
         *
         * @return callable
         */
        public function getConfig($className)
        {
            if (false === $this->hasConfig($className)) {
                throw new \InvalidArgumentException(
                    sprintf('Profile doesn\'t have converter configuration for class %s', $className)
                );
            }
            return $this->_configs[$className];
        }

        /**
         * @return ConverterMember[]
         */
        public function getMembers()
        {
            return $this->_members;
        }


    }

}
<?php

namespace Mincer
{


    abstract class ConverterProfile
    {

        /**
         * @var ConverterConfig[]
         */
        private $_configs = array();

        /**
         * @var ConverterMember[]
         */
        private $_members = array();

        /**
         * @var \Closure[]
         */
        private $_builders = array();

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
         * @param \Closure $configFactory [optional]
         *
         * @throws \Exception
         *
         * @return void
         */
        function create($class, \Closure $configFactory = null)
        {
            if (false === class_exists($class)) {
                throw new \Exception('Invalid class name');
            }
            $this->_builders[$class] = is_callable($configFactory) ? $configFactory : function() {};
        }

        /**
         * @param string $className
         * @return bool
         */
        public function hasConfig($className)
        {
            return array_key_exists($className, $this->_configs)
                || array_key_exists($className, $this->_builders);
        }

        /**
         * Returns configuration factory for class
         *
         * @param string $className
         *
         * @return ConverterConfig
         */
        public function getConfig($className)
        {
            if (array_key_exists($className, $this->_configs)) {
                return $this->_configs[$className];
            }
            if (array_key_exists($className, $this->_builders)) {
                $builder = new ConverterConfigBuilder($className);
                call_user_func($this->_builders[$className], $builder);
                $this->_configs[$className] = $builder->getConfig();
            }

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
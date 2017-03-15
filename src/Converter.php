<?php


namespace Mincer
{

    class Converter implements IConverter
    {

        /**
         * @var ConverterConfig[]
         */
        private $_configs = [];

        /**
         * @var ConverterProfile[]
         */
        private $_profiles = [];

        /**
         * @inheritdoc
         */
        function register(ConverterProfile $profile)
        {
            $this->_profiles[] = $profile;

            return $this;
        }

        /**
         * @inheritdoc
         */
        function serialize($data)
        {
            if (false === is_object($data)) {
                throw new \InvalidArgumentException('Serialize expects object.');
            }

            $result = [];
            $config = $this->getConfigFor(get_class($data));

            $members = $this->getMembersFor(get_class($data));

            $properties = $config->getProperties();

            foreach ($properties as $property) {

                $name = $property->getName();
                $member = $this->selectMember($members, $name);

                if ($member) {
                    $result[$name] = $member->getStrategy()
                        ->serialize($property->get($data), $this);
                }
            }

            return $result;
        }

        /**
         * @inheritdoc
         */
        function serializeCollection($data)
        {
            return array_map(function ($item) {
                return $this->serialize($item);
            }, $data);
        }

        /**
         * @inheritdoc
         */
        function deserialize($data, $className)
        {
            $reflect = new \ReflectionClass($className);
            $result = $reflect->newInstanceWithoutConstructor();
            $config = $this->getConfigFor($className);
            $properties = $config->getProperties();

            $members = $this->getMembersFor($className);

            foreach ($properties as $property) {
                $name = $property->getName();

                if (false === array_key_exists($name, $data)) {
                    // No data passed
                    // TODO : Maybe need throw ValidateException?
                    continue;
                }

                $member = $this->selectMember($members, $property->getName());

                if ($member) {
                    $property->set($result,
                        $member
                            ->getStrategy()
                            ->deserialize($data[$name], $this)
                    );
                }
            }

            return $result;
        }

        /**
         * @inheritdoc
         */
        function deserializeCollection($data, $className)
        {
            return array_map(function ($item) use ($className) {
                return $this->deserialize($item, $className);
            }, $data);
        }

        /**
         * Returns converter config for class
         *
         * @param string $className
         *
         * @return ConverterConfig
         */
        private function getConfigFor($className)
        {
            if (false === array_key_exists($className, $this->_configs)) {
                $profile = $this->getProfileFor($className);
                $factory = $profile->getConfigs()[$className];

                $builder = new ConverterConfigBuilder($className);

                // Execute config factory
                call_user_func($factory, $builder);

                // Register converter config
                $this->_configs[$className] = $builder->getConfig();
            }

            if (false === array_key_exists($className, $this->_configs)) {
                throw new \InvalidArgumentException('Converter config not found');
            }

            return $this->_configs[$className];
        }

        /**
         * Find best member
         *
         * @param ConverterMember[] $members
         * @param string            $name
         *
         * @return ConverterMember|boolean
         */
        private function selectMember($members, $name)
        {
            if (array_key_exists($name, $members)) {
                return $members[$name];
            }
            if (array_key_exists(ConverterMember::FALLBACK, $members)) {
                return $members[ConverterMember::FALLBACK];
            }
            return false;
        }

        /**
         * Returns union list of members for specific class
         *
         * @param string $className
         *
         * @return ConverterMember[]
         */
        private function getMembersFor($className)
        {
            $config = $this->getConfigFor($className);
            $profile = $this->getProfileFor($className);
            $members = $config->getMembers();

            $parent = $config->getReflection()->getParentClass();
            while ($parent) {
                try {
                    $parentConfig = $this->getConfigFor($parent->getName());
                    $members = array_merge($parentConfig->getMembers(), $members);
                    $parent = $parent->getParentClass();
                } catch (\Exception $exception) { }
            }

            return array_merge($profile->getMembers(), $members);
        }

        /**
         * Returns profile where defined class converting config
         *
         * @param string $className
         *
         * @throws \Exception
         *
         * @return ConverterProfile|null
         */
        private function getProfileFor($className)
        {
            $profiles = array_filter($this->_profiles, function (ConverterProfile $profile) use ($className) {
                return $profile->hasConfig($className);
            });

            if (count($profiles) > 1) {
                throw new \Exception('Multiple class converter config found');
            }
            if (count($profiles) === 0) {
                throw new \Exception(sprintf('Profile for class %s not found.', $className));
            }

            return array_values($profiles)[0];
        }

    }

}
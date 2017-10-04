<?php


namespace Mincer
{

    use Mincer\Errors\ClassNotRegisteredException;

    class Converter implements ConverterInterface
    {

        /**
         * @var ConverterConfig[]
         */
        private $_configs = array();

        /**
         * @var ConverterProfile[]
         */
        private $_profiles = array();

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

            $result = array();
            $config = $this->selectConfigFor(get_class($data));

            $members = $this->selectMembersFor(get_class($data));

            $properties = $config->getProperties();

            foreach ($properties as $property) {

                $name = $property->getName();
                $member = $this->selectMember($members, $name);

                if ($member) {
                    $result[$member->getSource($name)] = $member->getStrategy()
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
            $self = $this;
            return array_map(function ($item) use ($self) {
                return $self->serialize($item);
            }, $data);
        }

        /**
         * @inheritdoc
         */
        function deserialize($data, $className)
        {
            $config = $this->selectConfigFor($className);

            $reflect = new \ReflectionClass($className);
            $result = unserialize(
                sprintf(
                    'O:%d:"%s":0:{}',
                    strlen($reflect->getName()), $reflect->getName()
                )
            );

            $properties = $config->getProperties();

            $members = $this->selectMembersFor($className);

            foreach ($properties as $property) {
                $prop = $property->getName();
                $member = $this->selectMember($members, $prop);

                if (null === $member || false === array_key_exists($member->getSource($prop), $data)) {
                    // TODO : Maybe need throw ValidateException?
                    continue;
                }

                // TODO : Check for null values
                $property->set($result,
                    $member
                        ->getStrategy()
                        ->deserialize($data[$member->getSource($prop)], $this)
                );
            }
            return $result;
        }

        /**
         * @inheritdoc
         */
        function deserializeCollection($data, $className)
        {
            $self = $this;
            return array_map(function ($item) use ($className, $self) {
                return $self->deserialize($item, $className);
            }, $data);
        }

        /**
         * Returns converter config for class
         *
         * @param string $className
         *
         * @return ConverterConfig
         *
         * @throws ClassNotRegisteredException
         */
        private function selectConfigFor($className)
        {
            if (false === is_string($className)) {
                throw new \InvalidArgumentException('Class name must be a string');
            }
            if (false === array_key_exists($className, $this->_configs)) {
                $profile = $this->selectProfileFor($className);
                // Register converter config
                $this->_configs[$className] = $profile->getConfig($className);
            }

            // TODO : Remove this condition
            if (false === array_key_exists($className, $this->_configs)) {
                throw new ClassNotRegisteredException('Converter config not found');
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
        private function selectMembersFor($className)
        {
            $config = $this->selectConfigFor($className);
            $profile = $this->selectProfileFor($className);
            $members = $config->getMembers();

            $parent = $config->getReflection()->getParentClass();

            while ($parent) {
                $parentConfig = $this->selectConfigFor($parent->getName());
                $members = array_merge($parentConfig->getMembers(), $members);
                $parent = $parent->getParentClass();
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
        private function selectProfileFor($className)
        {
            $profiles = array_filter($this->_profiles, function (ConverterProfile $profile) use ($className) {
                return $profile->hasConfig($className);
            });

            if (count($profiles) > 1) {
                throw new \Exception('Multiple class converter config found');
            }
            if (count($profiles) === 0) {
                throw new ClassNotRegisteredException(sprintf('Profile for class %s not found.', $className));
            }

            // Reset resulted array keys
            $profiles = array_values($profiles);

            return $profiles[0];
        }

    }

}
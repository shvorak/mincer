<?php

namespace Mincer\Strategies
{

    use Mincer\ConverterInterface;

    class ScalarStrategy extends BaseStrategy
    {

        const SUPPORTER = [
            'string',
            'integer',
            'boolean',
            'float',
        ];

        /**
         * @var string
         */
        private $_type;

        /**
         * ScalarRule constructor.
         *
         * @param string $type
         */
        public function __construct($type)
        {
            if (false === in_array($type, static::SUPPORTER)) {
                throw new \InvalidArgumentException(
                    sprintf('Type "%s" not supported by ScalarStrategy', $type)
                );
            }
            $this->_type = $type;
        }

        function serialize($value, ConverterInterface $converter)
        {
            if (is_array($value) || is_object($value) || is_resource($value)) {
                throw new \InvalidArgumentException(
                    sprintf('Can\'t convert non scalar value, %s given', gettype($value))
                );
            }
            $target = $value;
            settype($target, $this->_type);
            return $target;
        }

        function deserialize($value, ConverterInterface $converter)
        {
            return $this->serialize($value, $converter);
        }

    }

}
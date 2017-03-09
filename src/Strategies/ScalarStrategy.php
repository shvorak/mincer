<?php

namespace Mincer\Strategies
{

    use Mincer\IConverter;

    class ScalarStrategy extends BaseStrategy
    {

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
            // TODO : Check by predefined type's list
            $this->_type = $type;
        }

        function deserialize($value, IConverter $converter)
        {
            $target = $value;
            if (false === settype($target, $this->_type)) {
                throw new \InvalidArgumentException(sprintf(
                    'Can\'t convert value %s to %s type', $value, $this->_type
                ));
            }
            return $target;
        }

    }

}
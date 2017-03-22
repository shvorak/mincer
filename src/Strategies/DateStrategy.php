<?php

namespace Mincer\Strategies
{

    use DateTime;
    use Mincer\ConverterInterface;

    class DateStrategy extends BaseStrategy
    {
        /**
         * @var string
         */
        private $_format;

        /**
         * DateStrategy constructor.
         *
         * @param string $format
         */
        public function __construct($format = DATE_ISO8601)
        {
            $this->_format = $format;
        }

        /**
         * @param DateTime           $value
         * @param ConverterInterface $converter
         *
         * @return string
         */
        function serialize($value, ConverterInterface $converter)
        {
            return $value ? $value->format($this->_format) : $value;
        }

        function deserialize($value, ConverterInterface $converter)
        {
            return $value ? DateTime::createFromFormat($this->_format, $value) : $value;
        }

    }

}
<?php

namespace Mincer\Strategies
{

    use DateTime;
    use Mincer\IConverter;

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
         * @param DateTime $value
         * @param IConverter $converter
         * @return string
         */
        function serialize($value, IConverter $converter)
        {
            return $value->format($this->_format);
        }

        function deserialize($value, IConverter $converter)
        {
            return DateTime::createFromFormat($this->_format, $value);
        }

    }

}
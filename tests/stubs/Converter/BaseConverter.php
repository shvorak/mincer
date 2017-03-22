<?php

namespace MincerTest\Stubs\Converter
{

    use Mincer\ConverterInterface;

    class BaseConverter extends ConverterInterface
    {

        /**
         * BaseConverter constructor.
         */
        public function __construct()
        {
            $this->register(new UserProfile());
        }

    }

}


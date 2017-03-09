<?php

namespace MincerTest\Stubs\Converter
{

    use Mincer\Converter;

    class BaseConverter extends Converter
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


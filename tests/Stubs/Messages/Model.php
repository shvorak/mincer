<?php

namespace MincerTest\Stubs\Messages
{

    abstract class Model
    {

        public static function className()
        {
            return get_called_class();
        }

    }

}


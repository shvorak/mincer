<?php

namespace MincerTest\Stubs\Messages
{

    class Profile
    {

        public $name;

        public $surname;

        public $patronymic;

        /**
         * Profile constructor.
         * @param $name
         * @param $surname
         * @param $patronymic
         */
        public function __construct($name, $surname, $patronymic)
        {
            $this->name = $name;
            $this->surname = $surname;
            $this->patronymic = $patronymic;
        }

    }

}


<?php

namespace MincerTest\Stubs\Messages
{

    class Comment
    {

        public $text;

        public $userPk;

        /**
         * Comment constructor.
         * @param $text
         * @param $userPk
         */
        public function __construct($text, $userPk)
        {
            $this->text = $text;
            $this->userPk = $userPk;
        }


    }

}


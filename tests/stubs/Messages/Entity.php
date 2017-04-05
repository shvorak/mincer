<?php

namespace MincerTest\Stubs\Messages
{

    class Entity
    {


        protected $id;

        protected $email;

        /**
         * @var bool
         */
        protected $active;

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * @param mixed $email
         */
        public function setEmail($email)
        {
            $this->email = $email;
        }

        /**
         * @return bool
         */
        public function isActive()
        {
            return $this->active;
        }

        /**
         * @param bool $active
         */
        public function setActive($active)
        {
            $this->active = $active;
        }

    }

}


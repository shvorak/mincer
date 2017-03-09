<?php

namespace MincerTest\Stubs\Messages
{

    class User
    {

        public $id;


        public $email;

        /**
         * @var Profile
         */
        protected $profile;
        /**
         * @var \DateTime
         */
        private $createdDate;

        /**
         * User constructor.
         * @param $id
         * @param $email
         * @param Profile $profile
         */
        public function __construct($id, $email, \DateTime $createdDate, Profile $profile)
        {
            $this->id = $id;
            $this->email = $email;
            $this->profile = $profile;
            $this->createdDate = $createdDate;
        }

        /**
         * @return Profile
         */
        public function getProfile()
        {
            return $this->profile;
        }

        /**
         * @param Profile $profile
         */
        public function setProfile(Profile $profile)
        {
            $this->profile = $profile;
        }

        /**
         * @return \DateTime
         */
        public function getCreatedDate()
        {
            return $this->createdDate;
        }

        /**
         * @param \DateTime $createdDate
         */
        public function setCreatedDate($createdDate)
        {
            $this->createdDate = $createdDate;
        }


    }

}


<?php

namespace MincerTest\Stubs\Messages
{

    class User
    {

        public $id;


        public $email;

        /**
         * @var boolean
         */
        protected $active;

        /**
         * @var float
         */
        protected $discount;

        /**
         * @var Profile
         */
        protected $profile;

        /**
         * @var CommentCollection
         */
        protected $comments;

        /**
         * @var \DateTime
         */
        private $createdDate;

        /**
         * @var \DateTime
         */
        private $loginDate;

        /**
         * User constructor.
         * @param $id
         * @param $email
         * @param \DateTime $createdDate
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

        /**
         * @return CommentCollection
         */
        public function getComments()
        {
            return $this->comments;
        }

        /**
         * @param CommentCollection $comments
         */
        public function setComments($comments)
        {
            $this->comments = $comments;
        }

        /**
         * @return \DateTime
         */
        public function getLoginDate()
        {
            return $this->loginDate;
        }

        /**
         * @param \DateTime $loginDate
         */
        public function setLoginDate($loginDate)
        {
            $this->loginDate = $loginDate;
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

        /**
         * @return float
         */
        public function getDiscount()
        {
            return $this->discount;
        }

        /**
         * @param float $discount
         */
        public function setDiscount($discount)
        {
            $this->discount = $discount;
        }

    }

}


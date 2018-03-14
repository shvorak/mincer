<?php

namespace MincerTest\Stubs\Messages
{


    class CommentCollection extends Model implements \IteratorAggregate
    {

        /**
         * @var array
         */
        private $comments;

        /**
         * CommentCollection constructor.
         * @param array $comments
         */
        public function __construct(array $comments)
        {
            $this->comments = $comments;
        }

        /**
         * @inheritdoc
         */
        public function getIterator()
        {
            return new \ArrayIterator($this->comments);
        }
    }

}


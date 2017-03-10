<?php

namespace MincerTest\Stubs\Converter
{

    use Mincer\ConverterConfig;
    use Mincer\ConverterConfigBuilder;
    use Mincer\ConverterProfile;
    use MincerTest\Stubs\Messages\Comment;
    use MincerTest\Stubs\Messages\CommentCollection;
    use MincerTest\Stubs\Messages\Profile;
    use MincerTest\Stubs\Messages\User;


    class UserProfile extends ConverterProfile
    {

        /**
         * UserProfile constructor.
         */
        public function __construct()
        {
            $this->others()->string();
            $this->properties(['id'])->integer();
            $this->properties(['createdDate', 'updatedDate'])->date(DATE_COOKIE);

            $this->create(User::class, function (ConverterConfigBuilder $config) {
                $config->property('profile')->typeOf(Profile::class);
                $config->property('comments')->listOf(Comment::class, CommentCollection::class);
            });

            $this->create(Profile::class);
            $this->create(Comment::class);
        }
    }

}


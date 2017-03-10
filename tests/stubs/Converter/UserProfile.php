<?php

namespace MincerTest\Stubs\Converter
{

    use Mincer\ConverterConfig;
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

            $this->fallback()->string();

            $this->members('id')->integer();
            $this->members('createdDate')->date(DATE_COOKIE);

            $this->create(User::class, function (ConverterConfig $config) {
                $config->member('profile')->typeOf(Profile::class);
            });

            $this->create(Profile::class);
        }
    }

}


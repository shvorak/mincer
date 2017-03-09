<?php

namespace MincerTest\Stubs\Converter
{

    use Mincer\ConverterConfig;
    use Mincer\ConverterProfile;
    use MincerTest\Stubs\Messages\Profile;
    use MincerTest\Stubs\Messages\User;


    class UserProfile extends ConverterProfile
    {

        /**
         * UserProfile constructor.
         */
        public function __construct()
        {
            $this->create(User::class, function (ConverterConfig $config) {
                $config->member('id')->integer();
                $config->member('email')->string();
                $config->member('profile')->typeOf(Profile::class);
                $config->member('createdDate')->date(DATE_COOKIE);
            });

            $this->create(Profile::class, function (ConverterConfig $config) {
                $config->member('name')->string();
                $config->member('surname')->string();
                $config->member('patronymic')->string();
            });
        }
    }

}


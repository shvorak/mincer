<?php

namespace MincerTest\Stubs\Converter
{

    use Mincer\ConverterConfigBuilder;
    use Mincer\ConverterProfile;
    use MincerTest\Stubs\Messages\InvalidUser;
    use MincerTest\Stubs\Messages\Model;
    use MincerTest\Stubs\Messages\User;

    class InvalidProfile extends ConverterProfile
    {

        public function __construct()
        {
            $this->create(User::className());
            $this->create(InvalidUser::className(), function (ConverterConfigBuilder $builder) {
                $builder->property('profile')->string();
            });
            $this->create(Model::className());
        }

    }

}


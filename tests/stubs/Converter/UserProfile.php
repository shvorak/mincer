<?php

namespace MincerTest\Stubs\Converter
{

    use Mincer\ConverterConfig;
    use Mincer\ConverterConfigBuilder;
    use Mincer\ConverterProfile;
    use Mincer\Strategies\DateStrategy;
    use MincerTest\Stubs\Messages\Comment;
    use MincerTest\Stubs\Messages\CommentCollection;
    use MincerTest\Stubs\Messages\CreateUserMessage;
    use MincerTest\Stubs\Messages\Entity;
    use MincerTest\Stubs\Messages\InvalidUser;
    use MincerTest\Stubs\Messages\Profile;
    use MincerTest\Stubs\Messages\User;


    class UserProfile extends ConverterProfile
    {

        /**
         * UserProfile constructor.
         */
        public function __construct()
        {
            $this->others()->raw();

            $this->properties(['id'])->integer();
            $this->properties(['createdDate', 'updatedDate'])->date(DATE_COOKIE);

            $this->create(User::class, function (ConverterConfigBuilder $config) {
                $config->property('name')->string();
                $config->property('active')->boolean();
                $config->property('discount')->float();
                $config->property('profile')->typeOf(Profile::class);
                $config->property('comments')->listOf(Comment::class, CommentCollection::class);

                // You can use your own converter strategy
                $config->property('loginDate')->using(new DateStrategy());
            });

            $this->create(CreateUserMessage::class);
            $this->create(Profile::class);
            $this->create(Comment::class);

            $this->create(Entity::class, function (ConverterConfigBuilder $config) {
                $config->property('id')->from('_id')->string();
                $config->property('email')->from('$email')->string();
            });

            /**
             *  INVALID CONFIGURATION
             */
            $this->create(InvalidUser::class, function (ConverterConfigBuilder $builder) {
                $builder->property('notExists');
            });
        }
    }

}


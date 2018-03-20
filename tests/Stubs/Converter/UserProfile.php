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
    use MincerTest\Stubs\Messages\Model;
    use MincerTest\Stubs\Messages\Profile;
    use MincerTest\Stubs\Messages\TestEntity;
    use MincerTest\Stubs\Messages\User;


    class UserProfile extends ConverterProfile
    {

        /**
         * UserProfile constructor.
         */
        public function __construct()
        {
            $this->others()->raw();

            $this->properties(array('id'))->integer();
            $this->properties(array('createdDate', 'updatedDate'))->date(DATE_COOKIE);

            $this->create(User::className(), function (ConverterConfigBuilder $config) {
                $config->property('name')->string();
                $config->property('active')->boolean();
                $config->property('discount')->float();
                $config->property('profile')->typeOf(Profile::className());
                $config->property('comments')->listOf(Comment::className(), CommentCollection::className());

                // You can use your own converter strategy
                $config->property('loginDate')->using(new DateStrategy());
            });

            $this->create(CreateUserMessage::className());
            $this->create(Profile::className());
            $this->create(Comment::className());

            $this->create(Entity::className(), function (ConverterConfigBuilder $config) {
                $config->property('id')->from('_id')->string();
                $config->property('email')->from('$email')->string();
            });

            /**
             *  INVALID CONFIGURATION
             */
            $this->create(InvalidUser::className(), function (ConverterConfigBuilder $builder) {
                $builder->property('notExists');
            });

            $this->create(Model::className());

            $this->create(TestEntity::className(), function (ConverterConfigBuilder $builder) {
                $builder->property('bool')->boolean();
                $builder->property('string')->string();
                $builder->property('ignored')->ignored();
            });
        }
    }

}


<?php

namespace MincerTest;

use Mincer\Converter;
use Mincer\ConverterInterface;
use MincerTest\Stubs\Converter\InvalidProfile;
use MincerTest\Stubs\Converter\UserProfile;
use MincerTest\Stubs\Messages\Comment;
use MincerTest\Stubs\Messages\CommentCollection;
use MincerTest\Stubs\Messages\CreateUserMessage;
use MincerTest\Stubs\Messages\InvalidUser;
use MincerTest\Stubs\Messages\Profile;
use MincerTest\Stubs\Messages\User;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{

    /**
     * @var InvalidUser
     */
    private $model;

    /**
     * @var ConverterInterface
     */
    private $converter;


    protected function setUp()
    {
        $this->model = new InvalidUser(1, '', new \DateTime(), new Profile('', '', ''));
        $this->converter = new Converter();
        $this->converter->register(new UserProfile());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Profile for class MincerTest\Stubs\Messages\InvalidUser not found.
     */
    public function testSerializeWithoutProfile()
    {
        $converter = new Converter();
        $converter->serialize($this->model);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Multiple class converter config found
     */
    public function testSerializeWithDuplicatedRegistrations()
    {
        $this->converter->register(new UserProfile());
        $this->converter->serialize($this->model);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Serialize expects object.
     */
    public function testSerializeNotAnObject()
    {
        $this->converter->serialize([]);
    }

    public function testConverting()
    {
        $user = new User(1, 'admin@admin.com', new \DateTime(), new Profile('1', '2', '3'));
        $user->setComments(new CommentCollection([
            new Comment('hi', 1)
        ]));

        $data = $this->converter->serialize($user);
        $this->assertTrue(is_array($data));

        $user2 = $this->converter->deserialize($data, User::class);
        $this->assertEquals($user, $user2);
    }

    public function testInheritedConverting()
    {
        $message = new CreateUserMessage(1, 'admin@admin.com', new \DateTime(), new Profile('1', '2', '3'));
        $data = $this->converter->serialize($message);
        $this->assertTrue(is_array($data));

        $user2 = $this->converter->deserialize($data, CreateUserMessage::class);
        $this->assertEquals($message, $user2);
    }

    public function testDeserializeFormArray()
    {
        $data = [
            'id' => 123,
            'email' => 'email',
            'active' => 1,
            'notExists' => 'value'
        ];

        /** @var User $user */
        $user = $this->converter->deserialize($data, User::class);

        $this->assertEquals(123, $user->id);
        $this->assertEquals('email', $user->email);
        $this->assertEquals(true, $user->isActive());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPropertyConfiguration()
    {
        $converter = new Converter();
        $converter->register(new InvalidProfile());

        $values = $converter->serialize($this->model);

        $converter->deserialize($values, InvalidUser::class);
    }

}

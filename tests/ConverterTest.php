<?php

namespace MincerTest;

use Mincer\Converter;
use Mincer\ConverterInterface;
use MincerTest\Stubs\Converter\InvalidProfile;
use MincerTest\Stubs\Converter\UserProfile;
use MincerTest\Stubs\Messages\Comment;
use MincerTest\Stubs\Messages\CommentCollection;
use MincerTest\Stubs\Messages\CreateUserMessage;
use MincerTest\Stubs\Messages\Entity;
use MincerTest\Stubs\Messages\InvalidUser;
use MincerTest\Stubs\Messages\Profile;
use MincerTest\Stubs\Messages\TestEntity;
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
        $this->converter->serialize(array());
    }

    public function testConverting()
    {
        $user = new User(1, 'admin@admin.com', new \DateTime(), new Profile('1', '2', '3'));
        $user->setComments(new CommentCollection(array(
            new Comment('hi', 1),
        )));

        $data = $this->converter->serialize($user);
        $this->assertTrue(is_array($data));

        $user2 = $this->converter->deserialize($data, User::className());
        $this->assertEquals($user, $user2);
    }

    public function testInheritedConverting()
    {
        $message = new CreateUserMessage(1, 'admin@admin.com', new \DateTime(), new Profile('1', '2', '3'));
        $data = $this->converter->serialize($message);
        $this->assertTrue(is_array($data));

        $user2 = $this->converter->deserialize($data, CreateUserMessage::className());
        $this->assertEquals($message, $user2);
    }

    public function testDeserializeFormArray()
    {
        $data = array(
            'id'        => 123,
            'email'     => 'email',
            'active'    => 1,
            'notExists' => 'value',
        );

        /** @var User $user */
        $user = $this->converter->deserialize($data, User::className());

        $this->assertEquals(123, $user->id);
        $this->assertEquals('email', $user->email);
        $this->assertEquals(true, $user->isActive());
    }

    public function testDeserializeWithMembersFrom()
    {
        $data = array(
            '_id'    => '123',
            '$email' => 'email',
            'active' => 1,
        );

        /** @var Entity $user */
        $user = $this->converter->deserialize($data, Entity::className());

        $this->assertEquals(123, $user->getId());
        $this->assertEquals('email', $user->getEmail());
        $this->assertEquals(true, $user->isActive());

        $back = $this->converter->serialize($user);

        $this->assertEquals($data, $back);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPropertyConfiguration()
    {
        $converter = new Converter();
        $converter->register(new InvalidProfile());

        $values = $converter->serialize($this->model);

        $converter->deserialize($values, InvalidUser::className());
    }

    /**
     * @dataProvider ignoredSerializationProvider
     * @param TestEntity $entity
     */
    public function testIgnoredPropertySerialization(TestEntity $entity)
    {
        $data = $this->converter->serialize($entity);
        $this->assertEquals($entity->isBool(),$data['bool']);
        $this->assertEquals($entity->getString(),$data['string']);
        $this->assertArrayNotHasKey('ignored', $data);
    }

    /**
     * @dataProvider ignoredDeserializationProvider
     * @param array $data
     */
    public function testIgnoredPropertyDeserialization(array $data)
    {
        /** @var TestEntity $entity */
        $entity = $this->converter->deserialize($data, TestEntity::className());
        $this->assertEquals($data['bool'],$entity->isBool());
        $this->assertEquals($data['string'],$entity->getString());
        $this->assertEquals('', $entity->getIgnored());
    }

    public function ignoredSerializationProvider()
    {
        return array(
            array(new TestEntity()),
            array(new TestEntity(true, 'test', 'ignored')),
        );
    }

    public function ignoredDeserializationProvider()
    {
        return array(
            array(array('bool' => false, 'string' => 'test', 'ignored' => '')),
            array(array('bool' => true, 'string' => 'test2', 'ignored' => 'ignored')),
        );
    }

}

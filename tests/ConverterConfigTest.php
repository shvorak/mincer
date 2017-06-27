<?php

namespace MincerTest;

use Mincer\ConverterConfig;
use Mincer\ConverterProfile;
use Mincer\ConverterProperty;
use MincerTest\Stubs\Converter\UserProfile;
use MincerTest\Stubs\Messages\CreateUserMessage;
use MincerTest\Stubs\Messages\InvalidUser;
use MincerTest\Stubs\Messages\Profile;
use MincerTest\Stubs\Messages\User;
use PHPUnit\Framework\TestCase;

class ConverterConfigTest extends TestCase
{

    /**
     * @var ConverterProfile
     */
    protected $profile;

    /**
     * @var ConverterConfig
     */
    private $config;

    /**
     * @var InvalidUser
     */
    private $invalidUser;

    protected function setUp()
    {
        $this->profile = new UserProfile();
        $this->config = $this->profile->getConfig(User::className());
        $this->invalidUser = new InvalidUser(1, '', new \DateTime(), new Profile('', '', ''));
    }

    public function testEmptyClassConfig()
    {
        $config = $this->profile->getConfig(Profile::className());
        $members = $config->getMembers();
        $this->assertTrue(empty($members));
    }

    public function testRealClassConfig()
    {
        $members = $this->config->getMembers();
        $this->assertFalse(empty($members));

        $this->assertNotNull($this->config->getMember('profile'));

        $property = $this->config->getProperty('profile');

        $this->assertInstanceOf('Mincer\ConverterProperty', $property);
        $this->assertEquals('profile', $property->getName());

        $this->assertFalse($property->isPublic());
        $this->assertTrue($property->hasGetter());
        $this->assertTrue($property->hasSetter());

    }

    public function testExtendedClass()
    {
        $config = $this->profile->getConfig(CreateUserMessage::className());
        $this->assertNotNull($config);

        $this->assertInstanceOf('Mincer\ConverterProperty', $config->getProperty('profile'));
    }

    public function testProperties()
    {
        $email = $this->config->getProperty('email');
        $this->assertTrue($email->isPublic());
        $this->assertFalse($email->hasGetter());
        $this->assertFalse($email->hasSetter());

        $this->assertTrue(is_callable($email->getGetter($this->invalidUser)));
        $this->assertTrue(is_callable($email->getSetter($this->invalidUser)));

        $active = $this->config->getProperty('active');

        $this->assertFalse($active->isPublic(), 'Is not public');
        $this->assertTrue($active->hasGetter(), 'Has getter');
        $this->assertTrue($active->hasSetter(), 'Has setter');

        $this->assertTrue(is_callable($active->getGetter($this->invalidUser)));
        $this->assertTrue(is_callable($active->getSetter($this->invalidUser)));
    }

    public function testSet()
    {
        $active = $this->config->getProperty('active');

        $this->assertNull($this->invalidUser->isActive());

        $active->set($this->invalidUser, true);
        $this->assertTrue($this->invalidUser->isActive());
        $this->assertTrue($active->get($this->invalidUser));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotExistsProperty()
    {
        $this->config->getProperty('undefined');
    }

    /**
     * @expectedException \Exception
     */
    public function testClassPropertyNoSetter()
    {
        $config = $this->profile->getConfig(InvalidUser::className());
        $config->getProperty('reason')->getSetter($this->invalidUser);
    }

    /**
     * @expectedException \Exception
     */
    public function testClassPropertyNoGetter()
    {
        $config = $this->profile->getConfig(InvalidUser::className());
        $config->getProperty('reason')->getGetter($this->invalidUser);
    }

}

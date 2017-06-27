<?php

namespace MincerTest;

use Mincer\ConverterConfig;
use Mincer\ConverterProfile;
use MincerTest\Stubs\Converter\UserProfile;
use MincerTest\Stubs\Messages\NotRegistered;
use MincerTest\Stubs\Messages\User;
use PHPUnit\Framework\TestCase;

class ConverterProfileTest extends TestCase
{

    /**
     * @var ConverterProfile
     */
    protected $profile;

    protected function setUp()
    {
        $this->profile = new UserProfile();
    }

    public function testClassConfigExists()
    {
        $this->assertTrue($this->profile->hasConfig(User::className()));
    }

    public function testClassConfigReflection()
    {
        $config = $this->profile->getConfig(User::className());
        $this->assertInstanceOf('Mincer\ConverterConfig', $config);

        $this->assertEquals($config, $this->profile->getConfig(User::className()));

        $reflect = $config->getReflection();
        $this->assertNotNull($reflect);
        $this->assertInstanceOf('ReflectionClass', $config->getReflection());
        $this->assertEquals($reflect, $config->getReflection());
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateNotExistingClass()
    {
        $this->profile->create('NotExistingClass');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testClassConfigMissed()
    {
        $this->assertFalse($this->profile->hasConfig(NotRegistered::className()));
        $this->profile->getConfig(NotRegistered::className());
    }

    public function testClassConfigMembers()
    {
        $this->assertTrue(is_array($this->profile->getMembers()));
    }

}

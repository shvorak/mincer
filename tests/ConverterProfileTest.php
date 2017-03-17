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
        $this->assertTrue($this->profile->hasConfig(User::class));
    }

    public function testClassConfigReflection()
    {
        $config = $this->profile->getConfig(User::class);
        $this->assertInstanceOf(ConverterConfig::class, $config);

        $this->assertEquals($config, $this->profile->getConfig(User::class));

        $reflect = $config->getReflection();
        $this->assertNotNull($reflect);
        $this->assertInstanceOf(\ReflectionClass::class, $config->getReflection());
        $this->assertEquals($reflect, $config->getReflection());
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateNotExistingClass()
    {
        $this->profile->create(NotExistingClass::class);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testClassConfigMissed()
    {
        $this->assertFalse($this->profile->hasConfig(NotRegistered::class));
        $this->profile->getConfig(NotRegistered::class);
    }

    public function testClassConfigMembers()
    {
        $this->assertTrue(is_array($this->profile->getMembers()));
    }

}

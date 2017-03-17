<?php

namespace MincerTest;

use Mincer\ConverterConfig;
use Mincer\ConverterMember;
use Mincer\Strategies\DateStrategy;
use MincerTest\Stubs\Converter\UserProfile;
use MincerTest\Stubs\Messages\CreateUserMessage;
use MincerTest\Stubs\Messages\InvalidUser;
use MincerTest\Stubs\Messages\User;
use PHPUnit\Framework\TestCase;

class ConverterMemberTest extends TestCase
{

    /**
     * @var UserProfile
     */
    private $profile;

    /**
     * @var ConverterConfig
     */
    private $userConfig;

    /**
     * @var ConverterConfig
     */
    private $messageConfig;


    public function setUp()
    {
        $this->profile = new UserProfile();
        $this->userConfig = $this->profile->getConfig(User::class);
        $this->messageConfig =  $this->profile->getConfig(CreateUserMessage::class);
    }

    public function testEmptyMembers()
    {
        $this->assertEmpty($this->messageConfig->getMembers());
    }

    public function testExistingMembers()
    {
        $loginDate = $this->userConfig->getMember('loginDate');
        $this->assertEquals('loginDate', $loginDate->getName());
        $this->assertInstanceOf(DateStrategy::class, $loginDate->getStrategy());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStrategyNotSet()
    {
        $this->profile->getConfig(InvalidUser::class)->getMember('notExists')->getStrategy();
    }

}

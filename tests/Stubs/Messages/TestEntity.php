<?php


namespace MincerTest\Stubs\Messages;


class TestEntity extends Model
{
    /**
     * @var bool
     */
    protected $bool;

    /**
     * @var string
     */
    protected $string;

    /**
     * @var string
     */
    protected $ignored;

    /**
     * TestEntity constructor.
     * @param bool   $bool
     * @param string $string
     * @param string $ignored
     */
    public function __construct($bool = false, $string = '', $ignored = '')
    {
        $this->bool = $bool;
        $this->string = $string;
        $this->ignored = $ignored;
    }

    /**
     * @return bool
     */
    public function isBool()
    {
        return $this->bool;
    }

    /**
     * @param bool $bool
     */
    public function setBool($bool)
    {
        $this->bool = $bool;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     */
    public function setString($string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function getIgnored()
    {
        return $this->ignored;
    }

    /**
     * @param string $ignored
     */
    public function setIgnored($ignored)
    {
        $this->ignored = $ignored;
    }


}
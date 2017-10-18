<?php

namespace Domatskiy;

use Domatskiy\VisitorSource\Source;

/**
 * Class VisitorSource
 * @package Domatskiy
 */
class VisitorSource
{
    const COOKIE_KEY = 'USER_SOURCE';

    /**
     * @var VisitorSource
     */
    protected static $entity;

    /**
     * @var null|Source
     */
    protected $last_source = null;

    /**
     * @var null|Source
     */
    protected $source = null;

    /**
     * @return VisitorSource
     */
    public static function getInstance()
    {
        if(!static::$entity && !(static::$entity instanceof VisitorSource))
            static::$entity = new static();

        return static::$entity;
    }


    function __construct()
    {
        $this->source = new Source();
        $this->last_source = (isset($_COOKIE[self::COOKIE_KEY])? $_COOKIE[self::COOKIE_KEY] : null);

        try{

            if(!$this->last_source || $this->last_source != '')
                $this->last_source = unserialize($this->last_source);

        }
        catch (\Exception $e){

            $this->last_source = null;

        }

        if(!$this->source || $this->source->isInner()){
            $this->last_source = $this->source;
            $this->last_source = null;
            }

        setcookie(self::COOKIE_KEY, serialize($this->source), time() + 86400 * 30, '/');
    }


    /**
     * @return Source|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return Source|null
     */
    public function getLastSource()
    {
        return $this->last_source;
    }

}
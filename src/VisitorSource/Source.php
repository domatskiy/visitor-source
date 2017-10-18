<?php

namespace Domatskiy\VisitorSource;

/**
 * Class Source
 * @package Domatskiy\VisitorSource
 */
class Source implements \Serializable
{

    const SOURCE_CONTEXT = 'CONTEXT';
    const SOURCE_SEO = 'SEO';
    const SOURCE_OTHER = 'OTHER';

    const SEARCH_YANDEX = 1;
    const SEARCH_GOOGLE = 2;
    const SEARCH_MAIL = 3;
    const SEARCH_RAMBLER = 4;

    const CONTEXT_YANDEX = 1;
    const CONTEXT_GOOGLE = 2;

    const UTM_YANDEX = 'Yandex_Direct';
    const UTM_GOOGLE = 'Google_Adword';
    const UTM_YANDEX_MARKET = 'YandexMarket';

    private $time;
    private $is_inner;
    private $source;
    private $referrer;
    private $referrer_params = array();
    private $search_system;
    private $context_system;

    /**
     * Source constructor.
     * @param $source
     * @param $referrer
     * @param $context_system
     */
    function __construct()
    {
        $url_referer = parse_url($_SERVER['HTTP_REFERER']);

        $this->time = time();
        $this->is_inner =  strpos($url_referer['host'], $_SERVER['HTTP_HOST']) !== false;

        if(!$this->is_inner)
        {
            parse_str($url_referer['query'], $this->referrer_params);
            $this->source = self::SOURCE_OTHER;

            $this->referrer = $url_referer['host'];
            $this->search_system = $this->__getSearchSystem($url_referer['host']);

            if($_GET['utm_source'])
            {
                $this->source = self::SOURCE_CONTEXT;
                $this->context_system = $this->__getContextSystem($url_referer['host']);
            }
            elseif ($this->search_system)
            {
                $this->source = self::SOURCE_SEO;
            }

        }

    }

    /**
     * @return bool
     */
    public function isInner()
    {
        return $this->is_inner;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return mixed
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * @return mixed
     */
    public function getReferrerParams()
    {
        if(!is_array($this->referrer_params))
            $this->referrer_params = array();

        return $this->referrer_params;
    }

    /**
     * @return int|null
     */
    public function getSearchSystem()
    {
        return $this->search_system;
    }


    /**
     * @return null
     */
    public function getContextSys()
    {
        return $this->context_system;
    }

    public static function getSourceTypeListList()
    {
        return array(
            self::SOURCE_TYPE_CONTEXT,
            self::SOURCE_TYPE_SEO,
            self::SOURCE_TYPE_OTHER,
        );
    }


    /**
     * @param $utm_source
     * @return int|null
     */
    protected function __getContextSystem($utm_source)
    {
        $context_system = null;

        if($utm_source == self::UTM_YANDEX)
            $context_system = self::CONTEXT_YANDEX;
        elseif($utm_source == self::UTM_GOOGLE)
            $context_system = self::CONTEXT_GOOGLE;

        return $context_system;
    }


    /**
     * @param $url
     * @return int|null
     */
    protected function __getSearchSystem($url)
    {
        $search_system = null;

        if(strpos($url, 'yandex.') !== false) //  && (isset($arParams['text']) || isset($arParams['etext']))
            $search_system = self::SEARCH_YANDEX;
        elseif(strpos($url, 'google.') !== false)
            $search_system = self::SEARCH_GOOGLE;
        elseif(strpos($url, 'mail.ru') !== false)
            $search_system = self::SEARCH_MAIL;
        elseif(strpos($url, 'rambler.') !== false)
            $search_system = self::SEARCH_RAMBLER;

        return $search_system;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->source,
            $this->referrer,
            $this->referrer_params,
            $this->search_system,
            $this->context_system,
            $this->time,
            ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($data)
    {
        list(
            $this->source,
            $this->referrer,
            $this->referrer_params,
            $this->search_system,
            $this->context_system,
            $this->time
            ) = unserialize($data);
    }

}
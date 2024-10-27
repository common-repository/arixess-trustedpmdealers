<?php namespace TrustedPMDealers;

if ( !defined( 'ABSPATH' )) {
    exit;
}

class Option
{
    private $api_key = null;
    private $api_cur = null;
    private $api_attr = null;

    public function __construct($key, $cur, $attr)
    {
        $this->api_key = $key;
        $this->api_cur = $cur;
        $this->api_attr = $attr;
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    public function getApiCur()
    {
        return $this->api_cur;
    }

    public function setApiCur($api_cur)
    {
        $this->api_cur = $api_cur;
    }

    public function getApiAttr()
    {
        return $this->api_attr;
    }

    public function setApiAttr($attr)
    {
        $this->api_attr = $attr;
    }
}
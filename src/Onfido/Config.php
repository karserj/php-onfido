<?php

namespace Onfido;

class Config
{
    public $token = '';
    public $version = '1';
    public $page = 1;
    public $perPage = 20;
    public $debug;

    public static $instance;

    protected function __construct()
    {

    }

    public static function init()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    public function paginate($page = null, $perPage = null)
    {
        if ($page !== null) {
            $this->page = $page;
        }

        if ($perPage !== null) {
            $this->perPage = $perPage;
        }

        return $this;
    }

    public function debug()
    {
        $this->debug = true;

        return $this;
    }

//    public function get_token() {
//        return $this->token;
//    }
}
<?php

namespace TienRocker\Auth\Controllers;

use Phalcon\Mvc\Controller;

class Base extends Controller
{
    /**
     * @var \Hybrid_Auth
     */
    protected $hybrid_auth;

    /**
     * @var \Hybrid_Providers_Twitter|\Hybrid_Providers_Facebook|\Hybrid_Providers_Google null
     */
    protected $provider = null;

    /**
     * Init config
     */
    public function initialize()
    {
        global $config;
        $this->hybrid_auth = new \Hybrid_Auth($config->hybrid->toArray());
    }
}
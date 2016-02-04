<?php

namespace TienRocker\Auth\Controllers;

use Phalcon\Config;
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
        if (!isset($config->hybrid)) {
            $_config_hybrid = include dirname(dirname(__FILE__)) . '/config/hybrid.php';
            $_config_hybrid['base_url'] = $config->application->baseUri . $this->router->getRewriteUri();
            $config->hybrid = new Config($_config_hybrid);
        }
        $this->hybrid_auth = new \Hybrid_Auth($config->hybrid->toArray());
    }
}
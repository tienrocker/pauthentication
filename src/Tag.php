<?php

namespace TienRocker\Auth;

use Phalcon\DI;

class Tag extends \Phalcon\Tag
{
    /**
     * @var \Phalcon\Translate\Adapter\Gettext
     */
    public static $translate;

    /**
     * @param $string
     * @param array $params
     * @return string
     */
    public static function _($string, array $params = [])
    {
        if (static::$translate == null) {
            global $config;

            //Ask browser what is the best language
            $locale = DI::getDefault()->get('request')->get('lang', 'string', DI::getDefault()->get('request')->getBestLanguage());
            if (empty($config->application->langDir) || !file_exists($config->application->langDir)) $config->application->langDir = __DIR__ . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR;

            //Check if we have a translation file for that lang
            if (!file_exists($config->application->langDir . $locale)) $locale = 'en_US';

            // clear cache in dev environment
            $default_domain = 'auth';
            if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
                $default_domain = sprintf('%s.%s', $default_domain, time());
                if(file_exists($default_domain));
            }

            //Init translate object
            static::$translate = new \Phalcon\Translate\Adapter\Gettext(
                [
                    'locale' => $locale,
                    'defaultDomain' => $default_domain,
                    'directory' => $config->application->langDir
                ]
            );
        }
        return static::$translate->_($string, $params);
    }
}
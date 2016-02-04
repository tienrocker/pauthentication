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
            $locale = DI::getDefault()->get('request')->get('lang', 'string', DI::getDefault()->get('request')->getBestLanguage());
            if (empty($config->application->langDir) || !file_exists($config->application->langDir)) $config->application->langDir = __DIR__ . DIRECTORY_SEPARATOR . 'lang';
            static::$translate = new \Phalcon\Translate\Adapter\Gettext(array(
                //'content' => include $file
                'locale' => $locale,
                'file' => 'messages',
                'directory' => $config->application->langDir
            ));
        }
        var_dump(static::$translate->_($string, $params), static::$translate);
        die;
        return static::$translate->_($string, $params);
    }
}
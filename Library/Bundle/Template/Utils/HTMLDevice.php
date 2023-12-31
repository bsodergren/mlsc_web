<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Bundle\Template\Utils;

use MLSC\Bundle\Template\Browser\Os;
use MLSC\Bundle\Template\Browser\Device;
use MLSC\Bundle\Template\Browser\Browser;

class HTMLDevice
{
    public static $DEVICE        = 'APPLICATION';
    public static $default_theme = 'default';

    public static $template_dir;

    public function __construct($template_directory = '')
    {
        self::$template_dir = $template_directory;
        self::$DEVICE       = $this->run();
    }

    public function run()
    {
        $device  = new Device();
        $os      = new Os();
        $browser = new Browser();

        /*
         *  windows desktop
         * Edge
         * Windows
         * unknown
         *
         * Media App
         * Chrome
         * Windows
         * Unknown
         *
         *
         * iPhone
         * Edge
         * iOS
         * iPhone
         *
         *
         */

        if ('Edge' == $browser->getName())
        {
            if ('Windows' == $os->getName())
            {
                return 'DESKTOP';
            } elseif ('iOS' == $os->getName())
            {
                return 'MOBILE';
            }
        } elseif ('Chrome' == $browser->getName())
        {
            if ('Windows' == $os->getName())
            {
                return 'APPLICATION';
            }
        } elseif ('Safari' == $browser->getName())
        {
            if ('iOS' == $os->getName())
            {
                return 'MOBILE';
            }
        }

        return [$browser->getName(), $device->getName(), $os->getName()];
    }

    private static function getDevicePath()
    {
        $ClassName = ucfirst(strtolower(self::$DEVICE));

        return 'MLSC\\Bundle\\Template\\Theme'; // .$ClassName;
    }

    public static function getHeader($template = '', $params = [])
    {
        $className = self::getDevicePath().'\\Header';
        if (class_exists($className))
        {
            return $className::Display($template, $params);
        }
    }

    public static function getNavbar($template = '', $params = [])
    {
        $className = self::getDevicePath().'\\Navbar';
        if (class_exists($className))
        {
            return $className::Display($template, $params);
        }
    }

    public static function getFooter($template = '', $params = [])
    {
        $className = self::getDevicePath().'\\Footer';
        if (class_exists($className))
        {
            return $className::Display($template, $params);
        }
    }

    public static function getAssetURL($type, $files)
    {
        $html = null;

        foreach ($files as $file)
        {
            $filePath = self::getThemepath().'/'.$file;
            $url      = __URL_LAYOUT__.'/'.strtolower(self::$DEVICE).'/'.$file;
            if (!file_exists($filePath))
            {
                $filePath = self::getDefaultTheme().'/'.$file;
                $url      = __URL_LAYOUT__.'/'.strtolower(self::$default_theme).'/'.$file;
                if (!file_exists($filePath))
                {
                    $url = null;
                }
            }
            if (null !== $url)
            {
                $url = $url.'?'.random_int(100000, 999999);
                switch ($type)
                {
                    case 'image':
                        $html .= $url;
                        break;
                    case 'css':
                        $html .= '<link rel="stylesheet" href="'.$url.'">'.\PHP_EOL;
                        break;
                    case 'js':
                        $html .= '<script src="'.$url.'" crossorigin="anonymous"></script>'.\PHP_EOL;
                        break;
                }
            }
        }

        return $html;
    }

    public static function getThemePath()
    {
        return self::$template_dir.\DIRECTORY_SEPARATOR.strtolower(self::$DEVICE);
    }

    public static function getDefaultTheme()
    {
        return self::$template_dir.\DIRECTORY_SEPARATOR.strtolower(self::$default_theme);
    }

    public static function getTemplateFile($template)
    {
        $template      = \DIRECTORY_SEPARATOR.str_replace('.html', '', $template);
        $template_file = self::getThemePath().\DIRECTORY_SEPARATOR.$template.'.html';
        if (!file_exists($template_file))
        {
            $template_file = self::getDefaultTheme().\DIRECTORY_SEPARATOR.$template.'.html';
            if (!file_exists($template_file))
            {
                $template_file = null;
            }
        }

        return $template_file;
    }
}

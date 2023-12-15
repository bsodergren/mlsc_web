<?php
/**
 * Command like Metatag writer for video files.
 */

namespace MLSC\Traits;

trait Translate
{
    public static $Class;

    public static function text($constant, $vars = [])
    {
        $class     = self::$Class;
        $thisClass = new \ReflectionClass($class);
        $text      = $thisClass->getConstant($constant);

        if (false == $text)
        {
            return '<error>'.$constant.' not yet set in '.$class.'</error> ';
        }

        if (\is_array($vars))
        {
            foreach ($vars as $key => $value)
            {
                $key  = '%%'.strtoupper($key).'%%';
                $text = str_replace($key, $value, $text);
            }

            $text = preg_replace_callback('|%%(\w+)%%|i', function ($matches)
            { return ''; }, $text);
        }

        return $text;
    }
}

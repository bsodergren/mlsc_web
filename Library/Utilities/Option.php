<?php
/**
 * Command like Metatag writer for video files.
 */

namespace MLSC\Utilities;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Option.
 */
class Option extends InputOption
{
    private static $options    = [];

    private static $cmdOptions = [];

    /**
     * init.
     *
     * @param mixed $input
     */
    public static function init(InputInterface $input)
    {
        self::$options = $input->getOptions();
    }

    public static function getOptions()
    {
        if (0 == \count(self::$cmdOptions))
        {
            foreach (self::$options as $option => $value)
            {
                if (\is_array($value))
                {
                    if (\count($value) > 0)
                    {
                        self::$cmdOptions[$option] = $value;
                    }
                } else
                {
                    if (null !== $value)
                    {
                        if (false != $value)
                        {
                            self::$cmdOptions[$option] = $value;
                        }
                    }
                }
            }
        }

        return self::$cmdOptions;
    }

    public static function getValue($name, $return = false)
    {
        if (\array_key_exists($name, self::$options))
        {
            if (!\is_array(self::$options[$name]))
            {
                if (true == $return)
                {
                    if ('filelist' == $name)
                    {
                        $fileList = str_replace('.mp4,', '.mp4|', self::$options[$name]);

                        return explode('|', $fileList);
                    }

                    return explode(',', self::$options[$name]);
                }

                return self::$options[$name];
            }
            if (\is_array(self::$options[$name]))
            {
                if (0 == \count(self::$options[$name]))
                {
                    return null;
                }
                if (1 == \count(self::$options[$name]))
                {
                    if (true == $return)
                    {
                        return explode(',', self::$options[$name][0]);
                    }

                    return self::$options[$name];
                }
                if (\count(self::$options[$name]) > 1)
                {
                    return self::$options[$name];
                }
            }
        }

        return null;
    }

    public static function isTrue($name)
    {
        if (\array_key_exists($name, self::$options))
        {
            if (\is_array(self::$options[$name]))
            {
                if (\count(self::$options[$name]) > 0)
                {
                    return true;
                }

                return false;
            }
            if (null !== self::$options[$name])
            {
                return self::$options[$name];
            }
        }

        return null;
    }

    public static function dump($loc, ...$val)
    {
        if (self::isTrue('dump'))
        {
            if ($loc == self::getValue('dump'))
            {
                dd($val);
            }
        }
    }
}

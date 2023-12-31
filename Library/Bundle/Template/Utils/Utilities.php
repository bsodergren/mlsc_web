<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Bundle\Template\Utils;

class Utilities
{
    public static function isTrue($define_name)
    {
        if (\defined($define_name))
        {
            if (true == \constant($define_name))
            {
                //  MediaUpdate::echo(constant($define_name));
                return 1;
            }
        }

        return 0;
    }

    public static function isSet($define_name)
    {
        if (\defined($define_name))
        {
            return 1;
        }

        return 0;
    }

    public static function toint($string)
    {
        $string_ret = str_replace(',', '', $string);

        return $string_ret;
    }
}

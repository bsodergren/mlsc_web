<?php
/**
 * Command like Metatag writer for video files.
 */

namespace MLSC\Utilities;

class Strings extends \Nette\Utils\Strings
{
    public $text = '';

    public static function clean($filename)
    {
        foreach (str_split($filename) as $char)
        {
            if (\ord($char) > 125)
            {
                $str[] = ' ';
            } else
            {
                $str[] = $char;
            }
        }
        $filename      = implode('', $str);
        $special_chars = ['?', '[', '´', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '|', '`', '!', '{', '}', '%', '+', '’', '«', '»', '”', '“', \chr(0)];
        $filename      = str_replace($special_chars, '', $filename);
        $special_chars = ['(', ')', '~'];
        $filename      = str_replace($special_chars, ' ', $filename);
        $filename      = str_replace(['%20', '+'], '-', $filename);
        $filename      = preg_replace('/[\r\n\t ]+/', '_', $filename);
        $filename      = str_replace('___', '_', $filename);

        return trim($filename, '.-_');
    }

    public static function print_array($array, $die = 0)
    {
        print_r($array);
        if (1 == $die)
        {
            exit(\PHP_EOL);
        }
    }

    public static function CallingFunctionName()
    {
        $trace = debug_backtrace();

        // dd($trace);
        foreach ($trace as $key => $row)
        {
            if (str_contains($row['function'], 'CallingFunctionName'))
            {
                continue;
            }

            if (\array_key_exists('class', $row))
            {
                if (str_contains($row['class'], 'Symfony'))
                {
                    continue;
                }
                if (str_contains($row['class'], 'MediaStopWatch'))
                {
                    continue;
                }
            }

            if (str_contains($row['function'], 'watch'))
            {
                $calledFile = basename($row['file']);
                $calledLine = $row['line'];

                continue;
            }
            if (str_contains($row['function'], 'require_once'))
            {
                $calledFile = basename($row['file']);
                $calledLine = $row['line'];

                continue;
            }
            if ('' != $row['class'])
            {
                $class = $row['class'];
                preg_match('/.*\\\\([A-Za-z]+)/', $class, $out);
                $class = $out[1];
            }

            if ($row['function'])
            {
                $function = $row['function'];
            }

            $TraceList = $calledFile.':'.$class.':'.$function.':'.$calledLine;

            break;
        }

        return $TraceList;
    }

    public static function truncateString($string, $maxlength, $ellipsis = false)
    {
        if (mb_strlen($string) <= $maxlength)
        {
            return $string;
        }

        if (str_contains($string, "\033[0m"))
        {
            $string       = str_replace("\033[0m", '', $string);
            $color_length = mb_strlen("\033[0m");
            $color_close  = "\033[0m";
        }
        if (empty($ellipsis))
        {
            $ellipsis = '';
        }

        if (true === $ellipsis)
        {
            $ellipsis = '…';
        }

        $ellipsis_length = mb_strlen($ellipsis);

        $maxlength       = $maxlength - $ellipsis_length - $color_length;

        return trim(mb_substr($string, 0, $maxlength)).$ellipsis.$color_close;
    }

    public static function showStatus($done, $total, $size = 30, $label = '')
    {
        return self::showStatusBar($done, $total, $size, $label);
    }

    public static function showStatusBar($done, $total, $size = 30, $label = '')
    {
        //  static $start_time;

        $label      = self::truncateString($label, 45, true);

        // if we go over our bound, just ignore it
        if ($done > $total)
        {
            echo \PHP_EOL;

            return 0;
        }

        //   if(empty($start_time)) $start_time=time();
        //   $now = time();

        $perc       = (float) ($done / $total);

        $bar        = floor($perc * $size);

        $status_bar = "\r[".$label;
        $status_bar .= ' '.number_format($done).'/'.number_format($total).' ';

        $str_len    = \strlen($status_bar);
        $size       = $size - $str_len;
        $bar        = floor($perc * $size);
        if ($bar < 1)
        {
            $bar = 0;
        }
        $status_bar .= str_repeat('=', $bar);
        if ($bar < $size)
        {
            $status_bar .= '>';
            $status_bar .= str_repeat(' ', $size - $bar);
        } else
        {
            $status_bar .= '=';
        }

        $disp       = number_format($perc * 100, 0);

        $status_bar .= "] {$disp}%";
        echo $status_bar;

        // flush();

        // when done, send a newline
        if ($done == $total || 0 == $done)
        {
            echo \PHP_EOL;

            return 0;
        }
    }

    public static function geturl($string)
    {
        $array = explode('"', $string);

        return $array[1];
    }

    public static function getkey($string)
    {
        $array = explode('/', $string);

        return $array[6];
    }

    public static function wrapimplode($array, $before = '', $after = '', $separator = '')
    {
        if (!$array)
        {
            return '';
        }

        return $before.implode("{$after}{$separator}{$before}", $array).$after;
    }

    public static function translate($text, $sep = '_')
    {
        return $text;
        /*
                        $parts = explode($sep, $text);
                        foreach ($parts as $t)
                        {
                            $out         = exec('trans -b -no-auto -no-ansi '.$t);
                            $t_p         = explode(',', trim($out));
                            $stringArr[] = $t_p[0];
                        }

                        return implode($sep, $stringArr);
                        */
    }
}

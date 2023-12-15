<?php
/**
 * Command like Metatag writer for video files.
 */

namespace MLSC\Utilities;

class Timer extends MLSCStopWatch
{
    public static $logProc        = false;

    public static function startwatch($input, $output, $options = [])
    {
        parent::init($input, $output);
        if (array_key_exists('log', $options))
        {
            parent::$writeNow = $options['log'];
        }
        if (array_key_exists('display', $options))
        {
            parent::$display = $options['display'];
        }
    }

    public static function getTrace($id = 0)
    {
        $trace         = debug_backtrace();

        if (__FILE__ == $trace[$id]['file'])
        {
            ++$id;
        }

        $logger_class  = explode("\\", __LOGGER_CLASS__);
        rsort($logger_class);

        if (str_contains($trace[$id]['file'], $logger_class[1]))
        {
            ++$id;
        }

        $filename      = basename($trace[$id]['file']);
        $file          = str_pad($filename, 18, ' ');
        $file          = parent::formatPrint($file, ['red']);
        $lineNo        = $trace[$id]['line'];
        $line          = str_pad($lineNo, 4, ' ');
        $line          = parent::formatPrint($line, ['yellow']);
        self::$logProc = trim($id.$filename).':'.$lineNo;

        return [$file, $line];
    }

    public static function watch($text = 'Watch Timer', $var = null)
    {
        [$file, $line ] = self::getTrace();
        $text           = str_pad($text, 18, ' ');
        $text           = parent::formatPrint($text, ['blue']);
        parent::dump($file.':'.$line.':'.$text, $var);
    }

    public static function startLap($text = 'lap', $_ = '')
    {
        [$file, $line ] = self::getTrace();
        $text           = trim(parent::formatPrint($text, ['blue']));
        $logText        = $file.':'.$line.':'.$text;
        parent::lap($logText, null);
    }

    public static function watchlap($text = 'lap', $var = null)
    {
        $text    = trim(parent::formatPrint($text, ['cyan']));
        $logText = "\t".$text;
        parent::lap($logText, $var);
    }

    public static function stopwatch($text = 'stop', $var = null)
    {
        parent::stop($text, $var);
    }
}

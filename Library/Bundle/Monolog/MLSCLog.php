<?php
/**
 * Command like Metatag writer for video files.
 */

namespace MLSC\Bundle\Monolog;

use Monolog\Logger;
use MLSC\Utilities\Timer;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;

class MLSCLog
{
    protected $logger;

    protected $disabled;

    protected $processor;

    private static $_objects;

    private static $_output;

    /**
     * Summary of _depth.
     */
    private static $_depth;

    public function __construct($channel = 'default', $disabled = false)
    {
        if (!defined('__LOGGER_CLASS__'))
        {
            define('__LOGGER_CLASS__', __CLASS__);
        }

        $error_file      = __LOGFILE_DIR__.'/'.$channel.'.log';
        $this->disabled  = $disabled;
        $this->processor = new IntrospectionProcessor();
        $stream          = new RotatingFileHandler($error_file, 3, Logger::INFO);
        $stream->setFormatter(new LineFormatter("[%datetime%] %message% %context%\n", 'g:i:s.v', true));
        //        $stream->setFormatter(new LineFormatter("%message% %context%\n", null, true));

        $this->logger    = new Logger($channel);
        $this->logger->pushHandler($stream);
    }

    public static function __callStatic($method, $args)
    {
        if (method_exists(Timer::class, $method))
        {
            $message = '';
            $context = null;

            if (key_exists('0', $args))
            {
                $message = $args[0];
            }
            if (key_exists(1, $args))
            {
                $context = $args[1];
            }

            Timer::$method($message, $context);
            self::logNotice($message, $context);
        }
    }

    /**
     * Method init.
     *
     * @param $channel $channel [explicite description]
     */
    public static function init($channel = 'default'): object
    {
        $logger = new self($channel);
        $logger->logger->pushProcessor($logger->processor);

        return $logger;
    }

    /**
     * Method logError.
     *
     * @param $message $message [explicite description]
     * @param $context $context [explicite description]
     */
    public static function logError($message, $context = [])
    {
        self::init()->log(Logger::ERROR, $message, $context);
    }

    /**
     * Method logWarning.
     *
     * @param $message $message [explicite description]
     * @param $context $context [explicite description]
     */
    public static function logWarning($message, $context = [null])
    {
        self::init()->log(Logger::WARNING, $message, $context);
    }

    /**
     * Method logNotice.
     *
     * @param $message $message [explicite description]
     * @param $context $context [explicite description]
     */
    public static function logNotice($message, $context = [null])
    {
        self::init()->log(Logger::NOTICE, $message, $context);
    }

    /**
     * Method logger.
     *
     * @param $message $message [explicite description]
     * @param $context $context [explicite description]
     */
    public static function logger($message, $context = null)
    {
        //Timer::watch($message, $context);
        self::logNotice($message, $context);
    }

    /**
     * Method logInfo.
     *
     * @param $message $message [explicite description]
     * @param $context $context [explicite description]
     */
    public static function logInfo($message, $context = [])
    {
        self::init()->log(Logger::INFO, $message, $context);
    }

    /**
     * Method LogDebug.
     *
     * @param $message $message [explicite description]
     * @param $context $context [explicite description]
     * @param $channel $channel [explicite description]
     */
    public static function LogDebug($message, $context = [], $channel = 'debug')
    {
        self::init($channel)->log(Logger::INFO, $message, $context);
    }

    /**
     * Method dump.
     *
     * @param $var       $var [explicite description]
     * @param $depth     $depth [explicite description]
     * @param $highlight $highlight [explicite description]
     */
    public static function dump($var, $depth = 3, $highlight = false)
    {
        self::$_output  = '';
        self::$_objects = [];
        self::$_depth   = $depth;
        self::dumpInternal($var, 0);

        return self::$_output;
    }

    /**
     * Method log.
     *
     * @param $level   $level [explicite description]
     * @param $message $message [explicite description]
     * @param $context $context [explicite description]
     */
    protected function log($level, $message, $context)
    {
        $message        = timer::formatPrint(self::dump($message), ['blue']);

        if (false != Timer::$clock)
        {
            $pcs     = explode('- ', Timer::$clock);
            $message = $pcs[1].' '.$message;
        }

        [$file, $line ] = timer::getTrace(2);
        $message        =  $file.':'.$line.';'.$message;

        $context        = self::dump($context);
        $this->logger->addRecord($level, (string) $message, (array) $context);
    }

    /**
     * Method getMessage.
     */

    /*
         private static function getMessage($message)
        {
            if (\is_array($message) || \is_object($message))
            {
                $message = json_encode($message);
            }

            dd(Timer::$clock);
            return str_replace("\n", ' ', Timer::$clock. " ".$message);
        }

        /**
         * Method getContext.
         *
         * @param $context $context [explicite description]
         */

    /*
         private static function getContext($context)
        {
            if (\is_string($context) || \is_bool($context))
            {
                $context = [$context];
            }

            if (\is_object($context))
            {
                $context = json_decode(json_encode($context), true);
            }

            return str_replace("\n", ' ', $context);
        }

        /**
         * Method dumpInternal.
         *
         * @param $var   $var [explicite description]
         * @param $level $level [explicite description]
         */

    private static function dumpInternal(mixed $var, int $level)
    {
        switch (\gettype($var))
        {
            case 'boolean':
                self::$_output .= $var ? 'true' : 'false';

                break;

            case 'integer':
                self::$_output .= "{$var}";

                break;

            case 'double':
                self::$_output .= "{$var}";

                break;

            case 'string':
                self::$_output .= "{$var}";

                break;

            case 'resource':
                self::$_output .= '{resource}';

                break;

            case 'NULL':
                self::$_output .= 'null';

                break;

            case 'unknown type':
                self::$_output .= '{unknown}';

                break;

            case 'array':
                if (self::$_depth <= $level)
                {
                    self::$_output .= 'array(...)';
                } elseif (true === empty($var))
                {
                    self::$_output .= 'array()';
                } else
                {
                    $keys   = array_keys($var);
                    $spaces = str_repeat(' ', $level * 4);
                    self::$_output .= 'array'.$spaces.'(';
                    foreach ($keys as $key)
                    {
                        self::$_output .= "\n".$spaces." [{$key}] => ";
                        self::$_output .= self::dumpInternal($var[$key], $level + 1);
                    }

                    self::$_output .= "\n".$spaces.')';
                }

                break;

            case 'object':
                if (($id = array_search($var, self::$_objects, true)) !== false)
                {
                    self::$_output .= $var::class.'#'.($id + 1).'(...)';
                } elseif (self::$_depth <= $level)
                {
                    self::$_output .= $var::class.'(...)';
                } else
                {
                    $id        = array_push(self::$_objects, $var);
                    $className = $var::class;
                    $members   = (array) $var;
                    $keys      = array_keys($members);
                    $spaces    = str_repeat(' ', $level * 4);
                    self::$_output .= "{$className}#{$id}\n".$spaces.'(';
                    foreach ($keys as $key)
                    {
                        $keyDisplay = strtr(trim($key), ["\0" => ':']);
                        self::$_output .= "\n".$spaces." [{$keyDisplay}] => ";
                        self::$_output .= self::dumpInternal($members[$key], $level + 1);
                    }

                    self::$_output .= "\n".$spaces.')';
                }

                break;

            default:
                break;
        }
    }
}

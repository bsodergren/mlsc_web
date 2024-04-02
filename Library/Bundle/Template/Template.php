<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Bundle\Template;

use MLSC\Traits\TemplateCallbacks;
use MLSC\Bundle\Template\Utils\Utilities;
use MLSC\Bundle\Template\Utils\HTMLDevice;

class Template
{
    use TemplateCallbacks;

    public static $static_html;
    public static $static_params      = [];

    public $html                      = '';
    public $header_html               = '';
    public $default_params            = [];
    public $templatefile;
    public $params                    = 0;
    private $TemplateLoc              = '';

    public $error                     = true;

    public static $StaticTemplateLoc  = '';

    public const VAR_CALLBACK         =   '|%%(\w+)%%|i';

    public const INCLUDE_CALLBACK     = '|{{include=([a-zA-Z\/]+)(,(\w+)=\|(.{0,})\|)?}}|i';
    public const FUNCTION_CALLBACK     = '|{{function=([a-zA-Z_]+)(.*)?}}|i';

    public const INPUT_CALLBACK       = '|(<input name=")([a-z_]+)(:.*)(>)|i';
    public const SELECT_CALLBACK      = '|(<select name=")([_[a-z]+)(:.*class=")(.*)(".*>)(</select>)|i';
    public const SPAN_CALLBACK        = '|(<span for=")([a-z_]+)(".*>)(</span>)|i';
    public const COLORPICKER_CALLBACK = '|(<span.*id="color_picker".*>)(</span>)|i';

    public function __construct($template_directory = '')
    {
        $this->TemplateLoc       = $template_directory;
        self::$StaticTemplateLoc = $template_directory;
    }

    public static function Load($template, $array)
    {
        self::$static_html .= (new self())->loadTemplate($template);
        self::$static_params = array_merge(self::$static_params, $array);
    }

    public static function GetHTML($template = '', $array = [], $error = true)
    {
        $template_obj               = new self();
        $template_obj->error        = $error;
        $template_obj->templatefile = $template;
        $template_obj->template($template, $array);

        return $template_obj->html;
    }

    public static function echo($template = '', $array = [], $error = true)
    {
        echo self::GetHTML($template, $array, $error);
    }

    public function clear()
    {
        $this->html = '';
    }

    public function return($template = '', $array = [])
    {
        if ($template)
        {
            $this->template($template, $array);
        }

        $html = $this->html;
        $this->clear();

        return $html;
    }

    public function render($template = '', $array = [])
    {
        echo $this->return($template, $array);
    }

    public function loadTemplate($template)
    {
        $template_file = HTMLDevice::getTemplateFile($template);

        if (null !== $template_file)
        {
            return file_get_contents($template_file).\PHP_EOL;
        }

        if (true == $this->error)
        {
            $template_text = '<h1>NO TEMPLATE FOUND<br>';
            $template_text .= 'FOR <pre>'.$template.'</pre></h1> <br>';
        } else
        {
            $template_text = '';
        }
        //        $template_text = '<!-- END OF '.$template.'-->'.\PHP_EOL;

        return $template_text.\PHP_EOL;
    }

    private function defaults($text)
    {
        preg_match_all('/%%([A-Z_]+)%%/m', $text, $output_array);
        $params               = [];

        foreach ($output_array[1] as $n => $def)
        {
            if (Utilities::isSet($def))
            {
                $params[$def] = \constant($def);
            }
        }

        $this->default_params = $params;
    }

    private function parse($text, $params = [])
    {
        $this->defaults($text);
        $params       = array_merge($params, $this->default_params);

        $this->params = $params;

        if (\is_array($params))
        {
            $text = preg_replace_callback(self::VAR_CALLBACK, [$this, 'callback_replace'], $text);
        }

        $text         = preg_replace_callback(self::INCLUDE_CALLBACK, [$this, 'callback_include_html'], $text);

        $text         = preg_replace_callback(self::FUNCTION_CALLBACK, [$this, 'callback_parse_function'], $text);
        // $text         = preg_replace_callback(self::GETEFFECTS_MENU, [$this, 'callback_getEffects_html'], $text);
        $text         = preg_replace_callback(self::INPUT_CALLBACK, [$this, 'callback_input_html'], $text);
        $text         = preg_replace_callback(self::SELECT_CALLBACK, [$this, 'callback_selectbox_html'], $text);
        $text         = preg_replace_callback(self::SPAN_CALLBACK, [$this, 'callback_span_html'], $text);
        $text         = preg_replace_callback(self::COLORPICKER_CALLBACK, [$this, 'callback_colorpicker_html'], $text);

        return $text;
    }

    public function template($template, $params = [])
    {
        $template_text = $this->loadTemplate($template);
        $html          = $this->parse($template_text, $params);

        $this->add($html);

        return $html;
    }

    public function add($var)
    {
        if (\is_object($var))
        {
            $this->html .= $var->html;
        } else
        {
            $this->html .= $var;
        }
    }

    public static function javaRefresh($url, $timeout = 0, $msg = '')
    {
        // $url = str_replace(__URL_PATH__, '', $url);
        // $url = __URL_PATH__.'/'.$url;
        $url          = str_replace('//', '/', $url);

        if ('' != $msg)
        {
            $sep = '?';
            if (\is_array($msg))
            {
                foreach ($msg as $key => $value)
                {
                    $url_array[] = $key.'='.urlencode($value);
                }
                $url_params = implode('&', $url_array);
                $url        = $url.$sep.$url_params;
            } else
            {
                $msg = urlencode($msg);
                if (str_contains($url, '?'))
                {
                    $sep = '&';
                }

                $url = $url.$sep.'msg='.$msg;
            }
        }

        // if ($timeout > 0) {
        //     $timeout = $timeout * 1000;
        //     $update_inv = $timeout / 100;
        //     Template::echo('progress_bar', ['SPEED' => $update_inv]);
        // }
        $params       = ['_URL' => $url, '_SECONDS' => $timeout];
        $refresh_html = "<script> setTimeout(function () { window.location.href = '%%_URL%%'; }, %%_SECONDS%%); </script>";

        foreach ($params as $key => $value)
        {
            $key          = '%%'.strtoupper($key).'%%';
            $refresh_html = str_replace($key, $value, $refresh_html);
        }
        echo $refresh_html;
    }
}

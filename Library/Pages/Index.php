<?php

namespace MLSC\Pages;

use MLSC\Pages\Pages;
use MLSC\Bundle\Template\Template;

class Index extends Pages
{
    public static function run()
    {
        
       
        self::Render(['BODY' => '']);

    }
}

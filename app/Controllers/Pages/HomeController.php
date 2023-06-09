<?php

namespace App\Controllers\Pages;

use App\Utils\View;

class HomeController extends PageController{

    /**
     * @return string
     */
    public static function getHome() : string
    {
        $content = View::render('home/home', [
            'name' => 'teste'
        ]);

        return parent::getPage('Home', $content);
    }
}

?>
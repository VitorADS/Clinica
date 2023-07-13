<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Utils\View;

class ComponentsController extends PageController{

    /**
     * @param string $path
     * @param string $type
     * @param string $value
     * @param string $id
     * @return string
     */
    public static function createButton(string $path, string $type, string $acao, string $id = '', string $name = '', string $value = '', string $typeButton = ''): string
    {
        return View::render('components/button', [
            'path' => $path,
            'type' => $type,
            'acao' => $acao,
            'value' => $value,
            'id' => $id,
            'name' => $name,
            'typeButton' => $typeButton
        ]);
    }

    /**
     * @param string $type
     * @param string $value
     * @return string
     */
    public static function createSubmitButton(string $type, string $value): string
    {
        return View::render('components/submitButton', [
            'type' => $type,
            'value' => $value,
        ]);
    }

    /**
     * @param string $value
     * @return string
     */
    public static function createDisabledButton(string $type, string $value): string
    {
        return View::render('components/disabledButton', [
            'type' =>$type,
            'value' => $value,
        ]);
    }
}

?>
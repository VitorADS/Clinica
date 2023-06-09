<?php

namespace App\Controllers\Pages;

use App\Http\Request;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;

class PageController{

    /**
     * @return string
     */
    private static function getHeader(): string
    {
        return View::render('page/header');
    }

    /**
     * @return string
     */
    private static function getFooter(): string
    {
        return View::render('page/footer');
    }

    /**
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage(string $title, string $content) : string
    {
        return View::render('page/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }

    /**
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    public static function getPagination(Request $request, Pagination $pagination): string
    {
        $pages = $pagination->getPages();

        if(count($pages) <=1 ) return '';

        $links = '';
        $url = $request->getRouter()->getCurrentUrl();
        $queryParams = $request->getQueryParams();

        foreach($pages as $page){
            $queryParams['page'] = $page['page'];
            $link = $url . '?' . http_build_query($queryParams);

            $links .= View::render('page/pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        return View::render('page/pagination/box', [
            'links' => $links
        ]);
    }
}

?>
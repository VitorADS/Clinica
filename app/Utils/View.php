<?php
    namespace App\Utils;

    class View{

        /**
         * @var array
         */
        private static $vars = [];

        /**
         * @param array $vars
         */
        public static function init($vars = [])
        {
            self::$vars = $vars;
        }

        /**
         * @param $view
         * @return string
         */
        public static function getContentView(string $view): string
        {
            $file = __DIR__ . '/../../src/views/' . $view . '.html';

            return file_exists($file) ? file_get_contents($file) : '';
        }

        /**
         * @param string $view
         * @param array $vars (string/numeric)
         * @return string
         */
        public static function render(string $view, array $vars = []): string
        {
            $contentView = self::getContentView($view);

            $vars = array_merge(self::$vars, $vars);

            $keys = array_keys($vars);
            $keys = array_map(function($item){
                return '{{'.$item.'}}';
            }, $keys);

            return str_replace($keys, array_values($vars), $contentView);
        }
    }
?>
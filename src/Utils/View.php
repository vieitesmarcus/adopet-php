<?php

namespace Adopet\Utils;

class View
{
    //Variáveis padrões da View
    private static array $vars = [];

    //Método para definir os dados iniciais da classe
    public static function init(array $vars = []): void
    {
        self::$vars = $vars;
    }

    //Método por retornar o conteudo de uma view
    private static function getContent(string $view): string
    {
        $file = __DIR__ . '/../View/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    //Método responsável por retornar o conteudo renderizado da view

    public static function render(string $view, array $vars = []): string
    {
        //conteudo da view
        $contentView = self::getContent($view);

        $vars = array_merge(self::$vars, $vars);

        //CHAVE DO ARRAY DE VARIÁVEIS
        $keys = array_keys($vars);

        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);
        
    }
}
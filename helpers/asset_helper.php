<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: nijhu
 * Date: 04-Mar-17
 * Time: 10:31 PM
 */

/*
 * Dynamically add Javascript files to header page
 */
if(!function_exists('header_js')){
    function header_js($file=''){
        loaderCssJs('header_js', $file);
    }
}
/*
 * Dynamically add CSS files to header page
 */
if(!function_exists('header_css')){
    function header_css($file=''){
        loaderCssJs('header_css', $file);
    }
}
/*
 * Dynamically call the js/css loader function for header on individual page
 */
if(!function_exists('loaderCssJs')){
    function loaderCssJs($function_name, $file){
        $str = '';
        $item = '';
        if(is_array($file)){
            if($function_name == 'header_css'){
                foreach($file AS $item){
                    if(strpos($item, 'https://') !== false || strpos($item, 'http://') !== false){
                        $str .= '<link rel="stylesheet" href="'.$item.'" type="text/css" />'."\n";
                    }else{
                        $str .= '<link rel="stylesheet" href="'.base_url().'asset/'.$item.'" type="text/css" />'."\n";
                    }
                }
            }
            if($function_name == 'header_js'){
                foreach($file AS $item){
                    if(strpos($item, 'https://') !== false || strpos($item, 'http://') !== false){
                        $str .= '<script type="text/javascript" src="'.$item.'"></script>'."\n";
                    }else{
                        $str .= '<script type="text/javascript" src="'.base_url().'asset/'.$item.'"></script>'."\n";
                    }
                }
            }
        }else{
            if($function_name == 'header_css'){
                if(strpos($item, 'https://') !== false || strpos($item, 'http://') !== false){
                    $str .= '<link rel="stylesheet" href="'.$item.'" type="text/css" />'."\n";
                }else{
                    $str .= '<link rel="stylesheet" href="'.base_url().'asset/'.$item.'" type="text/css" />'."\n";
                }
            }
            if($function_name == 'header_js'){
                if(strpos($item, 'https://') !== false || strpos($item, 'http://') !== false){
                    $str .= '<script type="text/javascript" src="'.$item.'"></script>'."\n";
                }else{
                    $str .= '<script type="text/javascript" src="'.base_url().'asset/'.$item.'"></script>'."\n";
                }
            }
        }
        echo $str;
    }
}
/*
 * Dynamically add Javascript files to header page
 */
if(!function_exists('footer_js')){
    function footer_js($file=''){
        CssJsConfig('footer_js', $file);
    }
}
/*
 * Dynamically add CSS files to header page
 */
if(!function_exists('footer_css')){
    function footer_css($file=''){
        CssJsConfig('footer_css', $file);
    }
}
/*
 * Dynamically call the js/css loader function for footer
 */
if(!function_exists('CssJsConfig')) {
    function CssJsConfig($function_name, $file){
        $str = '';
        $ci = &get_instance();
        $items = $ci->config->item($function_name);
        if (empty($file)) {
            return;
        }
        if (is_array($file)) {
            if (!is_array($file) && count($file) <= 0) {
                return;
            }
            foreach ($file AS $item) {
                $items[] = $item;
            }
            $ci->config->set_item($function_name, $items);
        } else {
            $str = $file;
            $items[] = $str;
            $ci->config->set_item($function_name, $items);
        }
    }
}
/*
 *  Loading all css and js function in header or footer
 *  like load_resource('header')
 *  like load_resource('footer')
 *
 */
if(!function_exists('load_resource')){
    function load_resource($param = 'header'){
        $str = '';
        $ci = &get_instance();
        if($param == 'footer'){
            $css = $ci->config->item('footer_css');
            $js  = $ci->config->item('footer_js');
        }else{
            $css = $ci->config->item('header_css');
            $js  = $ci->config->item('header_js');
        }
        foreach($css AS $item){
            if(strpos($item, 'https://') !== false || strpos($item, 'http://') !== false){
                $str .= '<link rel="stylesheet" href="'.$item.'" type="text/css" />'."\n";
                $str .= '<link rel="stylesheet" href="'.$item.'" type="text/css" />'."\n";
            }else{
                $str .= '<link rel="stylesheet" href="'.base_url().'asset/'.$item.'" type="text/css" />'."\n";
            }
        }
        $str .= '<link rel="newest stylesheet" href="'.base_url().'asset/css/apsis_style.css" type="text/css" />'."\n";
        foreach($js AS $item){
            if(strpos($item, 'https://') !== false || strpos($item, 'http://') !== false){
                $str .= '<script type="text/javascript" src="'.$item.'"></script>'."\n";
            }else{
                $str .= '<script type="text/javascript" src="'.base_url().'asset/'.$item.'"></script>'."\n";
            }
        }
        return $str;
    }
}
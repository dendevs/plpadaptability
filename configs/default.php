<?php
$root_path = str_replace('configs', '', dirname(__FILE__));

$root_url = '';
if(! empty($_SERVER['HTTPS']) && ! empty($_SERVER['HTTP_HOST']) && ! empty($_SERVER['REQUEST_URI']) ) {
    $protocol = ( $_SERVER['HTTPS'] && $_SERVER['HTTPS'] !== 'off' ) ? 'https://' : 'http://';
    $tmp = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $root_url = substr($tmp, 0, strpos($tmp, 'src/'));
}
return array( 
    'root_path' => $root_path,
    'log_path' => $root_path . 'logs/',
    'config_path' => $root_path . 'configs/',
    'service_config_path' => $root_path . 'configs/',
    'assets_path' => $root_path . 'assets/',
    'js_path' => $root_path . 'assets/js/',
    'js_url' => $root_url . 'assets/js/',
    'img_path' => $root_path . 'assets/img/',
    'css_path' => $root_path . 'assets/css/',
    'test' => 'test value config ok'
);


<?php


/**
 * AJAX
 * ----
 */

Route::post($config->manager->slug . '/ajax/(:any):(:all)', function($action = "", $kind = "") use($config, $speak) {
    $segment = 'ajax';
    $P = array('data' => Request::post());
    include strpos($kind, '/') === false ? DECK . DS . 'workers' . DS . 'unit.ajax.' . $action . '.' . $kind . '.php' : ROOT . DS . File::path($kind);
    exit;
});
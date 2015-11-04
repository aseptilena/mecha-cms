<?php

class Config {

    protected static $bucket = array();
    protected static $o = array();

    /**
     * =============================================================
     *  SET CONFIGURATION DATA
     * =============================================================
     *
     * -- CODE: ----------------------------------------------------
     *
     *    Config::set('foo', 'bar');
     *
     * -------------------------------------------------------------
     *
     *    Config::set(array(
     *        'a' => 1,
     *        'b' => 2
     *    ));
     *
     * -------------------------------------------------------------
     *
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *  Parameter | Type   | Description
     *  --------- | ------ | ---------------------------------------
     *  $key      | string | Key of data to be called
     *  $key      | array  | Array of data's key and value
     *  $value    | mixed  | The value of your data key
     *  --------- | ------ | ---------------------------------------
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *
     */

    public static function set($key, $value = "") {
        if(is_object($key)) $key = Mecha::A($key);
        if(is_object($value)) $value = Mecha::A($value);
        $cargo = array();
        if( ! is_array($key)) {
            Mecha::SVR($cargo, $key, $value);
        } else {
            foreach($key as $k => $v) {
                Mecha::SVR($cargo, $k, $v);
            }
        }
        Mecha::extend(self::$bucket, $cargo);
    }

    /**
     * =============================================================
     *  GET CONFIGURATION DATA BY ITS KEY
     * =============================================================
     *
     * -- CODE: ----------------------------------------------------
     *
     *    echo Config::get('url');
     *
     * -------------------------------------------------------------
     *
     *    echo Config::get('index')->slug;
     *
     * -------------------------------------------------------------
     *
     *    echo Config::get('index.slug');
     *
     * -------------------------------------------------------------
     *
     *    $config = Config::get();
     *
     *    echo $config->url;
     *    echo $config->index->slug;
     *
     * -------------------------------------------------------------
     *
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *  Parameter | Type   | Description
     *  --------- | ------ | ---------------------------------------
     *  $key      | string | Key of data to be called
     *  $fallback | mixed  | Fallback value if data does not exist
     *  --------- | ------ | ---------------------------------------
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *
     */

    public static function get($key = null, $fallback = false) {
        if(is_null($key)) {
            return Mecha::O(self::$bucket);
        }
        if(is_string($key) && strpos($key, '.') !== false) {
            $output = Mecha::GVR(self::$bucket, $key, $fallback);
            return is_array($output) ? Mecha::O($output) : $output;
        }
        return array_key_exists($key, self::$bucket) ? Mecha::O(self::$bucket[$key]) : $fallback;
    }

    /**
     * =============================================================
     *  REMOVE CONFIGURATION DATA BY ITS KEY
     * =============================================================
     *
     * -- CODE: ----------------------------------------------------
     *
     *    Config::reset();
     *
     * -------------------------------------------------------------
     *
     *    Config::reset('foo');
     *
     * -------------------------------------------------------------
     *
     */

    public static function reset($key = null) {
        if( ! is_null($key)) {
            Mecha::UVR(self::$bucket, $key);
        } else {
            self::$bucket = array();
        }
        return new static;
    }

    /**
     * =============================================================
     *  MERGE MORE ARRAY TO SPECIFIC CONFIGURATION ITEM
     * =============================================================
     *
     * -- CODE: ----------------------------------------------------
     *
     *    Config::merge('speak', array('cute' => 'manis'));
     *
     * -------------------------------------------------------------
     *
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *  Parameter | Type   | Description
     *  --------- | ------ | ---------------------------------------
     *  $key      | string | Key of data to be infected
     *  $array    | array  | The data you want to use to infect
     *  --------- | ------ | ---------------------------------------
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *
     */

    public static function merge($key, $value = array()) {
        self::set($key, $value);
    }

    // Show the added method(s)
    public static function kin($kin = null, $fallback = false) {
        $c = get_called_class();
        if( ! is_null($kin)) {
            return isset(self::$o[$c][$kin]) ? self::$o[$c][$kin] : $fallback;
        }
        return ! empty(self::$o[$c]) ? self::$o[$c] : $fallback;
    }

    // Add new method with `Config::plug('foo')`
    public static function plug($kin, $action) {
        self::$o[get_called_class()][$kin] = $action;
    }

    // Call the added method or use them as a shortcut for the default `get` method.
    // Example: You can use `Config::foo()` as a shortcut for `Config::get('foo')` as
    // long as `foo` is not defined yet by `Config::plug()`
    public static function __callStatic($kin, $arguments = array()) {
        $c = get_called_class();
        if( ! isset(self::$o[$c][$kin])) {
            $fallback = false;
            if(count($arguments) > 0) {
                $kin .= '.' . array_shift($arguments);
                $fallback = array_shift($arguments);
            }
            return self::get($kin, $fallback);
        }
        return call_user_func_array(self::$o[$c][$kin], $arguments);
    }

}
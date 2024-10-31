<?php

namespace OrielIO;

/**
 * Class Cache  - Oriel Cache, implemented using WP Options API
 */
class Cache
{
    private $_options;
    private static $_instance;

    /**
     * Cache constructor Initializes private options container, singleton object
     */
    private function __construct()
    {
        $this->_options = get_option("oriel_lite_cache");
        if(!$this->_options)
            $this->_options = array();
    }

    /**
     * @param mixed $key Key to be stored
     * @param mixed $value Value to be stored
     * @param int $expire Expiry time in seconds
     * @param bool $commit True to commit to DB, useful for batched sets
     */
    public function set($key, $value, $expire = 0, $commit = true)
    {
        $this->_options[$key . '_k'] = $value;
        if ($expire)
            $this->_options[$key . '_e'] = time() + $expire;
        else
            $this->_options[$key . '_e'] = 0;

        if ($commit)
            update_option("oriel_lite_cache", $this->_options);

        return true;
    }

    /**
     * @param mixed $key Key to be fetched
     * @param mixed $default_value Default value
     * @return mixed Value found in cache or default value if passed or null
     */
    public function get($key, $default_value = null)
    {
        $key_key = $key . '_k';
        $exp_key = $key . '_e';
        if (array_key_exists($key_key, $this->_options) && array_key_exists($exp_key, $this->_options) &&
            (time() < $this->_options[$exp_key] || !$this->_options[$exp_key])
        ) {
            return $this->_options[$key_key];
        }

        return $default_value;
    }

    /**
     * @param mixed $key Key to be deleted
     * @param bool $commit True to commit to DB, useful for batched deletes
     * @return bool True if key found
     */
    public function delete($key, $commit = true)
    {
        $key_key = $key . '_k';
        $exp_key = $key . '_e';
        $result = false;
        if (array_key_exists($key_key, $this->_options) && array_key_exists($exp_key, $this->_options)) {
            unset($this->_options[$key_key]);
            unset($this->_options[$exp_key]);
            $result = true;
        }

        if ($commit)
            update_option("oriel_lite_cache", $this->_options);

        return $result;
    }

    /**
     * Clears all cache
     */
    public function erase()
    {
        delete_option("oriel_lite_cache");
        $this->_options = array();
    }

    /**
     * @return Cache Singleton instance
     */
    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Prevents object cloning
     */
    private function __clone()
    {
    }

    /**
     * Prevents deserialization
     */
    private function __wakeup()
    {
    }

}

global $oriel_cache;

$oriel_cache = Cache::instance();

?>

<?php
namespace BOTK\Core;
/*
 * PHP 5.3 allows the creation of an inheritable Singleton class via late static binding:
 * see: http://stackoverflow.com/questions/203336/creating-the-singleton-design-pattern-in-php5
 * discussion
 * 
 */
class Singleton
{
    private static $instances = array();
    protected function __construct() {}
    private function __clone() {}
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        $cls = get_called_class(); // late-static-bound class name
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }
}
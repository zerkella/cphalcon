<?php
// Massive variable, stored in Phalcon property
Mem::start();
$string = str_repeat('a', 5000000);
Mem::report('Creating string for external call test: %s');

Mem::start();
$rawValue = new Phalcon\Db\RawValue($string);
Mem::report('Creating Phalcon DB RawValue: %s');

Mem::start();
$tmp = $rawValue->getValue();
Mem::report('Getting Phalcon string back: %s');

Mem::start();
$tmp2 = $rawValue->getValue();
Mem::report('Getting Phalcon string back again: %s');

echo "\n";

// Massive variable, retrieved via internal call in Phalcon
Mem::start();
$string3= str_repeat('a', 5000000);
Mem::report('Creating string for internall call test: %s');

Mem::start();
$modelsManagerDummy = new ModelsManagerDummy($string3);
Mem::report('Creating ModelsManagerDummy: %s');

$di = new Phalcon\DI();
$model = new Phalcon_Collection($di, $modelsManagerDummy);

Mem::start();
$tmpConn = $model->getConnectionService();
Mem::report('Getting string back via internal call: %s');

Mem::start();
$tmpConn2 = $model->getConnectionService();
Mem::report('Getting string back again via internall call: %s');

echo "\n";

// Massive variable, stored in user-space class property
Mem::start();
$string2 = str_repeat('a', 5000000);
Mem::report('Creating string for user-space call: %s');

Mem::start();
$phpRawValue = new PhpRawValue($string2);
Mem::report('Creating PhpRawValue: %s');

Mem::start();
$tmpPhp = $phpRawValue->getValue();
Mem::report('Getting PHP string back: %s');

Mem::start();
$tmpPhp2 = $phpRawValue->getValue();
Mem::report('Getting PHP string back again: %s');


//---Functions--------------------------------------------
class Mem
{
    protected static $_start;

    public static function start()
    {
        self::$_start = memory_get_usage();
    }

    public static function report($message)
    {
        $current = memory_get_usage();
        $difference = ($current - self::$_start) / 1000000;
        echo sprintf($message, sprintf('%.2fmb', $difference));
        echo "\n";
    }
}

class PhpRawValue
{
    protected $_value;

    public function __construct($value)
    {
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }
}

class ModelsManagerDummy
{
    protected $_value;

    public function initialize()
    {

    }

    public function __construct($value)
    {
        $this->_value = $value;
    }

    public function getConnectionService()
    {
        return $this->_value;
    }
}

class Phalcon_Collection extends \Phalcon\Mvc\Collection {
}


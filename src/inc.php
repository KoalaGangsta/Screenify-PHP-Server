<?php
if (!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

define('PATH', dirname(__FILE__) . DS);
define('MPATH', PATH . 'model' . DS);
define('CPATH', PATH . 'controller' . DS);

include_once(PATH . 'core.php');
include_once(PATH . 'config.php');


core::$REQUESTS = $_GET + $_POST + $_FILES;
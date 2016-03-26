<?php
class Core
{
    public static $models = array();
    public static $controllers = array();

    public static $config = array();

    public static $REQUESTS;

    public static function initialize($config)
    {
        self::$config = $config;

        include_once(MPATH . 'interface.php');

        foreach(self::$config['models'] as $modelName => $modelConfig)
        {
            self::loadModel($modelName, $modelConfig);
            self::consoleLog('Model "' . $modelName . '" loaded.');
        }
    }

    public static function loadModel($modelName, $config=array())
    {
        $fileName = $modelName . '.php';
        if (file_exists(MPATH . $fileName))
        {
            include_once(MPATH . $modelName . '.php');

            $className = $modelName . 'Model';
            if (class_exists($className))
            {
                if (!isset($config['autoLoad']))
                    $config['autoLoad'] = true;

                if (!isset($config['autoRun']))
                    $config['autoLoad'] = false;

                if($config['autoLoad'])
                    self::$models[$modelName] = new $className($config);

                if($config['autoRun'])
                    self::$models[$modelName]->run();
            }
            else
            {
                self::errorMessage('Class <u>' . $className . '</u> does not exist.');
            }
        }
        else
        {
            self::errorMessage('Model-file <u>' . $fileName . '</u> does not exist.');
        }
    }

    public static function errorMessage($msg)
    {
        die('<b>Error:</b> ' . $msg);
    }

    public static function consoleLog($msg)
    {
        echo '<script>console.log("'.addslashes($msg).'");</script>';
    }
}
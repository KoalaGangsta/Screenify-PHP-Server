<?php
class core
{
    public static $models = array();
    public static $controllers = array();

    public static $REQUESTS;

    public static function LoadModule($moduleName, $config=array())
    {
        if (file_exists(MPATH . $moduleName . '.php'))
        {
            if (class_exists($moduleName . 'Model'))
            {

            }
            else
            {
                self::ErrorMessage('Class <u>' . $moduleName . 'Model</u> does not exist.');
            }
        }
        else
        {
            self::ErrorMessage('Model-file <u>' . $moduleName . '.php</u> does not exist.');
        }
    }

    public static function ErrorMessage($msg)
    {
        die('<b>Error:</b> ' . $msg);
    }
}
<?php
if (@!class_exists(core))
    die();

$config = array(
    'debug' => true,

    'requirements' => array(
        'user' => false,
        'filetype' => array('jpg','jpeg')
    ),

    'useDB' => 'mysqli',

    'models' => array(
        'mysqli' => array(
            'autoLoad' => true,
            'autoRun' => true,

            'server' => 'localhost',
            'user' => 'root',
            'pw' => '',
            'db' => 'screenify'
        ),

        'auth' => array(
            'autoLoad' => true,
            'autoRun' => true
        )
    )
);
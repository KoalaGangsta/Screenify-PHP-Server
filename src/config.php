<?php
if (@!class_exists(core))
    die();

$cfg = array(
    'requirements' => array(
        'user' => false,
        'filetype' => array('jpg','jpeg')
    ),

    'useDB' => 'mysqli',

    'models' => array(
        'mysqli' => array(
            'autoLoad' => true,
            'autoRun' => false,

            'server' => 'localhost',
            'user' => 'root',
            'pw' => '',
            'db' => 'screenify'
        ),

        'upload' => array(
            'autoLoad' => true,
            'autoRun' => false,

            'path' => PATH . 'p' . DS
        )
    )
);
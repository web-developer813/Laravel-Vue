<?php

return array(

    'pdf' => array(
        'enabled' => true,
        // 'binary'  => '/usr/local/bin/wkhtmltopdf',
        'binary'  => (env('APP_ENV') == 'production') ? base_path() . '/vendor/bin/wkhtmltopdf-amd64' : '/usr/local/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    // 'image' => array(
    //     'enabled' => true,
    //     'binary'  => '/usr/local/bin/wkhtmltoimage',
    //     'timeout' => false,
    //     'options' => array(),
    //     'env'     => array(),
    // ),

);

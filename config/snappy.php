<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary' => base_path('public/rendering-engine/wkhtmltopdf/bin/wkhtmltopdf.exe'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => base_path('public/rendering-engine/wkhtmltopdf/bin/wkhtmltoimage.exe'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
<?php

return [
    // 'adminEmail' => 'slavavitrenko@gmail.com',
    // 'supportEmail' => 'slavavitrenko@gmail.com',
    'adminEmail' => getenv('SMTP_LOGIN'),
    'supportEmail' => getenv('SMTP_LOGIN'),
    'language' => 'uk-UA',
    'sourceLanguage' => 'en-US',
    'uploadPath'    =>  "../uploads/",
];

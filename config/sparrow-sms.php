<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Token generated from website
    |--------------------------------------------------------------------------
    |
    | This configuration option defines the token number that will be used as
    | the "token" number for all outgoing text messages. You should provide
    | the token you have already reserved within your Sparrow dashboard.
    |
    */

    "token" => env("SPARROW_SMS_TOKEN"),

    /*
    |--------------------------------------------------------------------------
    | SMS "From" Number
    |--------------------------------------------------------------------------
    |
    | This configuration option defines the phone number that will be used as
    | the "from" number for all outgoing text messages. You should provide
    | the number you have already reserved within your Sparrow dashboard.
    |
    */

    "from" => env("SPARROW_SMS_FROM"),
];

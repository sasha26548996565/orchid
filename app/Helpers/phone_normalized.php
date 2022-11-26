<?php

use Propaganistas\LaravelPhone\PhoneNumber;

if (! function_exists('phone_normalized'))
{
    function phone_normalized(string $phone): string
    {
        return str_replace('+', '', PhoneNumber::make($phone, 'RU')->formatE164());
    }
}

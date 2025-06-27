<?php

function containsSpecialCharacters($string)
{
    return preg_match('/[\'":;<>\/?+\-=_)(*&^%$#@!~`|]/', $string);
}

function removeSpecialCharacters($string)
{
    return preg_replace('/[\'":;{}[\]=_\-+?\/.,<>`~]/', '', $string);
}

function sanitize($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function getCurrentDate()
{
    date_default_timezone_set('Asia/Jakarta');
    return date('Y-m-d');
}

function getCurrentTime()
{
    date_default_timezone_set('Asia/Jakarta');
    return date('H:i:s');
}

function getCurrentMount()
{
    date_default_timezone_set('Asia/Jakarta');
    return date('m');
}
function getCurrentYear()
{
    date_default_timezone_set('Asia/Jakarta');
    return date('Y');
}

<?php

function numberAddPlus($string)
{
//    if(strlen($string) == 11)
//        return '+' . $string;
//    else
//        return '+370' . substr($string, 1, strlen($string) - 1);
    return '+370' . $string;
}

function numberChangeCode($string)
{
//    if(strlen($string) == 11)
//        return '8' . substr($string, 3, strlen($string) - 3);
//    else
//        return $string;
    return '8' . $string;
}
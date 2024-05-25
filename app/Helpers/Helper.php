<?php


function Numberformat($number)
{
    return number_format($number, 0,',','.');
}

function DateFormat($date, $format = "D-MM-Y HH:m:s")
{
    return \Carbon\Carbon::parse($date)->isoFormat($format);
}
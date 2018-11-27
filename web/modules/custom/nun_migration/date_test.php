<?php

date_default_timezone_set('America/New_York'); // set to Eastern
print_r(date_default_timezone_get());

$input = '2017-05-08 00:00:00';

$date = new DateTime($input);


//2013-03-03T04:00:00
//$date->setTimezone(new DateTimeZone());
print_r($date);
$formatted = $date->format('Y-m-d\TG:H:s');
print $formatted."\n";

print gmdate('Y-m-d\TH:i:s',$date->getTimestamp());

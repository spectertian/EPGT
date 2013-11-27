#!/usr/bin/php
<?php
//print_r($argc);
//print_r($argv);

if($argc == 2) {
    $date = $argv[1];
} else {
    $date = date('Y-m-d');
}

$week = date('w', strtotime($date));

if ($week == 0) {
    $week = 7;
}

$times = 60 * 60 * 24;

for ($i = $week; $i < 8; ++$i) {
    $n = $i - $week;
    $d = date('Y-m-d', strtotime($date) + $n * $times);
    echo ($i == 1 ? '' : "\n") . $d . "\n";
    exec('../../symfony tv:program program ' . $d);
}

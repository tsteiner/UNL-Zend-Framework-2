#!/usr/bin/php
<?php

$root = realpath(__DIR__ . '/..');
$pyrus = escapeshellarg($root . '/bin/pyrus.phar');
$args = array();
foreach ($_SERVER['argv'] as $i => $arg) {
    if ($i == 0) {
        continue;
    }
    $args[] = escapeshellarg($arg);
}
$root = escapeshellarg($root);
$args = implode(' ', $args);
passthru("php $pyrus $root $args");


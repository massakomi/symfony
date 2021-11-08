<?php

// config/packages/prod/monolog.php
use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog) {

	var_dump(11); exit;
    // this "file_log" key could be anything
    $monolog->handler('file_log')
        ->type('stream')
        // log to var/logs/(environment).log
        ->path('%kernel.logs_dir%/dd.log')
        // log *all* messages (debug is lowest level)
        ->level('debug');

    $monolog->handler('syslog_handler')
        ->type('syslog')
        // log error-level messages and higher
        ->level('error');
};


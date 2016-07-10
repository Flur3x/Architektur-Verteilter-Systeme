<?php

require_once('class/Logger.class.php');
require_once('class/ServerRestarter.class.php');

$logger = new Logger();
$restarter = new ServerRestarter();

if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
  error_log("All servers will be restarted ...");
  $restarter->restartAllServers();
} else {
  error_log("Restart triggered by " . $_SERVER['REMOTE_ADDR']);
}

$entries = $logger->resetLog();
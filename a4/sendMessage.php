<?php

require_once('HTTP/Request2.php');
require_once('class/Logger.class.php');
require_once('class/IPListHandler.class.php');

$fileHandler = new FileHandler();
$ipListHandler = new IPListHandler();
$myIP = $_SERVER['SERVER_ADDR'];
$isSystemMessage = false;
$isClientMessage = false;
$isServerMessage = false;
$loopActive = false;
$sender = 0;
$displayedSender = $sender;

if (isset($_REQUEST['message']) && isset($_REQUEST['timestamp'])) {
  $message = $_REQUEST['message'];
  $timestam = $_REQUEST['timestamp'];
}

if (isset($_REQUEST['sender'])) {
  $isServerMessage = true;
  $sender = $_REQUEST['sender'];

  if ($sender === $myIP) {
    $loopActive = false;
  } else {
    $loopActive = true;
  }
} else {
  $myIP = $ipListHandler->getMyIP();
  $isClientMessage = true;
  $loopActive = true;
  $sender = $myIP;
}

if (isset($_REQUEST['system']) && $_REQUEST['system'] === 'true') {
  $isSystemMessage = true;
  $displayedSender = "System";
} else {
  $displayedSender = $ipListHandler->getNameForIP($sender);
}

if ($loopActive) {
  $nextIP = $ipListHandler->getMyNextNeighborsIP();

  if ($nextIP !== $myIP) {

    $entry = array(
      'sender' => $displayedSender,
      'message' => $_REQUEST['message'],
      'timestamp' => $_REQUEST['timestamp']
    );

    $logger = new Logger();
    $logger->log($entry);

    try {
      $request = new HTTP_Request2('http://' . $nextIP . '/Architektur-Verteilter-Systeme/a4/sendMessage.php');
      $request->setMethod(HTTP_Request2::METHOD_POST)
        ->addPostParameter(array('message' => $_REQUEST['message'], 'timestamp' => $_REQUEST['timestamp'], 'sender' => $sender, 'system' => $isSystemMessage ? 'true' : 'false'));
      $request->send();
    } catch (Exception $exc) {
      echo $exc->getMessage();
      error_log("sendMessage.php: " . $nextIP . " konnte nicht aufgerufen werden.");
    }
  }
}
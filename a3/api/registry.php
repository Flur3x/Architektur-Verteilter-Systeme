<?php

require_once('HTTP/Request2.php');
require_once('../class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();

if (!empty($_REQUEST['name'])) {
  $name = $_REQUEST['name'];

  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    addToIpList($name, $ip);
} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    addToIpList($name, $ip);
  } else {
    user_error("IP konnte nicht ermittelt werden.");
  }
}

function addToIpList($name, $ip) {
  global $ipListHandler;
  $ipList = $ipListHandler->getList();

  $ipList[$ip] = array(
    'name' => $name,
    'IP' => $ip
  );

  error_log('Server registriert: ' . $ipList[$ip]['name'] . ' ' . $ipList[$ip]['ip']);

  $ipListHandler->update($ipList);

  $response = array(
    'name' => $name,
    'IP' => $ip
  );

  json_encode($response);
  triggerNeighborNotifications();
}

function triggerNeighborNotifications() {
  global $ipListHandler;
  $myIP = $ipListHandler->getMyIP();

  try {
    $request = new HTTP_Request2('http://' . $myIP . '/Architektur-Verteilter-Systeme/a3/yourNeighbor.php');
    $request->setMethod(HTTP_Request2::METHOD_POST);
    $request->send();
  } catch (Exception $exc) {
    echo $exc->getMessage();
  }
}
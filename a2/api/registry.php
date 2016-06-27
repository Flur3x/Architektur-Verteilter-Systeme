<?php

require_once('../class/FileHandler.class.php');

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
} else {
  var_dump(http_response_code(409));
}

function addToIpList($name, $ip) {
  $fileName = '../persistence/iplist.txt';
  $fileHandler = new FileHandler();

  $ipList = $fileHandler->deserialize($fileName);

    $ipList[$ip] = array (
      'Name' => $name,
      'IP' => $ip
    );

    $fileHandler->serialize($fileName, $ipList);

  if (count($ipList) > 0) {
    echo json_encode($ipList);
    var_dump(http_response_code(200));
  } else {
    var_dump(http_response_code(505));
  }
}
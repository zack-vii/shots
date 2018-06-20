<?php

//
// Gianluca.Moro@unipd.it
//

require_once('config.php');
require_once('externalCmds.php');

// load Twig library
require_once './web/js/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./web');
$twig = new Twig_Environment($loader);

//
// main entry point
//

$remoteIpAddress = $_SERVER['REMOTE_ADDR'];

buildHome();

function buildHome() {
  global $twig;
  global $remoteIpAddress;
  global $isDebug;
  global $statusDefinitions;
  global $dbFilename;
  global $dbShots;
  global $isAllowed;

  $isAllowed   = ipIsAllowed($remoteIpAddress);

  $dbShots = new SQLite3($dbFilename);

  $date    = is_null($_GET["date"])?date('Y-m-d'):$_GET["date"];
  $refresh = is_null($_GET["refresh"])?  0    :   $_GET["refresh"];

  $shotDate    = date('ymd', strtotime($date));
  $listOfShots = getListOfShots($shotDate);
  $listOfExpts = getListOfExpts($shotDate);

  $messageStr = "";
  $messageSubStr = "";
  $errorStr = "";
  if ($_POST['copy'] == 'copySelectedShots') {
    if ($isAllowed) {
      $theChecked = checkChecked($_POST, $listOfExpts, $listOfShots);
      if (count($theChecked) > 0) {
        $messageStr = "Upload request sent!";
        for ($i=0; $i<count($theChecked); $i++)
          $messageSubStr = $messageSubStr . " (".$theChecked[$i][0] . ", " . $theChecked[$i][1] . ") ";
        for ($i=0; $i<count($theChecked); $i++)
          saveShot($theChecked[$i][0], $theChecked[$i][1]);
      }
    } else {
      $errorStr = "You are not allowed to do that!";
    }
  } else {
    if (array_key_exists("info",$_GET)) {
      $info     = explode("_",$_GET['info']);
      $errorStr = getInfo($info[0],$info[1]);
    }
  }
  $template = $twig->loadTemplate('home.phtml');
  $tableOfStatus = getTableOfStatus($listOfExpts, $listOfShots);
  checkTableOfStatus($tableOfStatus, count($listOfExpts), count($listOfShots));
  $params = array(
    'statusDefinitions' => $statusDefinitions,
    'remoteIpAddress' => $remoteIpAddress,
    'errorStr' => $errorStr,
    'messageStr' => $messageStr,
    'messageSubStr' => $messageSubStr,
    'date' => $date,
    'refresh' => $refresh,
    'shotDate' => $shotDate,
    'listOfExpts' => $listOfExpts,
    'listOfShots' => $listOfShots,
    'tableOfStatus' => $tableOfStatus,
    'theChecked' => $theChecked,
  );
  $template->display($params);
}

function ipIsAllowed($remoteIp) {
   global $ipAllowedToCopy;
   if (count($ipAllowedToCopy) == 0) {
     return true;
   }
   if (in_array($remoteIp, $ipAllowedToCopy)) {
     return true;
   }
   return false;
}

function checkChecked($posted, $listOfExpts, $listOfShots) {
  $res = array();
  for($e=0; $e<count($listOfExpts); $e++) {
    for($s=0; $s<count($listOfShots); $s++) {
      if (array_key_exists("check".$e."x".$s, $posted)) {
        array_push($res, array($listOfExpts[$e], $listOfShots[$s]));
      }
    }
  }
  return $res;
}


function checkTableOfStatus($table, $exptCount, $shotCount) {
  for ($e=$exptCount; $e-->0;) {
    for ($s=0; $s<$shotCount; $s++) {
      $val = $table[$e][$s];
      if  (!is_null($val)) {
        if ($val < 0 or $val > 5) {
          $index = 4;
        } else {
          $index = $val;
        }
        $table[$e][$s] = $index;
      }
    }
  }
}

?>

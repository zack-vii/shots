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
  if (count($_POST) == 0) {
    $isPosted = false;
  } else {
    $isPosted = true;
  }

  if ($isPosted) {
    $date    = is_null($_POST["date"])?date('Y-m-d'):$_POST["date"];
    $refresh = is_null($_POST["refresh"])?  0    :$_POST["refresh"];
  } else {
    $date   = date('Y-m-d');
    $refresh= 0;
  }

  $shotDate    = date('ymd', strtotime($date));
  $listOfShots = getListOfShots($shotDate);
  $listOfExpts = getListOfExpts($shotDate);
  if ($isAllowed && array_key_exists('subject', $_REQUEST) && ($_REQUEST['subject'] == 'copySelectedShots')) {
    $theChecked = checkChecked($_POST, $listOfExpts, $listOfShots);
    if (isAllowed) {
      for ($i=0; $i<count($theChecked); $i++) {
        saveShot($theChecked[$i][0], $theChecked[$i][1]);
      }
    }
  } else {
    $theChecked = array();
  }

  $tableOfStatus = getTableOfStatus($listOfExpts, $listOfShots);

  $template = $twig->loadTemplate('home.phtml');
  $messageStr = "";
  $messageSubStr = "";
  $errorStr = "";
  if (count($theChecked) > 0) {
    if (ipIsAllowed($remoteIpAddress)) {
      $messageStr = "Upload request sent!";
      for ($k=0; $k<count($theChecked); $k++) {
        $messageSubStr = $messageSubStr . " (".$theChecked[$k][0] . ", " . $theChecked[$k][1] . ") ";
      }
    } else {
      $errorStr = "You are not allowed to check from IP: " . $remoteIpAddress;
    }
  }


  $tableOfStatusUI = addUIDataToTableOfStatus($tableOfStatus, count($listOfExpts), count($listOfShots));

  $params = array(
    'isDebug' => $isDebug,
    'statusDefinitions' => $statusDefinitions,
    'remoteIpAddress' => $remoteIpAddress,
    'errorStr' => $errorStr,
    'messageStr' => $messageStr,
    'messageSubStr' => $messageSubStr,
    'title' => 'Status of shots',
    'isPosted' => $isPosted,
    'date' => $date,
    'refresh' => $refresh,
    'shotDate' => $shotDate,
    'listOfExpts' => $listOfExpts,
    'listOfShots' => $listOfShots,
    'tableOfStatusUI' => $tableOfStatusUI,
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


function addUIDataToTableOfStatus($table, $exptCount, $shotCount) {
  global $statusDefinitions;
  $ret = [];
  for ($e=0; $e<$exptCount; $e++) {
    for ($s=0; $s<$shotCount; $s++) {
      $val = $table[$e][$s];
      if  (!is_null($val)) {
        if ($val < 0 or $val > 5) {
          $index = 4;
        } else {
          $index = $val;
        }
        $ret[$e][$s] = $statusDefinitions[$index];
      }
    }
  }
  return $ret;
}

?>

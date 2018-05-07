<?php

//
// Gianluca.Moro@unipd.it
//

require_once('config.php');
require_once('externalCmds.php');
require_once('web/js/liblog.php');

// load Twig library
require_once './web/js/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./web');
$twig = new Twig_Environment($loader);
$logger = new Logger($logFileName, $logLevel);

//
// main entry point
//

$listOfTrees = getListOfTrees();

$remoteIpAddress = $_SERVER['REMOTE_ADDR'];

buildHome();


//
//
//

function buildHome() {
  global $twig;
  global $listOfTrees;
  global $mainW7XTreeName;
  global $remoteIpAddress;
  global $isDebug;
  global $statusDefinitions;

  if (count($_POST) == 0) {
    $isPosted = false;
  } else {
    $isPosted = true;
  }

  if ($isPosted) {
    $dateToShow = is_null($_POST["dateToShow"])?date('Y-m-d'):$_POST["dateToShow"];
  } else {  
    $dateToShow = date('Y-m-d');
  }

  //var_dump($_POST["dateToShow"]);

  $shotDate = date('ymd', strtotime($dateToShow));
  $listOfShots = getListOfShots($mainW7XTreeName, $shotDate);

  if (array_key_exists('subject', $_REQUEST) && ($_REQUEST['subject'] == 'copySelectedShots')) {
    $theChecked = checkChecked($_POST, $listOfTrees, $listOfShots);
    //echo("<pre>"); var_dump($theChecked); exit;
    if (ipIsAllowed($remoteIpAddress)) {
      for ($i=0; $i<count($theChecked); $i++) {
        saveShot($theChecked[$i][0], $theChecked[$i][1]);
      }
    }
  } else {
    $theChecked = array();
  }

  $tableOfStatus = getTableOfStatus($listOfTrees, $listOfShots);

  $template = $twig->loadTemplate('home.phtml');
  $messageStr = "";
  $messageSubStr = "";
  $errorStr = "";
  if (count($theChecked) > 0) {
    if (ipIsAllowed($remoteIpAddress)) {
      $messageStr = "Copy request sent!";
      for ($k=0; $k<count($theChecked); $k++) {
        $messageSubStr = $messageSubStr . " (".$theChecked[$k][0] . ", " . $theChecked[$k][1] . ") ";
      }
    } else {
      $errorStr = "You are not allowed to copy from IP: " . $remoteIpAddress;
    }
  }


  $tableOfStatusUI = addUIDataToTableOfStatus($tableOfStatus, count($listOfTrees), count($listOfShots));
  //echo("<pre>"); var_dump($tableOfStatusUI); exit;

  //var_dump($dateToShow);
  //var_dump($shotDate);

  $params = array(
    'isDebug' => $isDebug,
    'statusDefinitions' => $statusDefinitions,
    'remoteIpAddress' => $remoteIpAddress,
    'errorStr' => $errorStr,
    'messageStr' => $messageStr,
    'messageSubStr' => $messageSubStr,
    'title' => 'Status of shots',
    'isPosted' => $isPosted,
    'dateToShow' => $dateToShow, 
    'shotDate' => $shotDate,
    'listOfTrees' => $listOfTrees,
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

function checkChecked($posted, $listOfTrees, $listOfShots) {
  $res = array();
  for($d=0; $d<count($listOfTrees); $d++) {
    for($s=0; $s<count($listOfShots); $s++) {
      if (array_key_exists("check".$d."x".$s, $posted)) {
        array_push($res, array($listOfTrees[$d], $listOfShots[$s]));
      }
    }
  } 
  return $res;
}


function addUIDataToTableOfStatus($table, $treeCount, $shotCount) {
  global $statusDefinitions;
  $ret = [];
  for ($t=0; $t<$treeCount; $t++) {
    for ($s=0; $s<$shotCount; $s++) {  
      $val = $table[$t][$s];
      $index = 0;
      if ($val <= 0) { $index = 0; }
      if ($val == 1) { $index = 1; }
      if ($val == 2) { $index = 2; }
      if ($val >= 3) { $index = 3; }
      $ret[$t][$s] = $statusDefinitions[$index];
    }
  }
  return $ret;
}

?>
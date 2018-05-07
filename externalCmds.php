<?php

//
// Gianluca.Moro@unipd.it
//

//
// The list of trees comes from an external command
//
function getListOfTrees() {
  $output = shell_exec('w7x_gettrees');
  if (strpos($output, "/w7x/vault/shotdb2.db") === 0) {
    $output = substr($output, strlen("/w7x/vault/shotdb2.db"));
  }
  return explode(" ", trim($output));
}


//
// get the status of all the shots given, for all the given trees
//
function getTableOfStatus($listOfTrees, $listOfShots) {
  global $dbShots;
  $res = array();

  for ($tree=0; $tree<count($listOfTrees); $tree++) {
    for ($st=0; $st<count($listOfShots); $st++) {
      $results = $dbShots->query('SELECT stat FROM shotdb WHERE shot='.$listOfShots[$st].' AND expt="'.$listOfTrees[$tree].'";');

      $res[$tree][$st] = 0;
      if ($results != false) {
        $row = $results->fetchArray();
        if (is_array($row) && count($row)>0) {
           $res[$tree][$st] = $row[0];
	}
      }
    }
  }

  return $res;
}


//
// check a shot (only if the old status is 1)
//
function saveShot($tree, $shot) {
  // shell_exec('sdb_check ' . $tree . " " . $shot);
  global $dbShots;

  $results = $dbShots->query('SELECT stat FROM shotdb WHERE shot='.$shot.' AND expt="'.$tree.'";');

  if ($results != false) {
    $row = $results->fetchArray();
    if (is_array($row) && count($row)>0) {
      $status = $row[0];      
      if ($status == 1) {
	$dbShots->query('UPDATE shotdb SET stat=2 WHERE shot='.$shot.' AND expt="'.$tree.'";');
      }
    }
  }
}


//
// get the list of shots (from directory listing)
//
function getListOfShots($dirName, $shotDate) {
  global $shotsDirectory;
  $fullPath = $shotsDirectory . $dirName;
  $output = shell_exec('ls ' . $fullPath . "/*" . $shotDate . "???.tree");

  if (is_null($output)) {
    return array();
  }

  $outFiles = explode("\n", $output);
  $outFilesNames = array();
  $prefixLength = strlen($fullPath)+1+4;
  for ($i=0; $i<count($outFiles); $i++) {
     if (strlen($outFiles[$i])>$prefixLength) {
       array_push($outFilesNames, substr($outFiles[$i], $prefixLength, 9));
     }
  }

  return $outFilesNames;
}


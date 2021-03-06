<?php

//
// Gianluca.Moro@unipd.it
//


//
// get the status of all the shots given, for all the given expts
//
function getTableOfStatus($listOfExpts, $listOfShots) {
  global $dbShots;
  $res = array();

  for ($ex=0; $ex<count($listOfExpts); $ex++) {
    for ($st=0; $st<count($listOfShots); $st++) {
      $results = $dbShots->query('SELECT stat FROM shotdb WHERE shot='.$listOfShots[$st].' AND expt="'.$listOfExpts[$ex].'";');
      $res[$ex][$st] = NULL;
      if ($results != false) {
        $row = $results->fetchArray(SQLITE3_NUM);
        if (is_array($row) && count($row)>0) {
           $res[$ex][$st] = $row[0];
        }
      }
    }
  }

  return $res;
}

//
// get the info of a given expt and shot
//
function getInfo($expt, $shot) {
  global $dbShots;
  $results = $dbShots->query('SELECT info FROM shotdb WHERE shot='.$shot.' AND expt="'.$expt.'";');
  if ($results != false) {
    $row = $results->fetchArray(SQLITE3_NUM);
    if (is_array($row) && count($row)>0)
      return $row[0];
  }
  return "no entry";
}


//
// check a shot (only if the old status is 1)
//
function saveShot($tree, $shot) {
  // shell_exec('sdb_check ' . $tree . " " . $shot);
  global $dbShots;

  $results = $dbShots->query('SELECT stat FROM shotdb WHERE shot='.$shot.' AND expt="'.$tree.'";');
  if ($results != false) {
    $row = $results->fetchArray(SQLITE3_NUM);
    if (is_array($row) && count($row)>0) {
      $status = $row[0];-  $results = $dbShots->query('SELECT stat FROM shotdb WHERE shot='.$shot.' AND expt="'.$tree.'";');
    }
  }
}


//
// get the list of shots (from shotdb)
//
function getListOfShots($shot) {
  global $dbShots;
  $res = array();
  $results = $dbShots->query('SELECT DISTINCT shot FROM shotdb WHERE shot>'.$shot.'000 AND shot<='.$shot.'999;');
  if ($results != false) {
    while ($row = $results->fetchArray(SQLITE3_NUM)) {
      array_push($res,$row[0]);
    }
  }
  return $res;
}

//
// get the list of expts (from shotdb)
//
function getListOfExpts($shot) {
  global $dbShots;
  global $mainExptName;
  $res = array();
  array_push($res,$mainExptName);
  $results = $dbShots->query('SELECT DISTINCT expt FROM shotdb WHERE expt!="'.$mainExptName.'" AND shot>'.$shot.'000 AND shot<='.$shot.'999 ORDER BY expt;');
  if ($results != false) {
    while ($row = $results->fetchArray(SQLITE3_NUM)) {
      array_push($res,$row[0]);
    }
  }
  return $res;
}

//
// get the list of expts (from shotdb)
//
function getListOfDays() {
  global $dbShots;
  $res = array();
  $results = $dbShots->query("SELECT DISTINCT CAST(shot/'1000' AS int) AS day FROM shotdb %s ORDER BY day ASC;");
  if ($results != false) {
    while ($row = $results->fetchArray(SQLITE3_NUM)) {
      array_push($res,$row[0]);
    }
  }
  return $res;
}



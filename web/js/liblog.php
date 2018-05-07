<?php

/*
 * logging class
 */
class Logger {

  var $fileName;
  var $level;

  function Logger($fileName, $level=10) {
    $this->fileName = $fileName;
    $this->level = $level;
  }

  function getLevel() {
    return $this->level;
  }

  function setLevel($level) {
    $this->level = $level;
  }

  function log($msg) {
    $today = date("Y-m-d\TG:i:sT"); 
    $myfile = fopen($this->fileName, "a+");
    if (isset($myfile)) {
      $n = fwrite($myfile, $today . ": " . $_SERVER['REQUEST_URI'] . ": " . $msg . "\n");
      fclose($myfile);
    }
  }
}


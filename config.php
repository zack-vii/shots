<?php

//
// Gianluca.Moro@unipd.it
//

$isDebug = false;

$ipAllowedToCopy = ["10.44.4.10"]; // empty list: all

$mainExptName = "w7x";

$statusDefinitions = [
// value in db, color, checkable, comment
  [ 0, 'lightgray', false, "Storing",  "currently running?" ],
  [ 1, 'orange',    true,  "Ready",    "store phase complete" ],
  [ 2, 'lime',      false, "Checked",  "scheduled for upload" ],
  [ 3, 'yellow',    false, "Uploading","currently uploading" ],
  [ 4, 'red',       false, "Error",    "upload failed" ],
  [ 5, 'green',     false, "Uploaded", "upload successful" ]
];

$dbFilename = "/w7x/.db/shotdb2.db";

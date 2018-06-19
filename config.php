<?php

//
// Gianluca.Moro@unipd.it
//

$isDebug = false;

$ipAllowedToCopy = ["10.44.4.10"]; // empty list: all

$mainExptName = "w7x";

$statusDefinitions = [
// value in db, color, checkable, comment
  [ 0, 'lightgray', false, "Storing",  "currently running shot?" ],
  [ 1, 'orange',    true,  "Ready",    "acquisition complete" ],
  [ 2, 'lime',      false, "Checked",  "scheduled for upload" ],
  [ 3, 'yellow',    false, "Uploading","currently uploaded" ],
  [ 4, 'red',       false, "Error",    "upload failed" ],
  [ 5, 'green',     false, "Uploaded", "upload successfully" ]
];

$dbFilename = "/w7x/.db/shotdb2.db";

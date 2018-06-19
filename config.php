<?php

//
// Gianluca.Moro@unipd.it
//

$isDebug = false;

$ipAllowedToCopy = ["10.44.4.10"]; // empty list: all

$mainExptName = "w7x";

$statusDefinitions = [
// value in db, color, checkable, comment
  [ 0, 'lightgray', false, "Storing: registered but currently running shot" ],
  [ 1, 'orange',    true,  "Ready: acquisition complete" ],
  [ 2, 'lawngreen', false, "Checked: ready to be uploaded" ],
  [ 3, 'yellow',    false, "Uploading: a service is currently uploading shot" ],
  [ 4, 'red',       false, "Error: upload failed - requires user assist" ],
  [ 5, 'green',     false, "Uploaded: upload completed successfully" ]
];

$dbFilename = "/w7x/.db/shotdb2.db";

// logging/errors configuration
$logFileName = "logs/log1.txt";
$logLevel = 10;

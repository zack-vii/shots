<?php

//
// Gianluca.Moro@unipd.it
//

$isDebug = false;

$ipAllowedToCopy = []; // empty list: all

$shotsDirectory = "/w7x/vault/";

$mainW7XTreeName = "w7x";

$statusDefinitions = [
// value in db, color, checkable, comment
  [ 0, 'lightgray', false, "Storing: registered but currently running shot" ],
  [ 1, 'orange',    true,  "Ready: acquisition complete" ],
  [ 2, 'lawngreen', false, "Checked: ready to be uploaded" ],
  [ 3, 'yellow',    false, "Uploading: a service is currently uploading shot" ],
  [ 4, 'red',       false, "Error: upload failed - requires user assist" ],
  [ 5, 'green',     false, "Uploaded: upload completed successfully" ]
];

$dbFilename = "/w7x/shotdb/shotdb2.db";
$dbShots = new SQLite3($dbFilename);

$listOfTrees = [
 'w7x','qmb','qmc','qme','qmf','qmrv1','qmrv2','qmrw','qmr1','qmr2','qmr8','qoc','qoi','qrn',
 'qrp','qsh','qsk','qsl','qso','qsr10a','qsr10b','qsr11a','qsr11b','qsr20a',
 'qsr20b','qsr21a','qsr21b','qsr30a','qsr31a','qsr31b','qsr40a','qsr40b','qsr41a','qsr41b',
 'qsr51a','qsr02','qsw','qsx','qxt1','qxt2','qxt3','qxt4'
];

// logging/errors configuration
$logFileName = "logs/log1.txt";
$logLevel = 10;

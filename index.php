<?php

require_once 'lib/DbConf.class.php';
require_once 'lib/DbSync.class.php';

$user = 'root';
$pass = 'yourpasswordhere';
$host = 'localhost';
$sourceDbName      = 'piiqa_invesp';
$destinationDbName = 'pii_invesp';

$sourceDb      = new DbConf($user, $pass, $host, $sourceDbName);
$destinationDb = new DbConf($user, $pass, $host, $destinationDbName);

print_r(DBSync::getDbDifferences($sourceDb,$destinationDb ));


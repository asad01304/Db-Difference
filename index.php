<?php

require_once 'lib/DbConf.class.php';
require_once 'lib/DbSync.class.php';

$user = 'root';
$pass = 'yourpasswordhere';
$host = 'localhost';
$sourceDbName      = 'source_db_name';
$destinationDbName = 'destination_db_name';

$sourceDb      = new DbConf($user, $pass, $host, $sourceDbName);
$destinationDb = new DbConf($user, $pass, $host, $destinationDbName);

print_r(DBSync::getDbDifferences($sourceDb,$destinationDb ));


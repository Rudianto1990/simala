<?php
require 'vendor/autoload.php';
require 'app/Config/Database.php';

$db = (new \Config\Database())->connect();
$result = $db->table('tbl_monitoring_alat')->select('*')->where('nomor_asset', 'AST-002')->get()->getResultArray();
echo json_encode($result, JSON_PRETTY_PRINT);

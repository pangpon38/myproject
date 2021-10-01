<?php
// db::setHost('172.24.8.146');
// db::setUser('baac_cloud_63');
// db::setPassword('24i584g534h5q5k4m4k504w274m3i434u446x5r454');
// db::setDBName('baac_cloud_63');
// db::setDBType('MSSQL');
// db::setAutoIncrement("Y");
// db::setLangDate('EN');
db::setHost('127.0.0.1');
db::setUser('root');
db::setPassword('');
db::setDBName('test');
db::setDBType('MYSQL');
db::setAutoIncrement("Y");
db::setLangDate('EN');
db::setRunType('DEV'); //LIVE,DEV

db::setupDatabase();
$WF_URL = "https://www.smartchapa.com/baac_cloud_63/";
$WF_LINE_CLIENT_ID = "f3sCDIwcbTcMLzhtgNJel3"; 
$WF_LINE_CLIENT_SECRET = "EH0YUMcrstZRylzjJqmR1eEY3gwQH0XCX1wqRxo9rnB";

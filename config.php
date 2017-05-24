<?php
/**
 * Config file for project
 */

//Config for database
$mysqli = new mysqli ("localhost", "root", "");

//Set time work limit
set_time_limit (5000);

//Create constant for Google Api Places and database
define('Google_Places_API', 'AIzaSyAhB3jA9QCVZt7j7WUgthU-dAglg_erwVo');
define('Database', 'test');

//Create database and table
$mysqli->query("CREATE DATABASE IF NOT EXISTS ". Database .";");
$mysqli->query("CREATE TABLE IF NOT EXISTS `". Database ."`.`places` (`place_id` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$mysqli->query("ALTER TABLE `". Database ."`.`places` ADD UNIQUE KEY `place_id` (`place_id`);");
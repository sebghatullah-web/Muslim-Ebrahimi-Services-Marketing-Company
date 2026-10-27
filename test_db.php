<?php
require 'app/Core/Config.php';
require 'app/Core/Database.php';
require 'app/Core/Env.php';
require 'config/helpers.php';

\App\Core\Config::load();
$db = \App\Core\Database::connect();
$db->exec("SET NAMES utf8mb4");
$row = $db->query("SELECT title FROM home_sections WHERE section_key='hero'")->fetch();
echo "DB Hero title: " . ($row['title'] ?? 'NOT FOUND') . "\n";
echo "DB Hero title hex: " . bin2hex($row['title'] ?? '') . "\n";
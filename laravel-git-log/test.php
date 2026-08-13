<?php
$pdo = new PDO('sqlite:storage/logs/split_1.db');
$rows = $pdo->query('SELECT * FROM LogLine')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT);

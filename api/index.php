<?php
header('Access-Control-Allow-Origin: https://site-computing-1951-dev-ed.lightning.force.com');
header('Content-Type: application/json; charset=UTF-8');
$response = [
  [
    'id' => 1,
    'name' => '鈴木一郎',
    'department' => '営業本部',
  ],
  [
    'id' => 2,
    'name' => '山田太郎',
    'department' => '営業一課',
  ],
  [
    'id' => 3,
    'name' => '田中次郎',
    'department' => '経理部',
  ],
];

echo json_encode($response);

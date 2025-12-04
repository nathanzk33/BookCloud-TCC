<?php
$pdo = new PDO("mysql:host=localhost;dbname=bookcloud;charset=utf8", "root", "");
$cats = $pdo->query("SELECT id,nome,cor FROM categorias ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cats as $cat) {
    echo $cat["id"] . " - " . $cat["nome"] . " - " . $cat["cor"] . PHP_EOL;
}

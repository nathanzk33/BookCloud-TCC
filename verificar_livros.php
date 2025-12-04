<?php
/**
 * Script para verificar e inserir todos os livros no banco
 * Execute este arquivo para garantir que todos os livros estejam no banco
 */

require_once 'includes/conexao.php';

echo "<h2>Verificando e Inserindo Livros</h2>";

// Primeiro, inserir os 10 livros básicos
require_once 'inserir_livros.php';

echo "<br><br>";

// Depois, inserir os livros extras
require_once 'inserir_livros_extras.php';

echo "<br><br>";

// Verificar total de livros
$stmt = $pdo->query("SELECT COUNT(*) as total FROM livros WHERE disponivel = 1");
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

echo "<h3>Total de livros disponíveis no banco: $total</h3>";

// Listar todos os livros
$stmt = $pdo->query("SELECT id, titulo, autor, disponivel FROM livros ORDER BY titulo");
$todosLivros = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Lista de Livros:</h3>";
echo "<ul>";
foreach ($todosLivros as $livro) {
    $status = $livro['disponivel'] ? '✅' : '❌';
    echo "<li>$status {$livro['titulo']} - {$livro['autor']}</li>";
}
echo "</ul>";


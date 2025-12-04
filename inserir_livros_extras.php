<?php
/**
 * Script para inserir os livros de livros_extras.php no banco de dados
 * Execute este arquivo uma vez através do navegador ou linha de comando
 */

require_once 'includes/conexao.php';
$livrosExtras = include __DIR__ . '/includes/livros_extras.php';

// Mapeamento de categorias por nome para ID
$stmt = $pdo->query("SELECT id, nome FROM categorias");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categoriaMap = [];
foreach ($categorias as $cat) {
    $categoriaMap[strtolower(trim($cat['nome']))] = $cat['id'];
}

// Mapeamento de categorias extras para categorias existentes
$categoriaMapping = [
    'clássicos' => 'Ficção',
    'romance' => 'Ficção',
    'mistério' => 'Ficção',
    'fantasia' => 'Ficção',
    'infantojuvenil' => 'Infantil',
    'satíricos' => 'Ficção',
    'drama' => 'Ficção',
    'suspense' => 'Ficção',
    'juvenil' => 'Infantil'
];

$inseridos = 0;
$atualizados = 0;
$erros = [];

try {
    foreach ($livrosExtras as $livro) {
        // Mapear categoria
        $categoriaNome = strtolower(trim($livro['categoria_nome']));
        $categoriaId = null;
        
        // Tentar mapeamento direto
        if (isset($categoriaMap[$categoriaNome])) {
            $categoriaId = $categoriaMap[$categoriaNome];
        } 
        // Tentar mapeamento indireto
        elseif (isset($categoriaMapping[$categoriaNome])) {
            $categoriaMapeada = $categoriaMapping[$categoriaNome];
            if (isset($categoriaMap[strtolower($categoriaMapeada)])) {
                $categoriaId = $categoriaMap[strtolower($categoriaMapeada)];
            }
        }
        
        // Se não encontrou, usar Ficção como padrão
        if (!$categoriaId) {
            $categoriaId = $categoriaMap['ficção'] ?? 1;
        }
        
        // Criar sinopse a partir da descrição se não houver
        $sinopse = $livro['descricao'] . ' Uma obra que encanta leitores de todas as idades.';
        
        // Verificar se o livro já existe
        $stmt = $pdo->prepare("SELECT id FROM livros WHERE titulo = ?");
        $stmt->execute([$livro['titulo']]);
        $existe = $stmt->fetch();
        
        // Estimar número de páginas baseado no tipo
        $numeroPaginas = 250; // padrão
        if (strpos(strtolower($livro['categoria_nome']), 'infantil') !== false || 
            strpos(strtolower($livro['categoria_nome']), 'juvenil') !== false) {
            $numeroPaginas = 200;
        }
        
        if ($existe) {
            // Atualizar livro existente
            $stmt = $pdo->prepare("
                UPDATE livros SET 
                    autor = ?, 
                    descricao = ?, 
                    sinopse = ?, 
                    preco = ?, 
                    imagem = ?, 
                    categoria_id = ?, 
                    idade_recomendada = ?, 
                    numero_paginas = ?,
                    disponivel = 1
                WHERE titulo = ?
            ");
            $stmt->execute([
                $livro['autor'],
                $livro['descricao'],
                $sinopse,
                $livro['preco'],
                $livro['imagem'],
                $categoriaId,
                $livro['idade_recomendada'],
                $numeroPaginas,
                $livro['titulo']
            ]);
            $atualizados++;
        } else {
            // Inserir novo livro
            $stmt = $pdo->prepare("
                INSERT INTO livros (titulo, autor, descricao, sinopse, preco, imagem, categoria_id, idade_recomendada, numero_paginas, destaque, disponivel) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 1)
            ");
            $stmt->execute([
                $livro['titulo'],
                $livro['autor'],
                $livro['descricao'],
                $sinopse,
                $livro['preco'],
                $livro['imagem'],
                $categoriaId,
                $livro['idade_recomendada'],
                $numeroPaginas
            ]);
            $inseridos++;
        }
    }
    
    // Contar total de livros
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM livros WHERE disponivel = 1");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "✅ Processo concluído!\n";
    echo "📚 Livros inseridos: $inseridos\n";
    echo "🔄 Livros atualizados: $atualizados\n";
    echo "📖 Total de livros disponíveis: $total\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}


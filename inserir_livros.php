<?php
/**
 * Script para inserir 10 livros no banco de dados
 * Execute este arquivo uma vez através do navegador ou linha de comando
 */

require_once 'includes/conexao.php';

// Lista dos 10 livros com todas as informações
$livros = [
    [
        'titulo' => 'O Pequeno Príncipe',
        'autor' => 'Antoine de Saint-Exupéry',
        'descricao' => 'Um clássico da literatura mundial que encanta leitores de todas as idades.',
        'sinopse' => 'A história de um piloto que encontra um pequeno príncipe vindo de outro planeta. Uma obra poética que fala sobre amizade, amor e a essência da vida.',
        'preco' => 29.90,
        'imagem' => 'assets/img/o-pequeno-principe.jpg',
        'categoria_id' => 4, // Infantil
        'idade_recomendada' => 'Todas as idades',
        'numero_paginas' => 96,
        'destaque' => true
    ],
    [
        'titulo' => 'Sapiens: Uma Breve História da Humanidade',
        'autor' => 'Yuval Noah Harari',
        'descricao' => 'Uma análise fascinante da evolução da humanidade desde os primórdios até os dias atuais.',
        'sinopse' => 'Harari examina como o Homo sapiens se tornou a espécie dominante na Terra, explorando as revoluções cognitiva, agrícola e científica que moldaram nossa sociedade.',
        'preco' => 45.90,
        'imagem' => 'assets/img/sapiens.jpg',
        'categoria_id' => 2, // Não-Ficção
        'idade_recomendada' => 'Adulto',
        'numero_paginas' => 443,
        'destaque' => false
    ],
    [
        'titulo' => '1984',
        'autor' => 'George Orwell',
        'descricao' => 'Um dos romances distópicos mais influentes da literatura mundial.',
        'sinopse' => 'Winston Smith vive em um mundo onde o Estado controla todos os aspectos da vida. Uma reflexão sobre poder, liberdade e resistência em uma sociedade totalitária.',
        'preco' => 32.50,
        'imagem' => 'assets/img/1984.jpg',
        'categoria_id' => 1, // Ficção
        'idade_recomendada' => 'Adulto',
        'numero_paginas' => 328,
        'destaque' => true
    ],
    [
        'titulo' => 'O Poder do Hábito',
        'autor' => 'Charles Duhigg',
        'descricao' => 'Um guia prático para entender e transformar hábitos em nossa vida pessoal e profissional.',
        'sinopse' => 'Duhigg explora a ciência por trás dos hábitos e como podemos usá-la para criar mudanças positivas em nossas vidas, desde a produtividade até a saúde.',
        'preco' => 38.90,
        'imagem' => 'assets/img/poder.jpg',
        'categoria_id' => 5, // Autoajuda
        'idade_recomendada' => 'Adulto',
        'numero_paginas' => 408,
        'destaque' => false
    ],
    [
        'titulo' => 'Harry Potter e a Pedra Filosofal',
        'autor' => 'J.K. Rowling',
        'descricao' => 'O primeiro livro da série que conquistou milhões de leitores ao redor do mundo.',
        'sinopse' => 'Harry Potter descobre que é um bruxo e ingressa na Escola de Magia e Bruxaria de Hogwarts, onde viverá aventuras incríveis e descobrirá segredos sobre seu passado.',
        'preco' => 35.90,
        'imagem' => 'assets/img/harry.jpg',
        'categoria_id' => 4, // Infantil
        'idade_recomendada' => 'Jovem/Adulto',
        'numero_paginas' => 223,
        'destaque' => false
    ],
    [
        'titulo' => 'Clean Code',
        'autor' => 'Robert C. Martin',
        'descricao' => 'Um guia essencial para desenvolvedores que desejam escrever código limpo e profissional.',
        'sinopse' => 'Martin apresenta princípios e práticas para escrever código mais legível, manutenível e eficiente, com exemplos práticos e dicas valiosas para programadores.',
        'preco' => 89.90,
        'imagem' => 'assets/img/clean.jpg',
        'categoria_id' => 3, // Técnicos
        'idade_recomendada' => 'Adulto',
        'numero_paginas' => 464,
        'destaque' => false
    ],
    [
        'titulo' => 'Dom Casmurro',
        'autor' => 'Machado de Assis',
        'descricao' => 'Um dos maiores clássicos brasileiros, explorando ciúmes e memórias.',
        'sinopse' => 'Bentinho narra sua história de amor com Capitu, questionando a fidelidade e a natureza humana através de uma narrativa psicológica profunda e envolvente.',
        'preco' => 34.90,
        'imagem' => 'assets/img/domcasmurro.jpg',
        'categoria_id' => 1, // Ficção
        'idade_recomendada' => '12+',
        'numero_paginas' => 256,
        'destaque' => false
    ],
    [
        'titulo' => 'Orgulho e Preconceito',
        'autor' => 'Jane Austen',
        'descricao' => 'Romance atemporal sobre amor, etiqueta social e descobertas pessoais.',
        'sinopse' => 'Elizabeth Bennet e o orgulhoso Sr. Darcy se encontram em uma história de amor que supera preconceitos sociais e revela a verdadeira natureza humana.',
        'preco' => 39.90,
        'imagem' => 'assets/img/orgulho.jpg',
        'categoria_id' => 1, // Ficção
        'idade_recomendada' => '12+',
        'numero_paginas' => 432,
        'destaque' => false
    ],
    [
        'titulo' => 'A Revolução dos Bichos',
        'autor' => 'George Orwell',
        'descricao' => 'Uma fábula política inteligente e indispensável.',
        'sinopse' => 'Os animais de uma fazenda se rebelam contra seus donos humanos, criando uma sociedade igualitária que gradualmente se transforma em uma tirania.',
        'preco' => 28.90,
        'imagem' => 'assets/img/revolucao.jpg',
        'categoria_id' => 1, // Ficção
        'idade_recomendada' => '14+',
        'numero_paginas' => 152,
        'destaque' => true
    ],
    [
        'titulo' => 'O Hobbit',
        'autor' => 'J.R.R. Tolkien',
        'descricao' => 'Uma jornada épica com dragões, anões e muita fantasia.',
        'sinopse' => 'Bilbo Bolseiro, um hobbit pacato, é arrastado para uma aventura épica para recuperar um tesouro guardado por um dragão, descobrindo coragem e amizade pelo caminho.',
        'preco' => 49.90,
        'imagem' => 'assets/img/hobbit.jpg',
        'categoria_id' => 1, // Ficção
        'idade_recomendada' => '10+',
        'numero_paginas' => 320,
        'destaque' => false
    ]
];

try {
    // Limpar livros existentes (opcional - descomente se quiser recriar do zero)
    // $pdo->exec("DELETE FROM livros");
    
    $inseridos = 0;
    $atualizados = 0;
    
    foreach ($livros as $livro) {
        // Verificar se o livro já existe pelo título
        $stmt = $pdo->prepare("SELECT id FROM livros WHERE titulo = ?");
        $stmt->execute([$livro['titulo']]);
        $existe = $stmt->fetch();
        
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
                    destaque = ?,
                    disponivel = 1
                WHERE titulo = ?
            ");
            $stmt->execute([
                $livro['autor'],
                $livro['descricao'],
                $livro['sinopse'],
                $livro['preco'],
                $livro['imagem'],
                $livro['categoria_id'],
                $livro['idade_recomendada'],
                $livro['numero_paginas'],
                $livro['destaque'] ? 1 : 0,
                $livro['titulo']
            ]);
            $atualizados++;
        } else {
            // Inserir novo livro
            $stmt = $pdo->prepare("
                INSERT INTO livros (titulo, autor, descricao, sinopse, preco, imagem, categoria_id, idade_recomendada, numero_paginas, destaque, disponivel) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
            ");
            $stmt->execute([
                $livro['titulo'],
                $livro['autor'],
                $livro['descricao'],
                $livro['sinopse'],
                $livro['preco'],
                $livro['imagem'],
                $livro['categoria_id'],
                $livro['idade_recomendada'],
                $livro['numero_paginas'],
                $livro['destaque'] ? 1 : 0
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


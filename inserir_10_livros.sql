-- Script para garantir 10 livros no catálogo com imagens e títulos corretos
-- Execute este script após o database.sql inicial

USE bookcloud;

-- Primeiro, vamos limpar os livros existentes (opcional - comente se quiser manter)
-- DELETE FROM livros;

-- Inserir 10 livros completos com imagens
-- Se já existirem, use INSERT IGNORE ou DELETE antes de executar

INSERT INTO livros (titulo, autor, descricao, sinopse, preco, imagem, categoria_id, idade_recomendada, numero_paginas, destaque, disponivel) VALUES
-- Livro 1
('O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 
'Um clássico da literatura mundial que encanta leitores de todas as idades.', 
'A história de um piloto que encontra um pequeno príncipe vindo de outro planeta. Uma obra poética que fala sobre amizade, amor e a essência da vida.', 
29.90, 'assets/img/o-pequeno-principe.jpg', 4, 'Todas as idades', 96, TRUE, TRUE),

-- Livro 2
('Sapiens: Uma Breve História da Humanidade', 'Yuval Noah Harari', 
'Uma análise fascinante da evolução da humanidade desde os primórdios até os dias atuais.', 
'Harari examina como o Homo sapiens se tornou a espécie dominante na Terra, explorando as revoluções cognitiva, agrícola e científica que moldaram nossa sociedade.', 
45.90, 'assets/img/sapiens.jpg', 2, 'Adulto', 443, FALSE, TRUE),

-- Livro 3
('1984', 'George Orwell', 
'Um dos romances distópicos mais influentes da literatura mundial.', 
'Winston Smith vive em um mundo onde o Estado controla todos os aspectos da vida. Uma reflexão sobre poder, liberdade e resistência em uma sociedade totalitária.', 
32.50, 'assets/img/1984.jpg', 1, 'Adulto', 328, TRUE, TRUE),

-- Livro 4
('O Poder do Hábito', 'Charles Duhigg', 
'Um guia prático para entender e transformar hábitos em nossa vida pessoal e profissional.', 
'Duhigg explora a ciência por trás dos hábitos e como podemos usá-la para criar mudanças positivas em nossas vidas, desde a produtividade até a saúde.', 
38.90, 'assets/img/poder.jpg', 5, 'Adulto', 408, FALSE, TRUE),

-- Livro 5
('Harry Potter e a Pedra Filosofal', 'J.K. Rowling', 
'O primeiro livro da série que conquistou milhões de leitores ao redor do mundo.', 
'Harry Potter descobre que é um bruxo e ingressa na Escola de Magia e Bruxaria de Hogwarts, onde viverá aventuras incríveis e descobrirá segredos sobre seu passado.', 
35.90, 'assets/img/harry.jpg', 4, 'Jovem/Adulto', 223, FALSE, TRUE),

-- Livro 6
('Clean Code', 'Robert C. Martin', 
'Um guia essencial para desenvolvedores que desejam escrever código limpo e profissional.', 
'Martin apresenta princípios e práticas para escrever código mais legível, manutenível e eficiente, com exemplos práticos e dicas valiosas para programadores.', 
89.90, 'assets/img/clean.jpg', 3, 'Adulto', 464, FALSE, TRUE),

-- Livro 7
('Dom Casmurro', 'Machado de Assis', 
'Um dos maiores clássicos brasileiros, explorando ciúmes e memórias.', 
'Bentinho narra sua história de amor com Capitu, questionando a fidelidade e a natureza humana através de uma narrativa psicológica profunda e envolvente.', 
34.90, 'assets/img/domcasmurro.jpg', 1, '12+', 256, FALSE, TRUE),

-- Livro 8
('Orgulho e Preconceito', 'Jane Austen', 
'Romance atemporal sobre amor, etiqueta social e descobertas pessoais.', 
'Elizabeth Bennet e o orgulhoso Sr. Darcy se encontram em uma história de amor que supera preconceitos sociais e revela a verdadeira natureza humana.', 
39.90, 'assets/img/orgulho.jpg', 1, '12+', 432, FALSE, TRUE),

-- Livro 9
('A Revolução dos Bichos', 'George Orwell', 
'Uma fábula política inteligente e indispensável.', 
'Os animais de uma fazenda se rebelam contra seus donos humanos, criando uma sociedade igualitária que gradualmente se transforma em uma tirania.', 
28.90, 'assets/img/revolucao.jpg', 1, '14+', 152, TRUE, TRUE),

-- Livro 10
('O Hobbit', 'J.R.R. Tolkien', 
'Uma jornada épica com dragões, anões e muita fantasia.', 
'Bilbo Bolseiro, um hobbit pacato, é arrastado para uma aventura épica para recuperar um tesouro guardado por um dragão, descobrindo coragem e amizade pelo caminho.', 
49.90, 'assets/img/hobbit.jpg', 1, '10+', 320, FALSE, TRUE)

ON DUPLICATE KEY UPDATE 
    titulo = VALUES(titulo),
    autor = VALUES(autor),
    descricao = VALUES(descricao),
    sinopse = VALUES(sinopse),
    preco = VALUES(preco),
    imagem = VALUES(imagem),
    categoria_id = VALUES(categoria_id),
    idade_recomendada = VALUES(idade_recomendada),
    numero_paginas = VALUES(numero_paginas),
    destaque = VALUES(destaque),
    disponivel = VALUES(disponivel);

-- Verificar quantos livros foram inseridos
SELECT COUNT(*) as total_livros FROM livros WHERE disponivel = 1;


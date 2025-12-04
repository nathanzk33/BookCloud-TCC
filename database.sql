-- Criação do banco de dados para o site de livros BookCloud
CREATE DATABASE IF NOT EXISTS bookcloud;
USE bookcloud;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    cidade VARCHAR(50),
    estado VARCHAR(2),
    cep VARCHAR(10),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- Tabela de categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    cor VARCHAR(7) DEFAULT '#ff6b6b'
);

-- Tabela de livros
CREATE TABLE livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    descricao TEXT,
    sinopse TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255),
    categoria_id INT,
    idade_recomendada VARCHAR(20),
    numero_paginas INT,
    idioma VARCHAR(20) DEFAULT 'Português',
    disponivel BOOLEAN DEFAULT TRUE,
    destaque BOOLEAN DEFAULT FALSE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabela de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'processando', 'enviado', 'entregue', 'cancelado') DEFAULT 'pendente',
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    endereco_entrega TEXT,
    observacoes TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de itens do pedido
CREATE TABLE pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    livro_id INT,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (livro_id) REFERENCES livros(id)
);

-- Tabela de contatos
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    assunto VARCHAR(100),
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lido BOOLEAN DEFAULT FALSE
);

-- Inserir categorias padrão
INSERT INTO categorias (nome, descricao, cor) VALUES
('Ficção', 'Romances, contos e literatura ficcional', '#0554ffff '),
('Não-Ficção', 'Biografias, história, ciência e conhecimento', '#0554ffff '),
('Técnicos', 'Livros técnicos e acadêmicos', '#3453ffff'),
('Infantil', 'Livros para crianças e jovens', '#442fffff'),
('Autoajuda', 'Desenvolvimento pessoal e motivacional', '#000000'),
('Religião', 'Livros religiosos e espirituais', '#a29bfe');

-- Inserir 10 livros completos com imagens e títulos corretos para busca
INSERT INTO livros (titulo, autor, descricao, sinopse, preco, imagem, categoria_id, idade_recomendada, numero_paginas, destaque, disponivel) VALUES
('O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 
'Um clássico da literatura mundial que encanta leitores de todas as idades.', 
'A história de um piloto que encontra um pequeno príncipe vindo de outro planeta. Uma obra poética que fala sobre amizade, amor e a essência da vida.', 
29.90, 'assets/img/o-pequeno-principe.jpg', 4, 'Todas as idades', 96, TRUE, TRUE),

('Sapiens: Uma Breve História da Humanidade', 'Yuval Noah Harari', 
'Uma análise fascinante da evolução da humanidade desde os primórdios até os dias atuais.', 
'Harari examina como o Homo sapiens se tornou a espécie dominante na Terra, explorando as revoluções cognitiva, agrícola e científica que moldaram nossa sociedade.', 
45.90, 'assets/img/sapiens.jpg', 2, 'Adulto', 443, FALSE, TRUE),

('1984', 'George Orwell', 
'Um dos romances distópicos mais influentes da literatura mundial.', 
'Winston Smith vive em um mundo onde o Estado controla todos os aspectos da vida. Uma reflexão sobre poder, liberdade e resistência em uma sociedade totalitária.', 
32.50, 'assets/img/1984.jpg', 1, 'Adulto', 328, TRUE, TRUE),

('O Poder do Hábito', 'Charles Duhigg', 
'Um guia prático para entender e transformar hábitos em nossa vida pessoal e profissional.', 
'Duhigg explora a ciência por trás dos hábitos e como podemos usá-la para criar mudanças positivas em nossas vidas, desde a produtividade até a saúde.', 
38.90, 'assets/img/poder.jpg', 5, 'Adulto', 408, FALSE, TRUE),

('Harry Potter e a Pedra Filosofal', 'J.K. Rowling', 
'O primeiro livro da série que conquistou milhões de leitores ao redor do mundo.', 
'Harry Potter descobre que é um bruxo e ingressa na Escola de Magia e Bruxaria de Hogwarts, onde viverá aventuras incríveis e descobrirá segredos sobre seu passado.', 
35.90, 'assets/img/harry.jpg', 4, 'Jovem/Adulto', 223, FALSE, TRUE),

('Clean Code', 'Robert C. Martin', 
'Um guia essencial para desenvolvedores que desejam escrever código limpo e profissional.', 
'Martin apresenta princípios e práticas para escrever código mais legível, manutenível e eficiente, com exemplos práticos e dicas valiosas para programadores.', 
89.90, 'assets/img/clean.jpg', 3, 'Adulto', 464, FALSE, TRUE),

('Dom Casmurro', 'Machado de Assis', 
'Um dos maiores clássicos brasileiros, explorando ciúmes e memórias.', 
'Bentinho narra sua história de amor com Capitu, questionando a fidelidade e a natureza humana através de uma narrativa psicológica profunda e envolvente.', 
34.90, 'assets/img/domcasmurro.jpg', 1, '12+', 256, FALSE, TRUE),

('Orgulho e Preconceito', 'Jane Austen', 
'Romance atemporal sobre amor, etiqueta social e descobertas pessoais.', 
'Elizabeth Bennet e o orgulhoso Sr. Darcy se encontram em uma história de amor que supera preconceitos sociais e revela a verdadeira natureza humana.', 
39.90, 'assets/img/orgulho.jpg', 1, '12+', 432, FALSE, TRUE),

('A Revolução dos Bichos', 'George Orwell', 
'Uma fábula política inteligente e indispensável.', 
'Os animais de uma fazenda se rebelam contra seus donos humanos, criando uma sociedade igualitária que gradualmente se transforma em uma tirania.', 
28.90, 'assets/img/revolucao.jpg', 1, '14+', 152, TRUE, TRUE),

('O Hobbit', 'J.R.R. Tolkien', 
'Uma jornada épica com dragões, anões e muita fantasia.', 
'Bilbo Bolseiro, um hobbit pacato, é arrastado para uma aventura épica para recuperar um tesouro guardado por um dragão, descobrindo coragem e amizade pelo caminho.', 
49.90, 'assets/img/hobbit.jpg', 1, '10+', 320, FALSE, TRUE);

-- Inserir usuário administrador padrão
INSERT INTO usuarios (nome, email, senha, telefone, endereco, cidade, estado, cep) VALUES
('Administrador', 'admin@bookcloud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(14) 99171-4230', 'Rua das Flores, 123', 'Bauru', 'SP', '17000-000');

-- Criar índices para melhor performance
CREATE INDEX idx_livros_categoria ON livros(categoria_id);
CREATE INDEX idx_livros_destaque ON livros(destaque);
CREATE INDEX idx_livros_disponivel ON livros(disponivel);
CREATE INDEX idx_pedidos_usuario ON pedidos(usuario_id);
CREATE INDEX idx_pedidos_status ON pedidos(status);
CREATE INDEX idx_pedido_itens_pedido ON pedido_itens(pedido_id);
CREATE INDEX idx_pedido_itens_livro ON pedido_itens(livro_id);

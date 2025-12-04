<?php
require_once 'includes/conexao.php';
require_once 'includes/helpers.php';

$pageTitle = 'Detalhes do Livro';

// Verificar se o ID foi fornecido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: catalogo.php');
    exit;
}

$livro_id = $_GET['id'];

// Buscar detalhes do livro
$stmt = $pdo->prepare("SELECT l.*, c.nome as categoria_nome, c.cor as categoria_cor 
                      FROM livros l 
                      LEFT JOIN categorias c ON l.categoria_id = c.id 
                      WHERE l.id = :id AND l.disponivel = 1");
$stmt->execute(['id' => $livro_id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

// Se livro não encontrado, redirecionar
if (!$livro) {
    header('Location: catalogo.php');
    exit;
}

// Buscar livros relacionados (mesma categoria)
$stmt = $pdo->prepare("SELECT l.*, c.nome as categoria_nome, c.cor as categoria_cor 
                      FROM livros l 
                      LEFT JOIN categorias c ON l.categoria_id = c.id 
                      WHERE l.categoria_id = :categoria_id AND l.id != :livro_id AND l.disponivel = 1 
                      ORDER BY l.data_cadastro DESC 
                      LIMIT 4");
$stmt->execute(['categoria_id' => $livro['categoria_id'], 'livro_id' => $livro_id]);
$livrosRelacionados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $livro['titulo'];

include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 2rem;">
            <a href="index.php" style="color: var(--text-light); text-decoration: none;">Início</a>
            <span style="margin: 0 0.5rem; color: var(--text-light);">/</span>
            <a href="catalogo.php" style="color: var(--text-light); text-decoration: none;">Catálogo</a>
            <span style="margin: 0 0.5rem; color: var(--text-light);">/</span>
            <span style="color: var(--text-dark); font-weight: 500;"><?php echo htmlspecialchars($livro['titulo']); ?></span>
        </nav>
        
        <div class="book-detail">
            <div class="book-detail-grid">
                <!-- Imagem do Livro -->
                <div class="book-image-container">
                    <img src="<?php echo resolverImagemLivro($livro); ?>" 
                         alt="<?php echo htmlspecialchars($livro['titulo']); ?>" 
                         class="book-image">
                    <?php if ($livro['destaque']): ?>
                    <div class="destaque-badge">
                        <i class="fas fa-star"></i>
                        Destaque
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Informações do Livro -->
                <div class="book-info">
                    <h1 class="book-title"><?php echo htmlspecialchars($livro['titulo']); ?></h1>
                    <p class="book-author">por <strong><?php echo htmlspecialchars($livro['autor']); ?></strong></p>
                    
                    <div class="book-meta">
                        <span class="book-category" style="background-color: <?php echo $livro['categoria_cor']; ?>">
                            <?php echo htmlspecialchars($livro['categoria_nome']); ?>
                        </span>
                        <span class="book-age"><?php echo htmlspecialchars($livro['idade_recomendada']); ?></span>
                        <span class="book-pages"><?php echo $livro['numero_paginas']; ?> páginas</span>
                        <span class="book-language"><?php echo htmlspecialchars($livro['idioma']); ?></span>
                    </div>
                    
                    <div class="book-price">
                        <span class="price-label">Preço:</span>
                        <span class="price-value">R$ <?php echo number_format($livro['preco'], 2, ',', '.'); ?></span>
                    </div>
                    
                    <div class="book-actions">
                        <button class="btn btn-primary btn-large add-to-cart" 
                                data-id="<?php echo $livro['id']; ?>"
                                data-title="<?php echo htmlspecialchars($livro['titulo']); ?>"
                                data-price="<?php echo $livro['preco']; ?>"
                                data-image="<?php echo resolverImagemLivro($livro); ?>">
                            <i class="fas fa-shopping-cart"></i>
                            Adicionar ao Carrinho
                        </button>
                        <a href="carrinho.php" class="btn btn-secondary btn-large">
                            <i class="fas fa-shopping-bag"></i>
                            Ver Carrinho
                        </a>
                    </div>
                    
                    <!-- Informações Adicionais -->
                    <div class="book-details">
                        <h3>Detalhes do Livro</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <strong>Autor:</strong>
                                <span><?php echo htmlspecialchars($livro['autor']); ?></span>
                            </div>
                            <div class="detail-item">
                                <strong>Idade Recomendada:</strong>
                                <span><?php echo htmlspecialchars($livro['idade_recomendada']); ?></span>
                            </div>
                            <div class="detail-item">
                                <strong>Número de Páginas:</strong>
                                <span><?php echo $livro['numero_paginas']; ?></span>
                            </div>
                            <div class="detail-item">
                                <strong>Idioma:</strong>
                                <span><?php echo htmlspecialchars($livro['idioma']); ?></span>
                            </div>
                            <div class="detail-item">
                                <strong>Categoria:</strong>
                                <span><?php echo htmlspecialchars($livro['categoria_nome']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Descrição e Sinopse -->
            <div class="book-description">
                <div class="description-tabs">
                    <button class="tab-btn active" onclick="showTab('descricao')">Descrição</button>
                    <button class="tab-btn" onclick="showTab('sinopse')">Sinopse</button>
                </div>
                
                <div class="tab-content">
                    <div id="descricao" class="tab-panel active">
                        <h3>Sobre o Livro</h3>
                        <p><?php echo nl2br(htmlspecialchars($livro['descricao'])); ?></p>
                    </div>
                    
                    <div id="sinopse" class="tab-panel">
                        <h3>Sinopse</h3>
                        <p><?php echo nl2br(htmlspecialchars($livro['sinopse'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Livros Relacionados -->
        <?php if (count($livrosRelacionados) > 0): ?>
        <section class="related-books">
            <h2 class="section-title">Livros Relacionados</h2>
            <div class="grid grid-4">
                <?php foreach ($livrosRelacionados as $livroRelacionado): ?>
                <div class="card book-card">
                    <img src="<?php echo resolverImagemLivro($livroRelacionado); ?>" 
                         alt="<?php echo htmlspecialchars($livroRelacionado['titulo']); ?>" 
                         class="card-image">
                    <div class="card-content">
                        <h3 class="card-title"><?php echo htmlspecialchars($livroRelacionado['titulo']); ?></h3>
                        <p class="card-author">por <?php echo htmlspecialchars($livroRelacionado['autor']); ?></p>
                        <div class="card-meta">
                            <span class="card-category" style="background-color: <?php echo $livroRelacionado['categoria_cor']; ?>">
                                <?php echo htmlspecialchars($livroRelacionado['categoria_nome']); ?>
                            </span>
                            <span class="card-age"><?php echo htmlspecialchars($livroRelacionado['idade_recomendada']); ?></span>
                        </div>
                        <div class="card-price">R$ <?php echo number_format($livroRelacionado['preco'], 2, ',', '.'); ?></div>
                        <div class="card-actions">
                            <a href="livro.php?id=<?php echo $livroRelacionado['id']; ?>" class="btn btn-outline">Ver Detalhes</a>
                            <button class="btn btn-primary add-to-cart" 
                                    data-id="<?php echo $livroRelacionado['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($livroRelacionado['titulo']); ?>"
                                    data-price="<?php echo $livroRelacionado['preco']; ?>"
                                    data-image="<?php echo resolverImagemLivro($livroRelacionado); ?>">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</section>

<style>
.book-detail {
    margin-bottom: 4rem;
}

.book-detail-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 4rem;
    margin-bottom: 3rem;
}

.book-image-container {
    position: relative;
}

.book-image {
    width: 100%;
    max-width: 400px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.destaque-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--yellow-color);
    color: var(--text-dark);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.book-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.book-author {
    font-size: 1.2rem;
    color: var(--text-light);
    margin-bottom: 2rem;
}

.book-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.book-category, .book-age, .book-pages, .book-language {
    background: var(--primary-color);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.book-age {
    background: var(--secondary-color);
}

.book-pages {
    background: var(--accent-color);
}

.book-language {
    background: var(--text-light);
}

.book-price {
    margin: 2rem 0;
    padding: 1.5rem;
    background: var(--bg-light);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.price-label {
    font-size: 1.2rem;
    color: var(--text-light);
}

.price-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

.book-actions {
    display: flex;
    gap: 1rem;
    margin: 2rem 0;
}

.btn-large {
    padding: 1.2rem 2rem;
    font-size: 1.1rem;
    flex: 1;
    text-align: center;
}

.book-details {
    margin-top: 3rem;
    padding: 2rem;
    background: var(--bg-light);
    border-radius: var(--border-radius);
}

.book-details h3 {
    margin-bottom: 1.5rem;
    color: var(--text-dark);
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item strong {
    color: var(--text-dark);
    font-weight: 600;
}

.detail-item span {
    color: var(--text-light);
}

.book-description {
    margin: 3rem 0;
}

.description-tabs {
    display: flex;
    border-bottom: 2px solid #e0e0e0;
    margin-bottom: 2rem;
}

.tab-btn {
    background: none;
    border: none;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: var(--transition);
}

.tab-btn.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.tab-panel {
    display: none;
    padding: 2rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.tab-panel.active {
    display: block;
}

.tab-panel h3 {
    margin-bottom: 1rem;
    color: var(--text-dark);
}

.tab-panel p {
    line-height: 1.8;
    color: var(--text-light);
    font-size: 1.1rem;
}

.related-books {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 2px solid #e0e0e0;
}

.card-meta {
    display: flex;
    gap: 0.5rem;
    margin: 1rem 0;
    flex-wrap: wrap;
}

.card-category, .card-age {
    background: var(--primary-color);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.card-age {
    background: var(--secondary-color);
}

@media (max-width: 768px) {
    .book-detail-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .book-actions {
        flex-direction: column;
    }
    
    .book-meta {
        justify-content: center;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .description-tabs {
        flex-direction: column;
    }
    
    .tab-btn {
        text-align: left;
    }
}
</style>

<script>
function showTab(tabName) {
    // Esconder todas as abas
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.remove('active');
    });
    
    // Remover active de todos os botões
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar aba selecionada
    document.getElementById(tabName).classList.add('active');
    
    // Ativar botão correspondente
    event.target.classList.add('active');
}
</script>

<?php include 'includes/footer.php'; ?>

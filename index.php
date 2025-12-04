<?php
require_once 'includes/conexao.php';
require_once 'includes/helpers.php';

$pageTitle = 'Início';

// Buscar livro em destaque
$stmt = $pdo->prepare("SELECT l.*, c.nome as categoria_nome, c.cor as categoria_cor 
                      FROM livros l 
                      LEFT JOIN categorias c ON l.categoria_id = c.id 
                      WHERE l.destaque = 1 AND l.disponivel = 1 
                      ORDER BY l.data_cadastro DESC 
                      LIMIT 1");
$stmt->execute();
$livroDestaque = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar livros recentes
$stmt = $pdo->prepare("SELECT l.*, c.nome as categoria_nome, c.cor as categoria_cor 
                      FROM livros l 
                      LEFT JOIN categorias c ON l.categoria_id = c.id 
                      WHERE l.disponivel = 1 
                      ORDER BY l.data_cadastro DESC 
                      LIMIT 6");
$stmt->execute();
$livrosRecentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar livros para a seção "Novos Títulos Selecionados" (do banco, não do arquivo)
$stmt = $pdo->prepare("SELECT l.*, c.nome as categoria_nome, c.cor as categoria_cor 
                      FROM livros l 
                      LEFT JOIN categorias c ON l.categoria_id = c.id 
                      WHERE l.disponivel = 1 
                      ORDER BY l.data_cadastro DESC 
                      LIMIT 12");
$stmt->execute();
$livrosExtras = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Descubra o Mundo dos Livros</h1>
        <p>Encontre os melhores livros para todos os gostos e idades. De clássicos da literatura a lançamentos contemporâneos, temos o livro perfeito para você.</p>
        <a href="catalogo.php" class="cta-button">Explorar Catálogo</a>
    </div>
</section>

<?php if (!empty($livrosExtras)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Novos Títulos Selecionados</h2>
        <div class="grid grid-4">
            <?php foreach ($livrosExtras as $extra): ?>
            <div class="card book-card extra-card">
                <img src="<?php echo resolverImagemLivro($extra); ?>"
                     alt="<?php echo htmlspecialchars($extra['titulo']); ?>"
                     class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo htmlspecialchars($extra['titulo']); ?></h3>
                    <p class="card-author">por <?php echo htmlspecialchars($extra['autor']); ?></p>
                    <p class="card-description"><?php echo htmlspecialchars(substr($extra['descricao'], 0, 100)) . '...'; ?></p>
                    <div class="card-meta">
                        <span class="card-category" style="background-color: <?php echo $extra['categoria_cor']; ?>">
                            <?php echo htmlspecialchars($extra['categoria_nome']); ?>
                        </span>
                        <span class="card-age"><?php echo htmlspecialchars($extra['idade_recomendada']); ?></span>
                    </div>
                    <div class="card-price">R$ <?php echo number_format($extra['preco'], 2, ',', '.'); ?></div>
                    <div class="card-actions">
                        <a href="livro.php?id=<?php echo $extra['id']; ?>" class="btn btn-outline">Ver Detalhes</a>
                        <button class="btn btn-primary add-to-cart" 
                                data-id="<?php echo $extra['id']; ?>"
                                data-title="<?php echo htmlspecialchars($extra['titulo']); ?>"
                                data-price="<?php echo $extra['preco']; ?>"
                                data-image="<?php echo resolverImagemLivro($extra); ?>">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Book Section -->
<?php if ($livroDestaque): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Livro em Destaque</h2>
        <div class="featured-book">
            <div class="featured-image-container">
                    <img src="<?php echo resolverImagemLivro($livroDestaque); ?>" 
                     alt="<?php echo htmlspecialchars($livroDestaque['titulo']); ?>" 
                     class="featured-image">
            </div>
            <div class="featured-content">
                <h2><?php echo htmlspecialchars($livroDestaque['titulo']); ?></h2>
                <p class="featured-author">por <?php echo htmlspecialchars($livroDestaque['autor']); ?></p>
                <p><?php echo htmlspecialchars($livroDestaque['sinopse']); ?></p>
                <div class="featured-details">
                    <span class="featured-category" style="background-color: <?php echo $livroDestaque['categoria_cor']; ?>">
                        <?php echo htmlspecialchars($livroDestaque['categoria_nome']); ?>
                    </span>
                    <span class="featured-age"><?php echo htmlspecialchars($livroDestaque['idade_recomendada']); ?></span>
                    <span class="featured-pages"><?php echo $livroDestaque['numero_paginas']; ?> páginas</span>
                </div>
                <div class="featured-price">R$ <?php echo number_format($livroDestaque['preco'], 2, ',', '.'); ?></div>
                <div class="featured-actions">
                    <a href="livro.php?id=<?php echo $livroDestaque['id']; ?>" class="btn btn-primary">Ver Detalhes</a>
                    <button class="btn btn-secondary add-to-cart" 
                            data-id="<?php echo $livroDestaque['id']; ?>"
                            data-title="<?php echo htmlspecialchars($livroDestaque['titulo']); ?>"
                            data-price="<?php echo $livroDestaque['preco']; ?>"
                            data-image="<?php echo resolverImagemLivro($livroDestaque); ?>">
                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Recent Books Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <h2 class="section-title">Livros Recentes</h2>
        <div class="grid grid-3">
            <?php foreach ($livrosRecentes as $livro): ?>
            <div class="card book-card" data-category="<?php echo $livro['categoria_id']; ?>">
                <img src="<?php echo resolverImagemLivro($livro); ?>" 
                     alt="<?php echo htmlspecialchars($livro['titulo']); ?>" 
                     class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p class="card-author">por <?php echo htmlspecialchars($livro['autor']); ?></p>
                    <p class="card-description"><?php echo htmlspecialchars(substr($livro['descricao'], 0, 100)) . '...'; ?></p>
                    <div class="card-meta">
                        <span class="card-category" style="background-color: <?php echo $livro['categoria_cor']; ?>">
                            <?php echo htmlspecialchars($livro['categoria_nome']); ?>
                        </span>
                        <span class="card-age"><?php echo htmlspecialchars($livro['idade_recomendada']); ?></span>
                    </div>
                    <div class="card-price">R$ <?php echo number_format($livro['preco'], 2, ',', '.'); ?></div>
                    <div class="card-actions">
                        <a href="livro.php?id=<?php echo $livro['id']; ?>" class="btn btn-outline">Ver Detalhes</a>
                        <button class="btn btn-primary add-to-cart" 
                                data-id="<?php echo $livro['id']; ?>" 
                                data-title="<?php echo htmlspecialchars($livro['titulo']); ?>"
                                data-price="<?php echo $livro['preco']; ?>"
                                data-image="<?php echo resolverImagemLivro($livro); ?>">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="catalogo.php" class="btn btn-primary">Ver Todos os Livros</a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <h2 class="section-title">Como Funciona</h2>
        <div class="grid grid-3">
            <div class="card" style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-search"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">1. Explore</h3>
                <p style="color: var(--text-light);">
                    Navegue por nosso catálogo cuidadosamente curado, com milhares de títulos organizados por categoria e gênero.
                </p>
            </div>
            <div class="card" style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">2. Compre</h3>
                <p style="color: var(--text-light);">
                    Adicione seus livros favoritos ao carrinho e finalize sua compra com segurança e praticidade.
                </p>
            </div>
            <div class="card" style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; color: var(--accent-color); margin-bottom: 1rem;">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">3. Receba</h3>
                <p style="color: var(--text-light);">
                    Seus livros são entregues rapidamente em sua casa, prontos para serem descobertos e apreciados.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section">
    <div class="container">
        <div style="text-align: center; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); padding: 3rem; border-radius: var(--border-radius); color: white;">
            <h2 style="margin-bottom: 1rem; color: white;">Fique Por Dentro!</h2>
            <p style="margin-bottom: 2rem; opacity: 0.9;">
                Receba notificações sobre novos lançamentos, promoções especiais e recomendações personalizadas.
            </p>
            <form style="display: flex; gap: 1rem; max-width: 400px; margin: 0 auto;">
                <input type="email" placeholder="Seu melhor email" style="flex: 1; padding: 1rem; border: none; border-radius: var(--border-radius);">
                <button type="submit" class="btn" style="background: var(--yellow-color); color: var(--text-dark); border: none; padding: 1rem 2rem; border-radius: var(--border-radius); font-weight: 600;">
                    Inscrever-se
                </button>
            </form>
        </div>
    </div>
</section>

<style>
.featured-book {
    background: linear-gradient(135deg, var(--bg-light), var(--white));
    border-radius: var(--border-radius);
    padding: 3rem;
    margin: 3rem 0;
    box-shadow: var(--shadow);
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 3rem;
    align-items: center;
}

.featured-image {
    width: 100%;
    max-width: 400px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.featured-content h2 {
    font-size: 2.5rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.featured-author {
    color: var(--text-light);
    font-style: italic;
    margin-bottom: 1rem;
}

.featured-details {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
    flex-wrap: wrap;
}

.featured-category, .featured-age, .featured-pages {
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.featured-price {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: 1rem 0;
}

.featured-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
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

.grid-2 {
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
}

@media (max-width: 768px) {
    .featured-book {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .featured-actions {
        flex-direction: column;
    }
    
    .grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>

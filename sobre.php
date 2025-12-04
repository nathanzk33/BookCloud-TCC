<?php
require_once 'includes/conexao.php';

$pageTitle = 'Sobre Nós';

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));">
    <div class="hero-content">
        <h1>Nossa História</h1>
        <p>Conectando leitores com os melhores livros do mundo através de uma experiência única de compra</p>
    </div>
</section>

<!-- Mission Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="align-items: center; gap: 4rem;">
            <div>
                <h2 class="section-title" style="text-align: left; margin-bottom: 2rem;">Nossa Missão</h2>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; color: var(--text-light); line-height: 1.8;">
                    Acreditamos que os livros são portais para novos mundos, conhecimento e inspiração. Nossa missão é 
                    democratizar o acesso à literatura de qualidade, oferecendo uma curadoria cuidadosa dos melhores títulos.
                </p>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; color: var(--text-light); line-height: 1.8;">
                    Desde clássicos atemporais até os lançamentos mais recentes, nossa plataforma conecta leitores 
                    com histórias que transformam vidas e expandem horizontes.
                </p>
                <div style="display: flex; gap: 2rem; margin-top: 3rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 3rem; font-weight: 700; color: var(--primary-color);">15+</div>
                        <div style="color: var(--text-light); font-weight: 600;">Títulos Disponíveis</div>
                    </div>
                    <div style="text-align: center;">
                        
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 3rem; font-weight: 700; color: var(--accent-color);">5+</div>
                        <div style="color: var(--text-light); font-weight: 600;">Categorias</div>
                    </div>
                </div>
            </div>
            <div>
                <img src="assets/img/bibliotec.jpg" alt="Biblioteca de livros" style="width: 100%; border-radius: var(--border-radius); box-shadow: var(--shadow);">
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <h2 class="section-title">Como Funciona Nosso Processo</h2>
        <div class="grid grid-3">
            <div class="card" style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 4rem; color: var(--primary-color); margin-bottom: 2rem;">
                    <i class="fas fa-search"></i>
                </div>
                <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">1. Curadoria</h3>
                <p style="color: var(--text-light); line-height: 1.6;">
                    Nossa equipe especializada seleciona cuidadosamente os melhores títulos, desde clássicos 
                    atemporais até os lançamentos mais recentes, garantindo qualidade e relevância.
                </p>
            </div>
            <div class="card" style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 4rem; color: var(--secondary-color); margin-bottom: 2rem;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">2. Compra Fácil</h3>
                <p style="color: var(--text-light); line-height: 1.6;">
                    Oferecemos uma experiência de compra intuitiva e segura, com filtros avançados, 
                    recomendações personalizadas e processo de checkout simplificado.
                </p>
            </div>
            <div class="card" style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 4rem; color: var(--accent-color); margin-bottom: 2rem;">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">3. Entrega Rápida</h3>
                <p style="color: var(--text-light); line-height: 1.6;">
                    Seus livros são entregues rapidamente e com cuidado, prontos para serem descobertos 
                    e apreciados, expandindo seu conhecimento e imaginação.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Nossos Valores</h2>
        <div class="grid grid-2">
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Qualidade</h3>
                <p>Acreditamos que cada livro deve oferecer valor real ao leitor. Nossa curadoria garante que 
                cada título em nosso catálogo seja uma escolha valiosa e enriquecedora.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Comunidade</h3>
                <p>Construímos uma comunidade de leitores apaixonados que compartilham experiências, 
                recomendações e descobertas literárias, criando conexões através da leitura.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Inovação</h3>
                <p>Buscamos constantemente novas formas de conectar leitores com livros, utilizando tecnologia 
                para criar experiências de descoberta e compra cada vez mais personalizadas.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Educação</h3>
                <p>Promovemos o acesso ao conhecimento através da leitura, incentivando o desenvolvimento 
                intelectual e cultural de leitores de todas as idades e perfis.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <h2 class="section-title">Nossa Equipe</h2>
        <div class="grid grid-3">
            <div class="team-card">
                <div class="team-image">
                    <img src="assets/img/nathan.jpg" alt="Nathan de Oliveira">
                </div>
                <div class="team-info">
                    <h3>Nathan de Oliveira</h3>
                    <p class="team-role">Desenvolvedor Do Sistema</p>
                    <p class="team-bio"></p>
                </div>
            </div>
            <div class="team-card">
                <div class="team-image">
                    <img src="assets/img/hugo.jpg" alt="Victor Hugo Bueno">
                </div>
                <div class="team-info">
                    <h3>Victor Hugo Bueno</h3>
                    <p class="team-role">Desenvolvedor Do Sistema</p>
                    <p class="team-bio"></p>
                </div>
            </div>
            <div class="team-card">
                <div class="team-image">
                    <img src="assets/img/mendonca.jpg" alt="Eduardo Mendonça Gaspareto">
                </div>
                <div class="team-info">
                    <h3>Eduardo Mendonça Gaspareto</h3>
                    <p class="team-role">Desenvolvedor Do Sistema</p>
                    <p class="team-bio"></p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Call to Action -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
    <div class="container" style="text-align: center;">
        <h2 style="margin-bottom: 1rem; color: white;">Faça Parte da Nossa Comunidade</h2>
        <p style="margin-bottom: 2rem; opacity: 0.9; font-size: 1.2rem;">
            Junte-se a milhares de leitores que descobrem novos mundos através dos livros em nossa livraria.
        </p>
        <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap;">
            <a href="contato.php" class="btn" style="background: var(--yellow-color); color: var(--text-dark); padding: 1rem 2rem; border-radius: var(--border-radius); text-decoration: none; font-weight: 600;">
                <i class="fas fa-envelope"></i> Entre em Contato
            </a>
            <a href="catalogo.php" class="btn" style="background: transparent; color: white; border: 2px solid white; padding: 1rem 2rem; border-radius: var(--border-radius); text-decoration: none; font-weight: 600;">
                <i class="fas fa-book"></i> Ver Livros
            </a>
        </div>
    </div>
</section>

<style>
.value-card {
    background: var(--white);
    padding: 3rem 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    text-align: center;
    transition: var(--transition);
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.value-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: white;
    font-size: 2rem;
}

.value-card h3 {
    margin-bottom: 1rem;
    color: var(--text-dark);
    font-size: 1.5rem;
}

.value-card p {
    color: var(--text-light);
    line-height: 1.6;
}

.team-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: var(--transition);
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.team-image {
    height: 250px;
    overflow: hidden;
}

.team-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.team-card:hover .team-image img {
    transform: scale(1.05);
}

.team-info {
    padding: 2rem;
}

.team-info h3 {
    margin-bottom: 0.5rem;
    color: var(--text-dark);
    font-size: 1.3rem;
}

.team-role {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
}

.team-bio {
    color: var(--text-light);
    line-height: 1.6;
}

.impact-stats {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.stat-item {
    text-align: center;
    padding: 2rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--text-light);
    font-weight: 500;
}

.grid-2 {
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
}

@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
    
    .impact-stats {
        gap: 1rem;
    }
    
    .stat-item {
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>

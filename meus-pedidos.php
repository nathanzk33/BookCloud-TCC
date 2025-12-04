<?php
session_start();
require_once 'includes/conexao.php';
require_once 'includes/helpers.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar pedidos do usuário
$stmt = $pdo->prepare("
    SELECT p.*
    FROM pedidos p
    WHERE p.usuario_id = :usuario_id
    ORDER BY p.data_pedido DESC
");
$stmt->execute([':usuario_id' => $usuario_id]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar itens de cada pedido
foreach ($pedidos as &$pedido) {
    $stmt_itens = $pdo->prepare("
        SELECT pi.*, l.titulo, l.autor, l.imagem
        FROM pedido_itens pi
        INNER JOIN livros l ON pi.livro_id = l.id
        WHERE pi.pedido_id = :pedido_id
    ");
    $stmt_itens->execute([':pedido_id' => $pedido['id']]);
    $pedido['itens'] = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);
}

$pageTitle = 'Meus Pedidos';
include 'includes/header.php';

// Função para traduzir status
function traduzirStatus($status) {
    $status_traduzidos = [
        'pendente' => 'Pendente',
        'processando' => 'Processando',
        'enviado' => 'Enviado',
        'entregue' => 'Entregue',
        'cancelado' => 'Cancelado'
    ];
    return $status_traduzidos[$status] ?? $status;
}

// Função para obter cor do status
function getStatusColor($status) {
    $cores = [
        'pendente' => '#ffa500',
        'processando' => '#4a90e2',
        'enviado' => '#7b68ee',
        'entregue' => '#2ecc71',
        'cancelado' => '#e74c3c'
    ];
    return $cores[$status] ?? '#666';
}
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Meus Pedidos</h1>
        
        <?php if (empty($pedidos)): ?>
            <div class="empty-orders">
                <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #ccc; margin-bottom: 2rem;"></i>
                <h2>Você ainda não fez nenhum pedido</h2>
                <p style="color: var(--text-light); margin-bottom: 2rem;">
                    Que tal começar a explorar nossos livros?
                </p>
                <a href="catalogo.php" class="btn btn-primary">
                    <i class="fas fa-book"></i>
                    Ver Catálogo
                </a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></h3>
                                <p class="order-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                                </p>
                            </div>
                            <div class="order-status">
                                <span class="status-badge" style="background-color: <?php echo getStatusColor($pedido['status']); ?>">
                                    <?php echo traduzirStatus($pedido['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="order-items">
                            <?php foreach ($pedido['itens'] as $item): 
                                // Resolver imagem usando helper (já incluído no topo)
                                $imagemLivro = resolverImagemLivro($item);
                            ?>
                                <div class="order-item">
                                    <img src="<?php echo htmlspecialchars($imagemLivro); ?>" 
                                         alt="<?php echo htmlspecialchars($item['titulo']); ?>" 
                                         class="order-item-image"
                                         onerror="this.src='assets/img/box.jpg'">
                                    <div class="order-item-details">
                                        <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                                        <p class="order-item-author"><?php echo htmlspecialchars($item['autor']); ?></p>
                                        <p class="order-item-quantity">Quantidade: <?php echo $item['quantidade']; ?></p>
                                    </div>
                                    <div class="order-item-price">
                                        R$ <?php echo number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-footer">
                            <div class="order-total">
                                <strong>Total: R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></strong>
                            </div>
                            <?php if (!empty($pedido['endereco_entrega'])): ?>
                                <div class="order-address">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($pedido['endereco_entrega']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.empty-orders {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    margin-top: 2rem;
}

.order-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    padding: 2rem;
    transition: var(--transition);
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--bg-light);
}

.order-info h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 1.3rem;
}

.order-date {
    color: var(--text-light);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-light);
    border-radius: var(--border-radius);
}

.order-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.order-item-details {
    flex: 1;
}

.order-item-details h4 {
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.order-item-author {
    color: var(--text-light);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.order-item-quantity {
    color: var(--text-light);
    font-size: 0.85rem;
}

.order-item-price {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 1.1rem;
}

.order-footer {
    padding-top: 1.5rem;
    border-top: 2px solid var(--bg-light);
}

.order-total {
    font-size: 1.3rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
    text-align: right;
}

.order-address {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    color: var(--text-light);
    font-size: 0.9rem;
    padding: 1rem;
    background: var(--bg-light);
    border-radius: var(--border-radius);
}

.order-address i {
    color: var(--primary-color);
    margin-top: 0.2rem;
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .order-item-image {
        width: 100px;
        height: 100px;
    }
    
    .order-total {
        text-align: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>


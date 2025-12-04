<?php
require_once 'includes/conexao.php';
require_once __DIR__ . '/includes/helpers.php';

if (!function_exists('hashCategoriaNome')) {
    function hashCategoriaNome(string $nome): string
    {
        return md5(mb_strtolower(trim($nome)));
    }
}

if (!function_exists('normalizarCorCategoria')) {
    function normalizarCorCategoria(?string $cor): string
    {
        $cor = trim((string) $cor);
        if (preg_match('/^#[0-9a-f]{6}$/i', $cor)) {
            return $cor;
        }
        if (preg_match('/^#[0-9a-f]{8}$/i', $cor)) {
            return '#' . substr($cor, 1, 6);
        }
        if (preg_match('/^#[0-9a-f]{3}$/i', $cor)) {
            return $cor;
        }
        return '#1d3557';
    }
}

$pageTitle = 'Catálogo';

// Buscar categorias para os filtros
$stmt = $pdo->prepare("SELECT * FROM categorias ORDER BY nome");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoriasAgrupadas = [];
$nomeCategoriaPorId = [];
foreach ($categorias as $categoria) {
    $hash = hashCategoriaNome($categoria['nome']);
    if (!isset($categoriasAgrupadas[$hash])) {
        $categoriasAgrupadas[$hash] = [
            'hash' => $hash,
            'nome' => trim($categoria['nome']),
            'cor' => normalizarCorCategoria($categoria['cor'] ?? ''),
            'ids' => [],
        ];
    }
    $categoriasAgrupadas[$hash]['ids'][] = (int) $categoria['id'];
    $nomeCategoriaPorId[$categoria['id']] = $categoria['nome'];
}
$categoriasUnicas = array_values($categoriasAgrupadas);
$categoriaHashParaIds = [];
foreach ($categoriasUnicas as $categoria) {
    $categoriaHashParaIds[$categoria['hash']] = $categoria['ids'];
}

// Parâmetros de filtro
$categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'recentes';

// Construir query base
$sql = "SELECT l.*, c.nome as categoria_nome, c.cor as categoria_cor 
        FROM livros l 
        LEFT JOIN categorias c ON l.categoria_id = c.id 
        WHERE l.disponivel = 1";

$params = [];

// Aplicar filtros
if (!empty($categoria_filtro)) {
    $categoriaIdsFiltro = [];
    if (isset($categoriaHashParaIds[$categoria_filtro])) {
        $categoriaIdsFiltro = $categoriaHashParaIds[$categoria_filtro];
    } elseif (ctype_digit((string) $categoria_filtro)) {
        $categoriaIdsFiltro = [(int) $categoria_filtro];
    }

    if (!empty($categoriaIdsFiltro)) {
        $placeholders = [];
        foreach ($categoriaIdsFiltro as $index => $catId) {
            $paramKey = 'categoria_' . $index;
            $placeholders[] = ':' . $paramKey;
            $params[$paramKey] = $catId;
        }
        $sql .= " AND l.categoria_id IN (" . implode(',', $placeholders) . ")";
    }
}

if (!empty($busca)) {
    $sql .= " AND (l.titulo LIKE :busca OR l.autor LIKE :busca OR l.descricao LIKE :busca)";
    $params['busca'] = "%$busca%";
}

// Aplicar ordenação
switch ($ordenacao) {
    case 'preco_menor':
        $sql .= " ORDER BY l.preco ASC";
        break;
    case 'preco_maior':
        $sql .= " ORDER BY l.preco DESC";
        break;
    case 'titulo':
        $sql .= " ORDER BY l.titulo ASC";
        break;
    case 'recentes':
    default:
        $sql .= " ORDER BY l.data_cadastro DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debug: verificar total de livros no banco
$stmtDebug = $pdo->query("SELECT COUNT(*) as total FROM livros WHERE disponivel = 1");
$totalBanco = $stmtDebug->fetch(PDO::FETCH_ASSOC)['total'];

include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Catálogo de Livros</h1>
        
        <!-- Filtros e Busca -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end; margin-bottom: 2rem;">
                    <div>
                        <label for="busca" class="form-label">Buscar Livros</label>
                        <input type="text" id="busca" name="busca" class="form-input" 
                               placeholder="Digite o título, autor ou descrição..." 
                               value="<?php echo htmlspecialchars($busca); ?>">
                    </div>
                    <div>
                        <label for="categoria" class="form-label">Categoria</label>
                        <select id="categoria" name="categoria" class="form-input">
                            <option value="">Todas as categorias</option>
                            <?php foreach ($categoriasUnicas as $categoria): ?>
                            <option value="<?php echo $categoria['hash']; ?>" 
                                    <?php echo $categoria_filtro === $categoria['hash'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nome']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div class="filter-buttons">
                        <button type="button" class="filter-btn <?php echo $categoria_filtro == '' ? 'active' : ''; ?>" 
                                onclick="filtrarCategoria('')">Todos</button>
                        <?php foreach ($categoriasUnicas as $categoria): ?>
                        <button type="button" class="filter-btn <?php echo $categoria_filtro === $categoria['hash'] ? 'active' : ''; ?>" 
                                onclick="filtrarCategoria('<?php echo $categoria['hash']; ?>')"
                                style="background-color: <?php echo $categoria['cor']; ?>">
                            <?php echo htmlspecialchars($categoria['nome']); ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    
                    <div>
                        <label for="ordenacao" class="form-label">Ordenar por:</label>
                        <select id="ordenacao" name="ordenacao" class="form-input" onchange="this.form.submit()">
                            <option value="recentes" <?php echo $ordenacao == 'recentes' ? 'selected' : ''; ?>>Mais Recentes</option>
                            <option value="preco_menor" <?php echo $ordenacao == 'preco_menor' ? 'selected' : ''; ?>>Menor Preço</option>
                            <option value="preco_maior" <?php echo $ordenacao == 'preco_maior' ? 'selected' : ''; ?>>Maior Preço</option>
                            <option value="titulo" <?php echo $ordenacao == 'titulo' ? 'selected' : ''; ?>>Título A-Z</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Resultados -->
        <div style="margin-bottom: 2rem;">
            <p style="color: var(--text-light);">
                <?php if (!empty($busca) || !empty($categoria_filtro)): ?>
                    Encontrados <?php echo count($livros); ?> livro(s) 
                    <?php if (!empty($busca)): ?>
                        para "<?php echo htmlspecialchars($busca); ?>"
                    <?php endif; ?>
                    <?php if (!empty($categoria_filtro)): ?>
                        na categoria <?php echo $categorias[array_search($categoria_filtro, array_column($categorias, 'id'))]['nome']; ?>
                    <?php endif; ?>
                <?php else: ?>
                    Mostrando todos os <?php echo count($livros); ?> livros disponíveis
                    <?php if ($totalBanco != count($livros)): ?>
                        <br><small style="color: #ff6b6b;">⚠️ Atenção: Existem <?php echo $totalBanco; ?> livros no banco, mas apenas <?php echo count($livros); ?> estão sendo exibidos. 
                        <a href="forcar_inserir_livros.php" style="color: #0d47a1;">Clique aqui para sincronizar</a></small>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
        
        <!-- Grid de Livros -->
        <?php if (count($livros) > 0): ?>
        <div class="grid grid-3">
            <?php foreach ($livros as $livro): ?>
            <div class="card book-card" data-category="<?php echo $livro['categoria_id']; ?>">
                <img src="<?php echo resolverImagemLivro($livro); ?>" 
                     alt="<?php echo htmlspecialchars($livro['titulo']); ?>" 
                     class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p class="card-author">por <?php echo htmlspecialchars($livro['autor']); ?></p>
                    <p class="card-description"><?php echo htmlspecialchars(substr($livro['descricao'], 0, 120)) . '...'; ?></p>
                    
                    <div class="card-meta">
                        <span class="card-category" style="background-color: <?php echo $livro['categoria_cor']; ?>">
                            <?php echo htmlspecialchars($livro['categoria_nome']); ?>
                        </span>
                        <span class="card-age"><?php echo htmlspecialchars($livro['idade_recomendada']); ?></span>
                        <span class="card-pages"><?php echo $livro['numero_paginas']; ?> páginas</span>
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
        <?php else: ?>
        <div style="text-align: center; padding: 4rem 2rem; color: var(--text-light);">
            <i class="fas fa-search" style="font-size: 4rem; margin-bottom: 2rem; opacity: 0.3;"></i>
            <h3>Nenhum livro encontrado</h3>
            <p>Tente ajustar os filtros ou fazer uma nova busca.</p>
            <a href="catalogo.php" class="btn btn-primary" style="margin-top: 2rem;">Ver Todos os Livros</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div style="text-align: center;">
            <h2 style="margin-bottom: 1rem;">Não Encontrou o Que Procurava?</h2>
            <p style="margin-bottom: 2rem; color: var(--text-light);">
                Cadastre-se para receber notificações sobre novos livros e histórias incríveis.
            </p>
            <form style="display: flex; gap: 1rem; max-width: 400px; margin: 0 auto;">
                <input type="email" placeholder="Seu melhor email" style="flex: 1; padding: 1rem; border: 2px solid #e0e0e0; border-radius: var(--border-radius);">
                <button type="submit" class="btn btn-primary">Inscrever-se</button>
            </form>
        </div>
    </div>
</section>

<style>
.filter-form {
    background: var(--white);
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-btn {
    padding: 0.8rem 1.5rem;
    border: 2px solid var(--primary-color);
    background: transparent;
    color: var(--primary-color);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--primary-color);
    color: var(--white);
}

.card-meta {
    display: flex;
    gap: 0.5rem;
    margin: 1rem 0;
    flex-wrap: wrap;
}

.card-category, .card-age, .card-pages {
    background: var(--primary-color);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.card-pages {
    background: var(--text-light);
}

@media (max-width: 768px) {
    .filter-form > div:first-child {
        grid-template-columns: 1fr;
    }
    
    .filter-buttons {
        justify-content: flex-start;
    }
}
</style>

<script>
function filtrarCategoria(categoriaHash) {
    const form = document.querySelector('.filter-form');
    const select = form.querySelector('#categoria');
    if (select) {
        select.value = categoriaHash;
    } else {
        let categoriaInput = form.querySelector('input[name=\"categoria\"]');
        if (!categoriaInput) {
            categoriaInput = document.createElement('input');
            categoriaInput.type = 'hidden';
            categoriaInput.name = 'categoria';
            form.appendChild(categoriaInput);
        }
        categoriaInput.value = categoriaHash;
    }
    form.submit();
}
</script>

<?php include 'includes/footer.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Livros das Crianças</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="index.php" id="logo-link" class="logo-smooth-scroll">
                        <i class="fas fa-book-open"></i>
                        <span>BookCloud</span>
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Início</a>
                    </li>
                    <li class="nav-item">
                        <a href="catalogo.php" class="nav-link">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a href="sobre.php" class="nav-link">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a href="contato.php" class="nav-link">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a href="carrinho.php" class="nav-link cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">0</span>
                        </a>
                    </li>
                    <?php
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item">
                        <a href="meus-pedidos.php" class="nav-link">
                            <i class="fas fa-shopping-bag"></i>
                            Meus Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            Sair
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link">
                            <i class="fas fa-user"></i>
                            Login
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content">
    
    

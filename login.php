<?php
require_once 'includes/conexao.php';

$pageTitle = 'Login';

$erro = '';

// Processar login
if ($_POST) {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = 1");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                // Redirecionar para a página que estava tentando acessar ou para o início
                $redirect = $_GET['redirect'] ?? 'index.php';
                header("Location: $redirect");
                exit;
            } else {
                $erro = 'Email ou senha incorretos.';
            }
        } catch (PDOException $e) {
            $erro = 'Erro interno. Tente novamente mais tarde.';
        }
    }
}

include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1>Entrar na Sua Conta</h1>
                    <p>Faça login para acessar seu perfil e acompanhar seus pedidos</p>
                </div>
                
                <?php if ($erro): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $erro; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="password-input">
                            <input type="password" id="senha" name="senha" class="form-input" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="lembrar">
                            <span class="checkmark"></span>
                            Lembrar de mim
                        </label>
                        <a href="#" class="forgot-password">Esqueceu a senha?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-sign-in-alt"></i>
                        Entrar
                    </button>
                </form>
                
                <div class="auth-divider">
                    <span>ou</span>
                </div>
                
                <div class="auth-footer">
                    <p>Não tem uma conta? <a href="cadastro.php" class="auth-link">Cadastre-se aqui</a></p>
                </div>
            </div>
            
            <div class="auth-benefits">
                <h2>Benefícios de ter uma conta</h2>
                <div class="benefits-list">
                    <div class="benefit-item">
                        <i class="fas fa-shopping-cart"></i>
                        <div>
                            <h3>Acompanhe seus pedidos</h3>
                            <p>Visualize o status de todos os seus pedidos em um só lugar</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-heart"></i>
                        <div>
                            <h3>Lista de favoritos</h3>
                            <p>Salve seus livros favoritos para comprar depois</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <h3>Perfil personalizado</h3>
                            <p>Mantenha seus dados sempre atualizados</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-bell"></i>
                        <div>
                            <h3>Notificações</h3>
                            <p>Receba avisos sobre novos livros e promoções</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.auth-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    max-width: 1000px;
    margin: 0 auto;
    align-items: start;
}

.auth-card {
    background: var(--white);
    padding: 3rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h1 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: var(--text-light);
}

.auth-form {
    margin-bottom: 2rem;
}

.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: var(--transition);
}

.password-toggle:hover {
    background: var(--bg-light);
    color: var(--text-dark);
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1.5rem 0;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    color: var(--text-light);
    font-size: 0.9rem;
}

.checkbox-label input {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    position: relative;
    transition: var(--transition);
}

.checkbox-label input:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.checkbox-label input:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 0.8rem;
    font-weight: bold;
}

.forgot-password {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition);
}

.forgot-password:hover {
    color: var(--secondary-color);
}

.auth-divider {
    text-align: center;
    margin: 2rem 0;
    position: relative;
}

.auth-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e0e0e0;
}

.auth-divider span {
    background: var(--white);
    padding: 0 1rem;
    color: var(--text-light);
    font-size: 0.9rem;
}

.auth-footer {
    text-align: center;
}

.auth-footer p {
    color: var(--text-light);
    margin: 0;
}

.auth-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.auth-link:hover {
    color: var(--secondary-color);
}

.auth-benefits {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 3rem;
    border-radius: var(--border-radius);
    color: white;
}

.auth-benefits h2 {
    margin-bottom: 2rem;
    text-align: center;
}

.benefits-list {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.benefit-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.benefit-item i {
    font-size: 1.5rem;
    color: var(--yellow-color);
    margin-top: 0.2rem;
}

.benefit-item h3 {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.benefit-item p {
    opacity: 0.9;
    font-size: 0.9rem;
    line-height: 1.5;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .auth-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .auth-card {
        padding: 2rem;
    }
    
    .auth-benefits {
        padding: 2rem;
    }
    
    .form-options {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('senha');
    const toggleButton = document.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.classList.remove('fa-eye');
        toggleButton.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleButton.classList.remove('fa-eye-slash');
        toggleButton.classList.add('fa-eye');
    }
}
</script>

<?php include 'includes/footer.php'; ?>

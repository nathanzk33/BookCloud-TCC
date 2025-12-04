<?php
require_once 'includes/conexao.php';

$pageTitle = 'Cadastro';

$sucesso = '';
$erro = '';

// Processar cadastro
if ($_POST) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmar_senha = trim($_POST['confirmar_senha'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $cep = trim($_POST['cep'] ?? '');
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Por favor, insira um email válido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';
    } else {
        try {
            // Verificar se email já existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $erro = 'Este email já está cadastrado.';
            } else {
                // Inserir novo usuário
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, telefone, endereco, cidade, estado, cep) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nome, $email, $senha_hash, $telefone, $endereco, $cidade, $estado, $cep]);
                
                $sucesso = 'Cadastro realizado com sucesso! Você já pode fazer login.';
                
                // Limpar campos do formulário
                $nome = $email = $telefone = $endereco = $cidade = $estado = $cep = '';
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
                    <h1>Criar Conta</h1>
                    <p>Junte-se à nossa comunidade de leitores e criadores</p>
                </div>
                
                <?php if ($sucesso): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $sucesso; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($erro): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $erro; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" class="auth-form">
                    <div class="form-section">
                        <h3>Informações Pessoais</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" id="nome" name="nome" class="form-input" 
                                       value="<?php echo htmlspecialchars($nome ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" id="email" name="email" class="form-input" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="tel" id="telefone" name="telefone" class="form-input" 
                                       value="<?php echo htmlspecialchars($telefone ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Segurança</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="senha" class="form-label">Senha *</label>
                                <div class="password-input">
                                    <input type="password" id="senha" name="senha" class="form-input" required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('senha')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-help">Mínimo de 6 caracteres</small>
                            </div>
                            <div class="form-group">
                                <label for="confirmar_senha" class="form-label">Confirmar Senha *</label>
                                <div class="password-input">
                                    <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input" required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('confirmar_senha')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Endereço (Opcional)</h3>
                        <div class="form-group">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" id="endereco" name="endereco" class="form-input" 
                                   value="<?php echo htmlspecialchars($endereco ?? ''); ?>">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" id="cidade" name="cidade" class="form-input" 
                                       value="<?php echo htmlspecialchars($cidade ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="estado" class="form-label">Estado</label>
                                <select id="estado" name="estado" class="form-input">
                                    <option value="">Selecione</option>
                                    <option value="AC" <?php echo (isset($estado) && $estado == 'AC') ? 'selected' : ''; ?>>Acre</option>
                                    <option value="AL" <?php echo (isset($estado) && $estado == 'AL') ? 'selected' : ''; ?>>Alagoas</option>
                                    <option value="AP" <?php echo (isset($estado) && $estado == 'AP') ? 'selected' : ''; ?>>Amapá</option>
                                    <option value="AM" <?php echo (isset($estado) && $estado == 'AM') ? 'selected' : ''; ?>>Amazonas</option>
                                    <option value="BA" <?php echo (isset($estado) && $estado == 'BA') ? 'selected' : ''; ?>>Bahia</option>
                                    <option value="CE" <?php echo (isset($estado) && $estado == 'CE') ? 'selected' : ''; ?>>Ceará</option>
                                    <option value="DF" <?php echo (isset($estado) && $estado == 'DF') ? 'selected' : ''; ?>>Distrito Federal</option>
                                    <option value="ES" <?php echo (isset($estado) && $estado == 'ES') ? 'selected' : ''; ?>>Espírito Santo</option>
                                    <option value="GO" <?php echo (isset($estado) && $estado == 'GO') ? 'selected' : ''; ?>>Goiás</option>
                                    <option value="MA" <?php echo (isset($estado) && $estado == 'MA') ? 'selected' : ''; ?>>Maranhão</option>
                                    <option value="MT" <?php echo (isset($estado) && $estado == 'MT') ? 'selected' : ''; ?>>Mato Grosso</option>
                                    <option value="MS" <?php echo (isset($estado) && $estado == 'MS') ? 'selected' : ''; ?>>Mato Grosso do Sul</option>
                                    <option value="MG" <?php echo (isset($estado) && $estado == 'MG') ? 'selected' : ''; ?>>Minas Gerais</option>
                                    <option value="PA" <?php echo (isset($estado) && $estado == 'PA') ? 'selected' : ''; ?>>Pará</option>
                                    <option value="PB" <?php echo (isset($estado) && $estado == 'PB') ? 'selected' : ''; ?>>Paraíba</option>
                                    <option value="PR" <?php echo (isset($estado) && $estado == 'PR') ? 'selected' : ''; ?>>Paraná</option>
                                    <option value="PE" <?php echo (isset($estado) && $estado == 'PE') ? 'selected' : ''; ?>>Pernambuco</option>
                                    <option value="PI" <?php echo (isset($estado) && $estado == 'PI') ? 'selected' : ''; ?>>Piauí</option>
                                    <option value="RJ" <?php echo (isset($estado) && $estado == 'RJ') ? 'selected' : ''; ?>>Rio de Janeiro</option>
                                    <option value="RN" <?php echo (isset($estado) && $estado == 'RN') ? 'selected' : ''; ?>>Rio Grande do Norte</option>
                                    <option value="RS" <?php echo (isset($estado) && $estado == 'RS') ? 'selected' : ''; ?>>Rio Grande do Sul</option>
                                    <option value="RO" <?php echo (isset($estado) && $estado == 'RO') ? 'selected' : ''; ?>>Rondônia</option>
                                    <option value="RR" <?php echo (isset($estado) && $estado == 'RR') ? 'selected' : ''; ?>>Roraima</option>
                                    <option value="SC" <?php echo (isset($estado) && $estado == 'SC') ? 'selected' : ''; ?>>Santa Catarina</option>
                                    <option value="SP" <?php echo (isset($estado) && $estado == 'SP') ? 'selected' : ''; ?>>São Paulo</option>
                                    <option value="SE" <?php echo (isset($estado) && $estado == 'SE') ? 'selected' : ''; ?>>Sergipe</option>
                                    <option value="TO" <?php echo (isset($estado) && $estado == 'TO') ? 'selected' : ''; ?>>Tocantins</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" id="cep" name="cep" class="form-input" 
                                       value="<?php echo htmlspecialchars($cep ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <label class="checkbox-label">
                            <input type="checkbox" name="termos" required>
                            <span class="checkmark"></span>
                            Aceito os <a href="#" class="link">Termos de Uso</a> e <a href="#" class="link">Política de Privacidade</a> *
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-user-plus"></i>
                        Criar Conta
                    </button>
                </form>
                
                <div class="auth-divider">
                    <span>ou</span>
                </div>
                
                <div class="auth-footer">
                    <p>Já tem uma conta? <a href="login.php" class="auth-link">Faça login aqui</a></p>
                </div>
            </div>
            
            <div class="auth-benefits">
                <h2>Por que se cadastrar?</h2>
                <div class="benefits-list">
                    <div class="benefit-item">
                        <i class="fas fa-book"></i>
                        <div>
                            <h3>Livros Personalizados</h3>
                            <p>Participe da criação de livros únicos feitos por crianças</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-shipping-fast"></i>
                        <div>
                            <h3>Entrega Rápida</h3>
                            <p>Receba seus livros em casa com frete grátis</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-gift"></i>
                        <div>
                            <h3>Ofertas Exclusivas</h3>
                            <p>Acesso a promoções e lançamentos em primeira mão</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <h3>Comunidade</h3>
                            <p>Faça parte de uma comunidade apaixonada por literatura infantil</p>
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

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e0e0e0;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.form-section h3 {
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-row:last-child {
    margin-bottom: 0;
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

.form-help {
    color: var(--text-light);
    font-size: 0.8rem;
    margin-top: 0.5rem;
    display: block;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    cursor: pointer;
    color: var(--text-light);
    font-size: 0.9rem;
    line-height: 1.4;
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
    flex-shrink: 0;
    margin-top: 0.1rem;
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

.link {
    color: var(--primary-color);
    text-decoration: none;
    transition: var(--transition);
}

.link:hover {
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

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
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
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleButton = passwordInput.parentElement.querySelector('.password-toggle i');
    
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

// Validação de senha em tempo real
document.getElementById('confirmar_senha').addEventListener('input', function() {
    const senha = document.getElementById('senha').value;
    const confirmarSenha = this.value;
    
    if (confirmarSenha && senha !== confirmarSenha) {
        this.style.borderColor = '#dc3545';
    } else {
        this.style.borderColor = '#e0e0e0';
    }
});
</script>

<?php include 'includes/footer.php'; ?>

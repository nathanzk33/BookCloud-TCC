<?php
require_once 'includes/conexao.php';

$pageTitle = 'Contato';

$mensagem_sucesso = '';
$mensagem_erro = '';

// Processar formulário de contato
if ($_POST) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    
    // Validações básicas
    if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem)) {
        $mensagem_erro = 'Por favor, preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = 'Por favor, insira um email válido.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, telefone, assunto, mensagem) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $telefone, $assunto, $mensagem]);
            $mensagem_sucesso = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
            
            // Limpar campos do formulário
            $nome = $email = $telefone = $assunto = $mensagem = '';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao enviar mensagem. Tente novamente mais tarde.';
        }
    }
}

include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Entre em Contato</h1>
        
        <div class="contact-container">
            <!-- Informações de Contato -->
            <div class="contact-info">
                <h2>Fale Conosco</h2>
                <p style="margin-bottom: 2rem; color: var(--text-light); font-size: 1.1rem;">
                    Tem alguma dúvida, sugestão ou quer participar do nosso projeto? 
                    Estamos aqui para ajudar e ouvir você!
                </p>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p>bookcloud@gmail.com</p>
                            <small>Respondemos em até 24 horas</small>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Telefone</h3>
                            <p>(14) 991714230</p>
                            <small>Segunda a Sexta, 9h às 18h</small>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Endereço</h3>
                            <p>Rua treze de maio, 2-12<br>Bauru-SP</p>
                            <small>CEP:17010-230</small>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Horário de Funcionamento</h3>
                            <p>Segunda a Sexta: 9h às 18h<br>Sábado: 9h às 12h</p>
                            <small>Domingo: Fechado</small>
                        </div>
                    </div>
                </div>
                
                <!-- Redes Sociais -->
                <div class="social-section">
                    <h3>Siga-nos nas Redes Sociais</h3>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        
                    </div>
                </div>
            </div>
            
            <!-- Formulário de Contato -->
            <div class="contact-form-container">
                <div class="contact-form">
                    <h2>Envie sua Mensagem</h2>
                    
                    <?php if ($mensagem_sucesso): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $mensagem_sucesso; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($mensagem_erro): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $mensagem_erro; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
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
                            <div class="form-group">
                                <label for="assunto" class="form-label">Assunto *</label>
                                <select id="assunto" name="assunto" class="form-input" required>
                                    <option value="">Selecione um assunto</option>
                                    <option value="Dúvida sobre livros" <?php echo (isset($assunto) && $assunto == 'Dúvida sobre livros') ? 'selected' : ''; ?>>Dúvida sobre livros</option>
                                    <option value="Participar como autor" <?php echo (isset($assunto) && $assunto == 'Participar como autor') ? 'selected' : ''; ?>>Participar como autor</option>
                                    <option value="Problema com pedido" <?php echo (isset($assunto) && $assunto == 'Problema com pedido') ? 'selected' : ''; ?>>Problema com pedido</option>
                                    <option value="Sugestão" <?php echo (isset($assunto) && $assunto == 'Sugestão') ? 'selected' : ''; ?>>Sugestão</option>
                                    <option value="Parceria" <?php echo (isset($assunto) && $assunto == 'Parceria') ? 'selected' : ''; ?>>Parceria</option>
                                    <option value="Outro" <?php echo (isset($assunto) && $assunto == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensagem" class="form-label">Mensagem *</label>
                            <textarea id="mensagem" name="mensagem" class="form-input form-textarea" 
                                      placeholder="Conte-nos como podemos ajudar..." required><?php echo htmlspecialchars($mensagem ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    margin-bottom: 4rem;
}

.contact-info {
    padding: 3rem;
}

.contact-info h2 {
    margin-bottom: 2rem;
    color: var(--text-dark);
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    margin-bottom: 3rem;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--bg-light);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.contact-method:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow);
}

.contact-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.contact-details h3 {
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.contact-details p {
    margin-bottom: 0.5rem;
    color: var(--text-light);
    font-weight: 500;
}

.contact-details small {
    color: var(--text-light);
    font-size: 0.9rem;
}

.social-section {
    padding-top: 2rem;
    border-top: 1px solid #e0e0e0;
}

.social-section h3 {
    margin-bottom: 1.5rem;
    color: var(--text-dark);
}

.social-links {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.social-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: var(--border-radius);
    transition: var(--transition);
    font-weight: 500;
}

.social-link:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.contact-form-container {
    padding: 3rem;
}

.contact-form h2 {
    margin-bottom: 2rem;
    color: var(--text-dark);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
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

.btn-large {
    padding: 1.2rem 2rem;
    font-size: 1.1rem;
    width: 100%;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .contact-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .contact-method {
        flex-direction: column;
        text-align: center;
    }
    
    .social-links {
        justify-content: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>

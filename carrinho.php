<?php
session_start();
require_once 'includes/conexao.php';

$pageTitle = 'Carrinho de Compras';

include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Carrinho de Compras</h1>
        
        <div class="cart-container">
            <div class="cart-items" id="cart-items">
                <!-- Os itens do carrinho serão carregados via JavaScript -->
            </div>
            
            <div class="cart-summary" id="cart-summary" style="display: none;">
                <!-- O resumo será carregado via JavaScript -->
            </div>
        </div>
        
        <!-- Se o carrinho estiver vazio, mostrar mensagem -->
        <div class="empty-cart" id="empty-cart" style="display: none;">
            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ccc; margin-bottom: 2rem;"></i>
            <h2>Seu carrinho está vazio</h2>
            <p style="color: var(--text-light); margin-bottom: 2rem;">
                Que tal dar uma olhada nos nossos livros incríveis?
            </p>
            <a href="catalogo.php" class="btn btn-primary">
                <i class="fas fa-book"></i>
                Explorar Catálogo
            </a>
        </div>
    </div>
</section>

<!-- Checkout Modal -->
<div id="checkout-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Finalizar Compra</h2>
            <button class="modal-close" onclick="closeCheckoutModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form id="checkout-form">
                <div class="form-section">
                    <h3>Informações Pessoais</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome" class="form-label">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" id="email" name="email" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefone" class="form-label">Telefone *</label>
                            <input type="tel" id="telefone" name="telefone" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="cpf" class="form-label">CPF *</label>
                            <input type="text" id="cpf" name="cpf" class="form-input" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Endereço de Entrega</h3>
                    <div class="form-row">
                        <div class="form-group" style="flex: 2;">
                            <label for="endereco" class="form-label">Endereço *</label>
                            <input type="text" id="endereco" name="endereco" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="numero" class="form-label">Número *</label>
                            <input type="text" id="numero" name="numero" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" id="complemento" name="complemento" class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="bairro" class="form-label">Bairro *</label>
                            <input type="text" id="bairro" name="bairro" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cidade" class="form-label">Cidade *</label>
                            <input type="text" id="cidade" name="cidade" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="estado" class="form-label">Estado *</label>
                            <select id="estado" name="estado" class="form-input" required>
                                <option value="">Selecione</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cep" class="form-label">CEP *</label>
                            <input type="text" id="cep" name="cep" class="form-input" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Forma de Pagamento</h3>
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="pagamento" value="pix" checked>
                            <div class="payment-card">
                                <i class="fas fa-qrcode"></i>
                                <span>PIX</span>
                                <small>Pagamento instantâneo</small>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="pagamento" value="cartao">
                            <div class="payment-card">
                                <i class="fas fa-credit-card"></i>
                                <span>Cartão de Crédito</span>
                                <small>Visa, Mastercard, Elo</small>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="pagamento" value="boleto">
                            <div class="payment-card">
                                <i class="fas fa-barcode"></i>
                                <span>Boleto Bancário</span>
                                <small>Vencimento em 3 dias</small>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Resumo do Pedido</h3>
                    <div id="checkout-summary">
                        <!-- Resumo será preenchido via JavaScript -->
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeCheckoutModal()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-lock"></i>
                        Finalizar Compra
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.cart-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    margin-bottom: 1.5rem;
    transition: var(--transition);
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.cart-item-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.cart-item-details {
    flex: 1;
}

.cart-item-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.cart-item-author {
    color: var(--text-light);
    margin-bottom: 1rem;
}

.cart-item-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--bg-light);
    padding: 0.5rem;
    border-radius: var(--border-radius);
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: var(--primary-color);
    color: var(--white);
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.quantity-btn:hover {
    background: #e55656;
    transform: scale(1.1);
}

.quantity {
    font-weight: 700;
    font-size: 1.2rem;
    min-width: 40px;
    text-align: center;
    color: var(--text-dark);
}

.remove-btn {
    background: var(--primary-color);
    color: var(--white);
    border: none;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.remove-btn:hover {
    background: #e55656;
    transform: translateY(-2px);
}

.cart-summary {
    background: var(--white);
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.cart-summary h3 {
    margin-bottom: 1.5rem;
    color: var(--text-dark);
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 1.5rem 0;
    padding: 1rem;
    background: var(--bg-light);
    border-radius: var(--border-radius);
}

.cart-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 2rem;
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.modal-content {
    background: var(--white);
    border-radius: var(--border-radius);
    max-width: 800px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-hover);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    border-bottom: 1px solid #e0e0e0;
}

.modal-header h2 {
    color: var(--text-dark);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-light);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: var(--transition);
}

.modal-close:hover {
    background: var(--bg-light);
    color: var(--text-dark);
}

.modal-body {
    padding: 2rem;
}

.form-section {
    margin-bottom: 3rem;
}

.form-section h3 {
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.payment-option {
    cursor: pointer;
}

.payment-option input {
    display: none;
}

.payment-card {
    padding: 1.5rem;
    border: 2px solid #e0e0e0;
    border-radius: var(--border-radius);
    text-align: center;
    transition: var(--transition);
    background: var(--white);
}

.payment-option input:checked + .payment-card {
    border-color: var(--primary-color);
    background: rgba(255, 107, 107, 0.1);
}

.payment-card i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    display: block;
}

.payment-card span {
    display: block;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.payment-card small {
    color: var(--text-light);
    font-size: 0.9rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e0e0e0;
}

@media (max-width: 768px) {
    .cart-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .cart-item {
        flex-direction: column;
        text-align: center;
    }
    
    .cart-item-controls {
        justify-content: center;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Função para abrir modal de checkout
function openCheckoutModal() {
    if (cart.items.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }
    
    // Preencher resumo do pedido
    const summary = document.getElementById('checkout-summary');
    summary.innerHTML = cart.items.map(item => `
        <div class="summary-item">
            <span>${item.title} x${item.quantity}</span>
            <span>R$ ${(item.price * item.quantity).toFixed(2)}</span>
        </div>
    `).join('') + `
        <div class="summary-total">
            <span>Total:</span>
            <span>R$ ${cart.getTotal().toFixed(2)}</span>
        </div>
    `;
    
    document.getElementById('checkout-modal').style.display = 'flex';
}

// Função para fechar modal de checkout
function closeCheckoutModal() {
    document.getElementById('checkout-modal').style.display = 'none';
}

// Função para finalizar compra
function finalizarCompra() {
    const form = document.getElementById('checkout-form');
    if (!form) return;
    
    if (!window.cart || !window.cart.items || window.cart.items.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }
    
    const formData = new FormData(form);
    
    // Validações básicas
    const requiredFields = ['nome', 'email', 'telefone', 'cpf', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'cep'];
    for (let field of requiredFields) {
        if (!formData.get(field)) {
            alert(`Por favor, preencha o campo: ${field}`);
            return;
        }
    }
    
    // Verificar se o usuário está logado
    <?php if (!isset($_SESSION['usuario_id'])): ?>
    alert('Você precisa estar logado para finalizar a compra. Redirecionando para a página de login...');
    window.location.href = 'login.php';
    return;
    <?php endif; ?>
    
    // Preparar dados para envio
    const pedidoData = {
        nome: formData.get('nome'),
        email: formData.get('email'),
        telefone: formData.get('telefone'),
        cpf: formData.get('cpf'),
        endereco: formData.get('endereco'),
        numero: formData.get('numero'),
        complemento: formData.get('complemento') || '',
        bairro: formData.get('bairro'),
        cidade: formData.get('cidade'),
        estado: formData.get('estado'),
        cep: formData.get('cep'),
        pagamento: formData.get('pagamento'),
        itens: window.cart.items
    };
    
    // Desabilitar botão de submit
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    
    // Enviar para o servidor
    fetch('processar_pedido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(pedidoData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pedido realizado com sucesso! Número do pedido: #' + String(data.pedido_id).padStart(6, '0'));
            window.cart.clearCart();
            closeCheckoutModal();
            window.location.href = 'meus-pedidos.php';
        } else {
            alert('Erro ao processar pedido: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar pedido. Tente novamente.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Adicionar event listeners
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            finalizarCompra();
        });
    }
    
    // Fechar modal ao clicar fora
    document.getElementById('checkout-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCheckoutModal();
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>

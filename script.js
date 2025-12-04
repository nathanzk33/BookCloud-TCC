// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
    
    // Smooth scroll animation for logo click
    const logoLink = document.getElementById('logo-link');
    if (logoLink) {
        logoLink.addEventListener('click', function(e) {
            // Se já estamos na index.php ou na raiz, fazer scroll suave para o topo
            const currentPath = window.location.pathname;
            const isIndexPage = currentPath.includes('index.php') || 
                               currentPath.endsWith('/') || 
                               currentPath === '' ||
                               currentPath.endsWith('tcc nathan/');
            
            if (isIndexPage) {
                e.preventDefault();
                // Animação suave de scroll para o topo
                const scrollToTop = () => {
                    const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                    if (currentScroll > 0) {
                        window.requestAnimationFrame(scrollToTop);
                        window.scrollTo(0, currentScroll - (currentScroll / 10));
                    } else {
                        window.scrollTo(0, 0);
                    }
                };
                
                // Usar scroll suave nativo se disponível, senão usar animação customizada
                if ('scrollBehavior' in document.documentElement.style) {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                } else {
                    scrollToTop();
                }
            }
            // Caso contrário, deixar o link funcionar normalmente
        });
    }
    
    // Initialize cart
    initializeCart();
    
    // Initialize filters
    initializeFilters();
    
});

// Event delegation GLOBAL para botões do carrinho (fora do DOMContentLoaded para garantir que funcione)
// Usar capture phase para pegar o evento antes de qualquer outro handler
document.addEventListener('click', function(e) {
    // Encontrar o botão clicado ou seu elemento pai
    let clickedElement = e.target;
    let buttonElement = null;
    
    // Se clicou diretamente no botão
    if (clickedElement.classList && (
        clickedElement.classList.contains('quantity-btn') || 
        clickedElement.classList.contains('remove-btn')
    )) {
        buttonElement = clickedElement;
    } 
    // Se clicou em um elemento filho (ícone, span, etc)
    else {
        buttonElement = clickedElement.closest('.quantity-btn, .remove-btn');
    }
    
    // Se não encontrou um botão, sair
    if (!buttonElement) return;
    
    // Verificar se está dentro do container do carrinho
    const cartContainer = document.querySelector('#cart-items') || document.querySelector('.cart-items');
    if (!cartContainer || !cartContainer.contains(buttonElement)) return;
    
    // Prevenir comportamento padrão
    e.preventDefault();
    e.stopPropagation();
    
    // Obter o ID do item
    let itemId = buttonElement.getAttribute('data-item-id');
    if (!itemId) {
        // Tentar encontrar no elemento pai
        const parentWithId = buttonElement.closest('[data-item-id]');
        if (parentWithId) {
            itemId = parentWithId.getAttribute('data-item-id');
        }
    }
    
    if (!itemId) {
        console.error('Item ID não encontrado no botão:', buttonElement);
        return;
    }
    
    // Verificar se o cart existe
    if (!window.cart) {
        console.error('Cart não está disponível');
        alert('Erro: Carrinho não está disponível. Recarregue a página.');
        return;
    }
    
    // Processar ação baseada no tipo de botão
    if (buttonElement.classList.contains('decrease-btn')) {
        const item = window.cart.items.find(i => String(i.id) === String(itemId));
        if (item && item.quantity > 0) {
            window.cart.updateQuantity(itemId, item.quantity - 1);
        }
    } 
    else if (buttonElement.classList.contains('increase-btn')) {
        const item = window.cart.items.find(i => String(i.id) === String(itemId));
        if (item) {
            window.cart.updateQuantity(itemId, item.quantity + 1);
        }
    } 
    else if (buttonElement.classList.contains('remove-btn')) {
        // Confirmar remoção
        if (confirm('Deseja remover este item do carrinho?')) {
            console.log('Removendo item ID:', itemId);
            console.log('Items antes:', window.cart.items);
            window.cart.removeItem(itemId);
            console.log('Items depois:', window.cart.items);
        }
    }
    
    return false;
}, true); // true = usar capture phase

// Cart functionality
class Cart {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('cart')) || [];
        this.updateCartDisplay();
    }
    
    addItem(book) {
        // Garantir que o ID seja sempre string para consistência
        const bookId = String(book.id);
        const existingItem = this.items.find(item => String(item.id) === bookId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.items.push({
                id: bookId, // Sempre salvar como string
                title: book.title,
                price: parseFloat(book.price),
                image: book.image || 'assets/img/box.jpg',
                quantity: 1
            });
        }
        
        this.saveCart();
        this.updateCartDisplay();
        this.showNotification('Livro adicionado ao carrinho!');
    }
    
    
    removeItem(id) {
        // Converter id para o mesmo tipo usado no array
        const idToRemove = String(id);
        const itemsBefore = this.items.length;
        
        console.log('removeItem chamado com ID:', idToRemove);
        console.log('Items antes:', JSON.parse(JSON.stringify(this.items)));
        
        // Filtrar o item a ser removido
        this.items = this.items.filter(item => {
            const itemId = String(item.id);
            const shouldKeep = itemId !== idToRemove;
            if (!shouldKeep) {
                console.log('Removendo item:', item);
            }
            return shouldKeep;
        });
        
        const itemsAfter = this.items.length;
        console.log('Items depois:', JSON.parse(JSON.stringify(this.items)));
        
        // Verificar se realmente removeu
        if (itemsBefore === itemsAfter) {
            console.error('Item não foi removido. ID procurado:', idToRemove);
            console.error('IDs no carrinho:', this.items.map(i => String(i.id)));
            alert('Erro ao remover item. O ID pode não corresponder. Tente novamente.');
            return;
        }
        
        // Salvar no localStorage primeiro
        this.saveCart();
        console.log('Carrinho salvo no localStorage');
        
        // Forçar atualização imediata do display
        this.updateCartDisplay();
        
        // Mostrar notificação
        this.showNotification('Livro removido do carrinho!');
    }
    
    updateQuantity(id, quantity) {
        // Converter id para string para consistência
        const itemId = String(id);
        const item = this.items.find(item => String(item.id) === itemId);
        if (item) {
            if (quantity <= 0) {
                this.removeItem(itemId);
            } else {
                item.quantity = parseInt(quantity);
                this.saveCart();
                this.updateCartDisplay();
            }
        } else {
            console.error('Item não encontrado para atualizar quantidade. ID:', itemId);
        }
    }
    
    clearCart() {
        this.items = [];
        this.saveCart();
        this.updateCartDisplay();
    }
    
    getTotal() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    }
    
    getItemCount() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    }
    
    saveCart() {
        localStorage.setItem('cart', JSON.stringify(this.items));
    }
    
    updateCartDisplay() {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = this.getItemCount();
        }
        
        // Update cart page if we're on it
        const isCartPage = window.location.pathname.includes('carrinho.php') || 
                          window.location.href.includes('carrinho.php');
        if (isCartPage) {
            // Usar setTimeout para garantir que o DOM esteja atualizado
            setTimeout(() => {
                this.renderCartPage();
            }, 0);
        }
    }
    
    renderCartPage() {
        // Recarregar items do localStorage para garantir sincronização
        const savedItems = JSON.parse(localStorage.getItem('cart')) || [];
        this.items = savedItems;
        
        // Tentar encontrar o container por ID ou classe
        const cartContainer = document.querySelector('#cart-items') || document.querySelector('.cart-items');
        const cartSummary = document.querySelector('#cart-summary') || document.querySelector('.cart-summary');
        
        if (!cartContainer) {
            console.warn('Container do carrinho não encontrado');
            return;
        }
        
        console.log('Renderizando carrinho com', this.items.length, 'itens');
        
        if (this.items.length === 0) {
            cartContainer.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h3>Seu carrinho está vazio</h3>
                    <p>Que tal dar uma olhada nos nossos livros?</p>
                    <a href="catalogo.php" class="btn btn-primary">Ver Catálogo</a>
                </div>
            `;
            if (cartSummary) cartSummary.style.display = 'none';
            return;
        }
        
        // Renderizar itens com IDs garantidos como string
        cartContainer.innerHTML = this.items.map(item => {
            const itemId = String(item.id); // Garantir que é string
            return `
            <div class="cart-item" data-cart-item-id="${itemId}">
                <img src="${item.image || 'assets/img/box.jpg'}" alt="${item.title}" class="cart-item-image" onerror="this.src='assets/img/box.jpg'">
                <div class="cart-item-details">
                    <h3 class="cart-item-title">${item.title}</h3>
                    <p class="cart-item-price">R$ ${parseFloat(item.price).toFixed(2)}</p>
                </div>
                <div class="cart-item-controls">
                    <div class="quantity-control">
                        <button class="quantity-btn decrease-btn" data-item-id="${itemId}" type="button" aria-label="Diminuir quantidade">-</button>
                        <span class="quantity">${item.quantity}</span>
                        <button class="quantity-btn increase-btn" data-item-id="${itemId}" type="button" aria-label="Aumentar quantidade">+</button>
                    </div>
                    <button class="remove-btn" data-item-id="${itemId}" type="button" aria-label="Remover item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        }).join('');
        
        if (cartSummary) {
            cartSummary.innerHTML = `
                <h3>Resumo do Pedido</h3>
                <div class="cart-total">
                    Total: R$ ${this.getTotal().toFixed(2)}
                </div>
                <button class="btn btn-primary" id="checkout-btn" type="button">Finalizar Compra</button>
                <button class="btn btn-outline" id="clear-cart-btn" type="button">Limpar Carrinho</button>
            `;
            cartSummary.style.display = 'block';
            
            // Adicionar event listeners aos botões do resumo
            const checkoutBtn = document.getElementById('checkout-btn');
            const clearCartBtn = document.getElementById('clear-cart-btn');
            
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', () => {
                    if (typeof checkout === 'function') {
                        checkout();
                    }
                });
            }
            
            if (clearCartBtn) {
                clearCartBtn.addEventListener('click', () => {
                    this.clearCart();
                });
            }
        }
    }
    
    
    showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: #4ecdc4;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}

// Initialize cart
let cart;
function initializeCart() {
    cart = new Cart();
    
    // Tornar cart acessível globalmente
    window.cart = cart;
    
    // Add to cart buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookData = {
                id: this.dataset.id,
                title: this.dataset.title,
                price: parseFloat(this.dataset.price),
                image: this.dataset.image
            };
            cart.addItem(bookData);
        });
    });
}

// Filter functionality
function initializeFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const bookCards = document.querySelectorAll('.book-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter books
            bookCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

// Checkout function
function checkout() {
    if (cart.items.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }
    
    // Se existe modal de checkout, abrir ele
    if (typeof openCheckoutModal === 'function') {
        openCheckoutModal();
    } else {
        // Fallback: Simple checkout simulation
        const total = cart.getTotal();
        const confirmMessage = `Finalizar compra de ${cart.getItemCount()} item(ns) por R$ ${total.toFixed(2)}?`;
        
        if (confirm(confirmMessage)) {
            // Here you would typically send the data to a server
            alert('Compra realizada com sucesso! Em breve você receberá um email com os detalhes.');
            cart.clearCart();
            window.location.href = 'index.php';
        }
    }
}

// Search functionality
function initializeSearch() {
    const searchInput = document.querySelector('.search-input');
    const bookCards = document.querySelectorAll('.book-card');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            bookCards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const author = card.querySelector('.card-author').textContent.toLowerCase();
                const description = card.querySelector('.card-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || author.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add fade-in animation
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all cards and sections
document.querySelectorAll('.card, .section').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// Add CSS for fade-in animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .empty-cart {
        text-align: center;
        padding: 3rem;
        color: #666;
    }
`;
document.head.appendChild(style);

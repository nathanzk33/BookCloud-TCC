<?php
session_start();
require_once 'includes/conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Você precisa estar logado para finalizar a compra']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Receber dados do pedido
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['itens']) || empty($data['itens'])) {
    echo json_encode(['success' => false, 'message' => 'Carrinho vazio']);
    exit;
}

// Validar dados obrigatórios
$required_fields = ['nome', 'email', 'telefone', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'cep'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Campo obrigatório faltando: $field"]);
        exit;
    }
}

try {
    $pdo->beginTransaction();
    
    // Montar endereço completo
    $endereco_completo = $data['endereco'] . ', ' . $data['numero'];
    if (!empty($data['complemento'])) {
        $endereco_completo .= ' - ' . $data['complemento'];
    }
    $endereco_completo .= ' - ' . $data['bairro'] . ', ' . $data['cidade'] . '/' . $data['estado'] . ' - CEP: ' . $data['cep'];
    
    // Calcular total
    $total = 0;
    foreach ($data['itens'] as $item) {
        $total += floatval($item['price']) * intval($item['quantity']);
    }
    
    // Inserir pedido
    $stmt = $pdo->prepare("
        INSERT INTO pedidos (usuario_id, total, status, endereco_entrega, observacoes) 
        VALUES (:usuario_id, :total, 'pendente', :endereco, :observacoes)
    ");
    
    $observacoes = "Forma de pagamento: " . $data['pagamento'] . "\n";
    $observacoes .= "CPF: " . $data['cpf'];
    
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':total' => $total,
        ':endereco' => $endereco_completo,
        ':observacoes' => $observacoes
    ]);
    
    $pedido_id = $pdo->lastInsertId();
    
    // Inserir itens do pedido
    $stmt_item = $pdo->prepare("
        INSERT INTO pedido_itens (pedido_id, livro_id, quantidade, preco_unitario) 
        VALUES (:pedido_id, :livro_id, :quantidade, :preco_unitario)
    ");
    
    foreach ($data['itens'] as $item) {
        $stmt_item->execute([
            ':pedido_id' => $pedido_id,
            ':livro_id' => $item['id'],
            ':quantidade' => $item['quantity'],
            ':preco_unitario' => $item['price']
        ]);
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Pedido realizado com sucesso!',
        'pedido_id' => $pedido_id
    ]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erro ao processar pedido: ' . $e->getMessage()]);
}


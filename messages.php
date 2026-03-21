<?php
// Page directe des messages

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'models/Message.php';

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$messageModel = new Message();
$userId = $_SESSION['user_id'];

// Récupérer les conversations
$conversations = $messageModel->getConversations($userId);
$unreadCount = $messageModel->getUnreadCount($userId);

// Si on ouvre une conversation spécifique
$selectedConversation = null;
$messages = [];
$otherUserId = $_GET['user_id'] ?? null;
$annonceId = $_GET['annonce_id'] ?? null;

if ($otherUserId && $annonceId) {
    // Vérifier que l'utilisateur a le droit d'accéder à cette conversation
    $hasAccess = false;
    foreach ($conversations as $conv) {
        if ($conv['other_user_id'] == $otherUserId && $conv['annonce_id'] == $annonceId) {
            $hasAccess = true;
            $selectedConversation = $conv;
            break;
        }
    }
    
    if ($hasAccess) {
        $messages = $messageModel->getMessages($userId, $otherUserId, $annonceId);
    }
}

// Envoyer un nouveau message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send') {
    $receiverId = $_POST['receiver_id'];
    $annonceId = $_POST['annonce_id'];
    $content = trim($_POST['content']);
    
    if (!empty($content) && $receiverId && $annonceId) {
        try {
            $messageModel->send($userId, $receiverId, $annonceId, $content);
            
            // Rediriger pour éviter les double soumissions
            header("Location: messages.php?user_id=$receiverId&annonce_id=$annonceId");
            exit;
        } catch (Exception $e) {
            $error = "Erreur lors de l'envoi du message";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - TerangaHomes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            height: 100vh;
            overflow: hidden;
        }
        
        .navbar {
            background: white !important;
            border-bottom: 3px solid #0066cc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .navbar-brand {
            color: #0066cc !important;
            font-weight: 700;
        }
        
        .messages-container {
            display: flex;
            height: calc(100vh - 76px);
            margin-top: 76px;
        }
        
        .conversations-list {
            width: 350px;
            background: white;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
        }
        
        .conversation-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .conversation-item:hover {
            background: #f8f9fa;
        }
        
        .conversation-item.active {
            background: #e3f2fd;
            border-left: 4px solid #0066cc;
        }
        
        .conversation-name {
            font-weight: 600;
            color: #333;
        }
        
        .conversation-preview {
            font-size: 0.85rem;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .conversation-time {
            font-size: 0.75rem;
            color: #999;
        }
        
        .unread-badge {
            background: #0066cc;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }
        
        .chat-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #fafafa;
        }
        
        .message {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
        }
        
        .message.sent {
            justify-content: flex-end;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 0.75rem 1rem;
            border-radius: 18px;
            word-wrap: break-word;
        }
        
        .message.received .message-bubble {
            background: white;
            border: 1px solid #e0e0e0;
            border-top-left-radius: 4px;
        }
        
        .message.sent .message-bubble {
            background: #0066cc;
            color: white;
            border-top-right-radius: 4px;
        }
        
        .message-time {
            font-size: 0.7rem;
            color: #999;
            margin-top: 0.25rem;
        }
        
        .message.sent .message-time {
            text-align: right;
        }
        
        .chat-input {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            background: white;
        }
        
        .empty-state {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
        }
        
        .no-conversations {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary me-3">
                    <i class="fas fa-envelope me-1"></i>
                    <?= $unreadCount ?> non lu<?= $unreadCount > 1 ? 's' : '' ?>
                </span>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a></li>
                        <li><a class="dropdown-item" href="my-annonces">
                            <i class="fas fa-list me-2"></i>Mes annonces
                        </a></li>
                        <li><a class="dropdown-item" href="favorites.php">
                            <i class="fas fa-heart me-2"></i>Mes favoris
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Messages Container -->
    <div class="messages-container">
        <!-- Conversations List -->
        <div class="conversations-list">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>Messages
                </h5>
            </div>
            
            <?php if (empty($conversations)): ?>
                <div class="no-conversations text-center">
                    <div>
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <h6>Aucune conversation</h6>
                        <p class="text-muted">Commencez par contacter des propriétaires</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($conversations as $conv): ?>
                    <a href="messages.php?user_id=<?= $conv['other_user_id'] ?>&annonce_id=<?= $conv['annonce_id'] ?>" 
                       class="conversation-item <?= ($selectedConversation && $selectedConversation['other_user_id'] == $conv['other_user_id']) ? 'active' : '' ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="conversation-name"><?= htmlspecialchars($conv['prenom'] . ' ' . $conv['nom']) ?></span>
                                    <span class="conversation-time"><?= date('H:i', strtotime($conv['last_message_time'])) ?></span>
                                </div>
                                <div class="conversation-preview"><?= htmlspecialchars($conv['annonce_titre']) ?></div>
                            </div>
                            <?php if ($conv['unread_count'] > 0): ?>
                                <div class="unread-badge ms-2"><?= $conv['unread_count'] ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <?php if ($selectedConversation): ?>
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= htmlspecialchars($selectedConversation['prenom'] . ' ' . $selectedConversation['nom']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($selectedConversation['annonce_titre']) ?></small>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="chat-messages" id="chatMessages">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?= $msg['sender_id'] == $userId ? 'sent' : 'received' ?>">
                            <div class="message-bubble">
                                <?= nl2br(htmlspecialchars($msg['content'])) ?>
                                <div class="message-time">
                                    <?= date('H:i', strtotime($msg['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Chat Input -->
                <div class="chat-input">
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="action" value="send">
                        <input type="hidden" name="receiver_id" value="<?= $otherUserId ?>">
                        <input type="hidden" name="annonce_id" value="<?= $annonceId ?>">
                        <input type="text" class="form-control" name="content" placeholder="Tapez votre message..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="text-center">
                        <i class="fas fa-comments fa-4x mb-3"></i>
                        <h5>Sélectionnez une conversation</h5>
                        <p class="text-muted">Choisissez une conversation dans la liste pour commencer à discuter</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Auto-scroll vers le bas du chat
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
    
    // Auto-refresh des messages (optionnel)
    setInterval(function() {
        // Rafraîchir la page toutes les 30 secondes pour les nouveaux messages
        if (document.querySelector('.chat-messages')) {
            // location.reload();
        }
    }, 30000);
    </script>
</body>
</html>

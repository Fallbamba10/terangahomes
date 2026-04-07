<?php
// Système de messagerie complet
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// Traitement des actions
$action = $_GET['action'] ?? '';

if ($action === 'send_message' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiverId = $_POST['receiver_id'] ?? '';
    $annonceId = $_POST['annonce_id'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if (!empty($receiverId) && !empty($message)) {
        $db->execute("INSERT INTO messages (sender_id, receiver_id, annonce_id, message, created_at) VALUES (?, ?, ?, ?, NOW())", 
                   [$userId, $receiverId, $annonceId, $message]);
        
        $success = "Message envoyé avec succès";
        header("Location: messaging_system.php?success=" . urlencode($success));
        exit;
    }
}

if ($action === 'mark_read' && isset($_GET['message_id'])) {
    $messageId = $_GET['message_id'];
    $db->execute("UPDATE messages SET is_read = 1 WHERE id = ? AND receiver_id = ?", [$messageId, $userId]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'delete_message' && isset($_GET['message_id'])) {
    $messageId = $_GET['message_id'];
    $db->execute("DELETE FROM messages WHERE id = ? AND (sender_id = ? OR receiver_id = ?)", [$messageId, $userId, $userId]);
    $success = "Message supprimé";
    header("Location: messaging_system.php?success=" . urlencode($success));
    exit;
}

// Récupérer les conversations
$conversations = $db->fetchAll("
    SELECT DISTINCT 
        CASE 
            WHEN m.sender_id = ? THEN m.receiver_id 
            ELSE m.sender_id 
        END as other_user_id,
        u.prenom, u.nom, u.email,
        MAX(m.created_at) as last_message_time,
        (SELECT COUNT(*) FROM messages WHERE is_read = 0 AND receiver_id = ? AND 
         ((sender_id = CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END) AND 
          receiver_id = ?)) as unread_count,
        (SELECT message FROM messages ORDER BY created_at DESC LIMIT 1) as last_message
    FROM messages m
    JOIN users u ON (CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END) = u.id
    WHERE (m.sender_id = ? OR m.receiver_id = ?)
    GROUP BY other_user_id, u.prenom, u.nom, u.email
    ORDER BY last_message_time DESC
", [$userId, $userId, $userId, $userId, $userId, $userId, $userId]);

// Récupérer les messages avec une conversation spécifique si sélectionnée
$selectedConversation = $_GET['conversation'] ?? '';
$messages = [];
$otherUser = null;

if ($selectedConversation) {
    $messages = $db->fetchAll("
        SELECT m.*, u.prenom as sender_prenom, u.nom as sender_nom, a.titre as annonce_titre
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        LEFT JOIN annonces a ON m.annonce_id = a.id
        WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))
        ORDER BY m.created_at ASC
    ", [$userId, $selectedConversation, $selectedConversation, $userId]);
    
    // Marquer les messages comme lus
    $db->execute("UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ?", [$userId, $selectedConversation]);
    
    $otherUser = $db->fetch("SELECT * FROM users WHERE id = ?", [$selectedConversation]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerangaHomes - Messagerie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .messaging-container {
            height: 100vh;
            background: #f8f9fa;
        }
        
        .conversations-list {
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
            background: white;
        }
        
        .conversation-item {
            padding: 15px;
            border-bottom: 1px solid #f1f3f4;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .conversation-item:hover {
            background: #f8f9fa;
        }
        
        .conversation-item.active {
            background: #e3f2fd;
            border-left: 4px solid #003580;
        }
        
        .conversation-item.unread {
            background: #fff3cd;
        }
        
        .messages-area {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .messages-header {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #dee2e6;
        }
        
        .messages-list {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            margin-bottom: 10px;
            word-wrap: break-word;
        }
        
        .message-sent {
            background: #003580;
            color: white;
            margin-left: auto;
        }
        
        .message-received {
            background: white;
            color: #333;
            border: 1px solid #e9ecef;
        }
        
        .message-input-area {
            padding: 20px;
            background: white;
            border-top: 1px solid #dee2e6;
        }
        
        .unread-badge {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .last-message {
            font-size: 0.85rem;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .message-time {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="messaging-container">
        <div class="row g-0 h-100">
            <!-- Liste des conversations -->
            <div class="col-md-4 conversations-list">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Messagerie</h5>
                </div>
                
                <?php foreach ($conversations as $conv): ?>
                    <div class="conversation-item <?= $selectedConversation == $conv['other_user_id'] ? 'active' : '' ?> <?= $conv['unread_count'] > 0 ? 'unread' : '' ?>"
                         onclick="window.location.href='messaging_system.php?conversation=<?= $conv['other_user_id'] ?>'">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong><?= htmlspecialchars($conv['prenom'] . ' ' . $conv['nom']) ?></strong>
                                <div class="last-message"><?= htmlspecialchars($conv['last_message'] ?? '') ?></div>
                            </div>
                            <div class="text-end">
                                <small><?= date('H:i', strtotime($conv['last_message_time'])) ?></small>
                                <?php if ($conv['unread_count'] > 0): ?>
                                    <span class="unread-badge ms-1"><?= $conv['unread_count'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($conversations)): ?>
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Aucune conversation</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Zone de messages -->
            <div class="col-md-8 messages-area">
                <?php if ($selectedConversation && $otherUser): ?>
                    <!-- En-tête de conversation -->
                    <div class="messages-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?= htmlspecialchars($otherUser['prenom'] . ' ' . $otherUser['nom']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($otherUser['email']) ?></small>
                            </div>
                            <div>
                                <a href="annonces_direct_fixed.php" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i>Nouvelle annonce
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages -->
                    <div class="messages-list">
                        <?php foreach ($messages as $msg): ?>
                            <div class="message-bubble <?= $msg['sender_id'] == $userId ? 'message-sent' : 'message-received' ?>">
                                <div><?= htmlspecialchars($msg['message']) ?></div>
                                <div class="message-time"><?= date('H:i', strtotime($msg['created_at'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Zone de saisie -->
                    <div class="message-input-area">
                        <form method="post" action="messaging_system.php?action=send_message">
                            <input type="hidden" name="receiver_id" value="<?= $selectedConversation ?>">
                            <div class="input-group">
                                <input type="text" class="form-control" name="message" placeholder="Tapez votre message..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                <?php else: ?>
                    <!-- Vue par défaut -->
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center">
                            <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Sélectionnez une conversation</h5>
                            <p class="text-muted">Choisissez une conversation dans la liste pour commencer à discuter</p>
                            <a href="accueil_booking_fixed.php" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Retour à l'accueil
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-scroll vers le bas des messages
        const messagesList = document.querySelector('.messages-list');
        if (messagesList) {
            messagesList.scrollTop = messagesList.scrollHeight;
        }
        
        // Rafraîchir les messages toutes les 30 secondes
        setInterval(() => {
            if (window.location.search.includes('conversation=')) {
                window.location.reload();
            }
        }, 30000);
    </script>
</body>
</html>

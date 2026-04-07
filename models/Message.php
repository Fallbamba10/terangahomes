<?php
class Message {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function send($senderId, $receiverId, $annonceId, $content) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, annonce_id, content, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $this->db->query($sql, [$senderId, $receiverId, $annonceId, $content]);
        return $this->db->lastInsertId();
    }
    
    public function getConversations($userId) {
        $sql = "SELECT DISTINCT 
                CASE 
                    WHEN m.sender_id = ? THEN m.receiver_id 
                    ELSE m.sender_id 
                END as other_user_id,
                u.nom, u.prenom, u.email,
                a.titre as annonce_titre, a.id as annonce_id,
                MAX(m.created_at) as last_message_time,
                (SELECT COUNT(*) FROM messages m2 
                 WHERE ((m2.sender_id = ? AND m2.receiver_id = other_user_id) OR 
                        (m2.sender_id = other_user_id AND m2.receiver_id = ?)) 
                 AND m2.is_read = 0 AND m2.sender_id != ?) as unread_count
                FROM messages m
                JOIN users u ON (u.id = CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END)
                JOIN annonces a ON a.id = m.annonce_id
                WHERE (m.sender_id = ? OR m.receiver_id = ?)
                GROUP BY other_user_id, annonce_id
                ORDER BY last_message_time DESC";
        
        return $this->db->fetchAll($sql, [
            $userId, $userId, $userId, $userId, $userId, $userId, $userId
        ]);
    }
    
    public function getMessages($userId, $otherUserId, $annonceId, $page = 1, $limit = 50) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT m.*, u.nom as sender_nom, u.prenom as sender_prenom
                FROM messages m
                JOIN users u ON u.id = m.sender_id
                WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR 
                       (m.sender_id = ? AND m.receiver_id = ?))
                AND m.annonce_id = ?
                ORDER BY m.created_at ASC
                LIMIT $limit OFFSET $offset";
        
        $messages = $this->db->fetchAll($sql, [
            $userId, $otherUserId, $otherUserId, $userId, $annonceId
        ]);
        
        // Marquer comme lus les messages reçus
        $this->markAsRead($userId, $otherUserId, $annonceId);
        
        return $messages;
    }
    
    public function markAsRead($userId, $senderId, $annonceId) {
        $sql = "UPDATE messages SET is_read = 1 
                WHERE receiver_id = ? AND sender_id = ? AND annonce_id = ? AND is_read = 0";
        return $this->db->query($sql, [$userId, $senderId, $annonceId]);
    }
    
    public function getUnreadCount($userId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_read = 0",
            [$userId]
        );
        return $result['total'] ?? 0;
    }
    
    public function getAnnonceMessages($annonceId, $userId) {
        $sql = "SELECT DISTINCT u.id, u.nom, u.prenom,
                (SELECT COUNT(*) FROM messages m2 
                 WHERE m2.sender_id = u.id AND m2.receiver_id = ? AND m2.annonce_id = ? AND m2.is_read = 0) as unread_count,
                (SELECT m3.contenu FROM messages m3 
                 WHERE m3.sender_id = u.id AND m3.annonce_id = ? 
                 ORDER BY m3.created_at DESC LIMIT 1) as last_message,
                (SELECT m3.created_at FROM messages m3 
                 WHERE m3.sender_id = u.id AND m3.annonce_id = ? 
                 ORDER BY m3.created_at DESC LIMIT 1) as last_message_time
                FROM messages m
                JOIN users u ON u.id = m.sender_id
                WHERE m.annonce_id = ? AND m.receiver_id = ?
                GROUP BY u.id
                ORDER BY last_message_time DESC";
        
        return $this->db->fetchAll($sql, [$userId, $annonceId, $annonceId, $annonceId, $annonceId, $userId]);
    }
}

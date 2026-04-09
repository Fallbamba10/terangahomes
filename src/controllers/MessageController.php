<?php
require_once '../core/Controller.php';

class MessageController extends Controller {
    
    public function index() {
        $this->requireAuth();
        $this->view('messages/index');
    }
    
    public function show($id) {
        $this->requireAuth();
        $this->view('messages/show');
    }
    
    public function send() {
        $this->requireAuth();
        // TODO: Implémenter l'envoi de message
        $this->json(['success' => true, 'message' => 'Message envoyé']);
    }
    
    public function getMessages($receiver_id) {
        $this->requireAuth();
        // TODO: Implémenter la récupération des messages
        $this->json(['messages' => []]);
    }
}

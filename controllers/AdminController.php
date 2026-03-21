<?php
class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index() {
        $this->view('admin/index');
    }
    
    public function users() {
        $this->view('admin/users');
    }
    
    public function annonces() {
        $this->view('admin/annonces');
    }
    
    public function toggleUser($id) {
        // TODO: Implémenter l'activation/désactivation utilisateur
        $this->json(['success' => true]);
    }
    
    public function toggleAnnonce($id) {
        // TODO: Implémenter l'activation/désactivation annonce
        $this->json(['success' => true]);
    }
}

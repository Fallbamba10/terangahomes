<?php
class UserController extends Controller {
    
    public function dashboard() {
        $this->requireAuth();
        $this->view('user/dashboard');
    }
    
    public function profile() {
        $this->requireAuth();
        $this->view('user/profile');
    }
    
    public function updateProfile() {
        $this->requireAuth();
        // TODO: Implémenter la mise à jour du profil
        $this->redirect('/profile');
    }
    
    public function favorites() {
        $this->requireAuth();
        $this->view('user/favorites');
    }
}

<?php
class InteractionController extends Controller {
    
    public function addFavorite($id) {
        $this->requireAuth();
        // TODO: Implémenter l'ajout aux favoris
        $this->json(['success' => true, 'message' => 'Ajouté aux favoris']);
    }
    
    public function removeFavorite($id) {
        $this->requireAuth();
        // TODO: Implémenter le retrait des favoris
        $this->json(['success' => true, 'message' => 'Retiré des favoris']);
    }
    
    public function addComment($id) {
        $this->requireAuth();
        // TODO: Implémenter l'ajout de commentaire
        $this->json(['success' => true, 'message' => 'Commentaire ajouté']);
    }
}

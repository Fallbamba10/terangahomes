<?php
class Router {
    private $routes = [
        'GET' => [],
        'POST' => []
    ];
    
    public function __construct() {
        $this->defineRoutes();
    }
    
    private function defineRoutes() {
        // Routes principales
        $this->get('/', 'HomeController@index');
        
        // Routes des annonces
        $this->get('/annonces', 'AnnonceController@index');
        $this->get('/annonces/{id}', 'AnnonceController@show');
        $this->get('/search', 'AnnonceController@search');
        $this->get('/annonces/create', 'AnnonceController@create');
        $this->post('/annonces', 'AnnonceController@store');
        $this->get('/annonces/{id}/edit', 'AnnonceController@edit');
        $this->post('/annonces/{id}/update', 'AnnonceController@update');
        $this->post('/annonces/{id}/delete', 'AnnonceController@delete');
        
        // Routes d'authentification
        $this->get('/login', 'AuthController@showLogin');
        $this->post('/login', 'AuthController@login');
        $this->get('/register', 'AuthController@showRegister');
        $this->post('/register', 'AuthController@register');
        $this->post('/logout', 'AuthController@logout');
        
        // Routes utilisateur
        $this->get('/dashboard', 'UserController@dashboard');
        $this->get('/profile', 'UserController@profile');
        $this->post('/profile', 'UserController@updateProfile');
        $this->get('/my-annonces', 'AnnonceController@myAnnonces');
        
        // Routes favoris
        $this->get('/favorites', 'FavoriteController@index');
        $this->post('/favorites/toggle', 'FavoriteController@toggle');
        
        // Routes interactions
        $this->post('/favorites/add/{id}', 'InteractionController@addFavorite');
        $this->post('/favorites/remove/{id}', 'InteractionController@removeFavorite');
        $this->post('/comments/add/{id}', 'InteractionController@addComment');
        
        // Routes messages
        $this->get('/messages', 'MessageController@index');
        $this->get('/messages/{id}', 'MessageController@show');
        $this->post('/messages/send', 'MessageController@send');
        $this->get('/messages/ajax/{receiver_id}', 'MessageController@getMessages');
        
        // Routes admin
        $this->get('/admin', 'AdminController@index');
        $this->get('/admin/users', 'AdminController@users');
        $this->get('/admin/annonces', 'AdminController@annonces');
        $this->post('/admin/users/{id}/toggle', 'AdminController@toggleUser');
        $this->post('/admin/annonces/{id}/toggle', 'AdminController@toggleAnnonce');
        
        // Routes pages spéciales (compatibilité avec fichiers existants)
        $this->get('/accueil', 'HomeController@accueil');
        $this->get('/car-rental', 'HomeController@carRental');
        $this->get('/airport-transfer', 'HomeController@airportTransfer');
        $this->get('/booking-confirmation', 'HomeController@bookingConfirmation');
        $this->get('/payment', 'HomeController@payment');
        $this->get('/favorites-page', 'HomeController@favorites');
    }
    
    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }
    
    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/terangaHomes', '', $uri); // Remove base path
        
        if ($uri === '') {
            $uri = '/';
        }
        
        // Route matching
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callHandler($handler, $matches);
                return;
            }
        }
        
        // 404
        http_response_code(404);
        $this->callHandler('ErrorController@notFound');
    }
    
    private function callHandler($handler, $params = []) {
        list($controllerName, $method) = explode('@', $handler);
        $controllerClass = $controllerName;
        
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
            } else {
                $this->showError("Méthode $method non trouvée dans $controllerClass");
            }
        } else {
            $this->showError("Contrôleur $controllerClass non trouvé");
        }
    }
    
    private function showError($message) {
        if (DEBUG) {
            echo "<h1>Erreur</h1><p>$message</p>";
        } else {
            echo "<h1>Page non trouvée</h1>";
        }
    }
}

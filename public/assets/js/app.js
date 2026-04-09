// Variables globales
const APP_URL = '<?= APP_URL ?>';

// Utilitaires
const Utils = {
    // Afficher un message flash
    showFlash: function(message, type = 'success') {
        const flashDiv = document.createElement('div');
        flashDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        flashDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        flashDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(flashDiv);
        
        setTimeout(() => {
            flashDiv.remove();
        }, 5000);
    },
    
    // Afficher un loader
    showLoader: function(element) {
        element.classList.add('loading');
        element.disabled = true;
    },
    
    // Cacher un loader
    hideLoader: function(element) {
        element.classList.remove('loading');
        element.disabled = false;
    },
    
    // Formater le prix
    formatPrice: function(price) {
        return new Intl.NumberFormat('fr-SN', {
            style: 'currency',
            currency: 'XOF',
            minimumFractionDigits: 0
        }).format(price);
    },
    
    // Formater la date
    formatDate: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-SN', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },
    
    // Valider un email
    isValidEmail: function(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    },
    
    // Valider un téléphone sénégalais
    isValidPhone: function(phone) {
        const regex = /^(\+221|0)?[2379]\d{8}$/;
        return regex.test(phone);
    }
};

// Gestion des favoris
const Favorites = {
    toggle: function(annonceId, button) {
        if (!this.isLoggedIn()) {
            window.location.href = APP_URL + '/login';
            return;
        }
        
        Utils.showLoader(button);
        
        fetch(APP_URL + '/favorites/toggle/' + annonceId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            Utils.hideLoader(button);
            
            if (data.success) {
                const icon = button.querySelector('i');
                if (data.action === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    button.classList.add('active');
                    Utils.showFlash('Ajouté aux favoris', 'success');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    button.classList.remove('active');
                    Utils.showFlash('Retiré des favoris', 'info');
                }
            } else {
                Utils.showFlash(data.message || 'Erreur', 'error');
            }
        })
        .catch(error => {
            Utils.hideLoader(button);
            Utils.showFlash('Erreur de connexion', 'error');
            console.error('Error:', error);
        });
    },
    
    isLoggedIn: function() {
        return document.body.classList.contains('logged-in');
    }
};

// Gestion du chat
const Chat = {
    isOpen: false,
    currentReceiverId: null,
    
    init: function() {
        // Créer le widget de chat
        this.createWidget();
        
        // Charger les conversations
        this.loadConversations();
    },
    
    createWidget: function() {
        const widget = document.createElement('div');
        widget.className = 'chat-widget';
        widget.innerHTML = `
            <div class="chat-button" onclick="Chat.toggle()">
                <i class="fas fa-comments"></i>
                <span class="badge bg-danger" id="unreadCount" style="position: absolute; top: 0; right: 0;">0</span>
            </div>
            <div class="chat-window" id="chatWindow">
                <div class="chat-header bg-primary text-white p-3">
                    <h6 class="mb-0">Messages</h6>
                    <button class="btn btn-sm btn-light float-end" onclick="Chat.toggle()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="chat-conversations p-3" style="height: 350px; overflow-y: auto;">
                    <div class="text-center text-muted">
                        <i class="fas fa-comments fa-2x mb-2"></i>
                        <p>Aucune conversation</p>
                    </div>
                </div>
                <div class="chat-input p-3 border-top">
                    <div class="input-group">
                        <input type="text" class="form-control" id="messageInput" placeholder="Tapez votre message...">
                        <button class="btn btn-primary" onclick="Chat.sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(widget);
    },
    
    toggle: function() {
        const window = document.getElementById('chatWindow');
        this.isOpen = !this.isOpen;
        
        if (this.isOpen) {
            window.classList.add('active');
            this.loadConversations();
        } else {
            window.classList.remove('active');
        }
    },
    
    loadConversations: function() {
        // TODO: Charger les conversations via AJAX
    },
    
    sendMessage: function() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        
        if (!message || !this.currentReceiverId) return;
        
        // TODO: Envoyer le message via AJAX
        input.value = '';
    }
};

// Gestion de la recherche
const Search = {
    init: function() {
        this.setupAutoComplete();
        this.setupFilters();
    },
    
    setupAutoComplete: function() {
        const cityInput = document.querySelector('input[name="ville"]');
        if (!cityInput) return;
        
        // TODO: Implémenter l'autocomplétion des villes
    },
    
    setupFilters: function() {
        // TODO: Implémenter les filtres dynamiques
    }
};

// Gestion des images
const ImageUpload = {
    preview: function(input, previewContainer) {
        const files = input.files;
        const container = document.getElementById(previewContainer);
        
        if (!container) return;
        
        container.innerHTML = '';
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'position-relative d-inline-block m-2';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    },
    
    validate: function(input) {
        const files = input.files;
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        for (let file of files) {
            if (file.size > maxSize) {
                Utils.showFlash(`Le fichier ${file.name} est trop volumineux (max 5MB)`, 'error');
                return false;
            }
            
            if (!allowedTypes.includes(file.type)) {
                Utils.showFlash(`Le fichier ${file.name} n'est pas une image valide`, 'error');
                return false;
            }
        }
        
        return true;
    }
};

// Gestion de la carte (Google Maps)
const Map = {
    map: null,
    markers: [],
    
    init: function(elementId, center = {lat: 14.6928, lng: -17.4467}) {
        if (typeof google === 'undefined') {
            console.error('Google Maps API non chargée');
            return;
        }
        
        this.map = new google.maps.Map(document.getElementById(elementId), {
            center: center,
            zoom: 12
        });
    },
    
    addMarker: function(position, title, info) {
        const marker = new google.maps.Marker({
            position: position,
            map: this.map,
            title: title
        });
        
        if (info) {
            const infoWindow = new google.maps.InfoWindow({
                content: info
            });
            
            marker.addListener('click', function() {
                infoWindow.open(this.map, marker);
            });
        }
        
        this.markers.push(marker);
        return marker;
    },
    
    clearMarkers: function() {
        this.markers.forEach(marker => marker.setMap(null));
        this.markers = [];
    }
};

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les modules
    Search.init();
    
    // Initialiser le chat si l'utilisateur est connecté
    if (document.body.classList.contains('logged-in')) {
        Chat.init();
    }
    
    // Gérer les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Gérer les popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Scroll smooth pour les liens d'ancrage
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
    
    // Animation au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
});

// Export pour utilisation globale
window.TerangaHomes = {
    Utils,
    Favorites,
    Chat,
    Search,
    ImageUpload,
    Map
};

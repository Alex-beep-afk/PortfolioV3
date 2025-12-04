import { Controller } from '@hotwired/stimulus';

/**
 * Contrôleur Stimulus pour les animations de scroll reveal
 * 
 * Ce contrôleur utilise l'Intersection Observer API pour détecter quand
 * les éléments entrent dans le viewport et déclenche des animations fluides.
 * 
 * Attributs data disponibles :
 * - data-controller="scroll-reveal" : Active le contrôleur sur l'élément
 * - data-scroll-reveal-delay-value : Délai avant l'animation (en ms, optionnel, défaut: 0)
 * - data-scroll-reveal-direction-value : Direction de l'animation ('up', 'down', 'left', 'right', optionnel, défaut: 'up')
 * - data-scroll-reveal-threshold-value : Pourcentage de visibilité requis (0-1, optionnel, défaut: 0.1)
 */
export default class extends Controller {
    // Valeurs par défaut pour les options
    static values = {
        delay: Number,
        direction: String,
        threshold: Number
    };

    // Méthode appelée lors de la connexion du contrôleur
    connect() {
        // Initialiser l'élément comme invisible
        this.element.style.opacity = '0';
        this.element.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
        
        // Appliquer la transformation initiale selon la direction
        this.applyInitialTransform();
        
        // Créer l'Intersection Observer
        this.createObserver();
    }

    // Méthode appelée lors de la déconnexion du contrôleur
    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }

    /**
     * Applique la transformation initiale selon la direction choisie
     */
    applyInitialTransform() {
        const direction = this.hasDirectionValue ? this.directionValue : 'up';
        const transforms = {
            'up': 'translateY(30px)',
            'down': 'translateY(-30px)',
            'left': 'translateX(30px)',
            'right': 'translateX(-30px)',
            'fade': 'none'
        };
        
        this.element.style.transform = transforms[direction] || transforms['up'];
    }

    /**
     * Crée et configure l'Intersection Observer
     */
    createObserver() {
        const threshold = this.hasThresholdValue ? this.thresholdValue : 0.1;
        
        const options = {
            threshold: threshold,
            rootMargin: '0px 0px -50px 0px' // Déclenche l'animation un peu avant que l'élément soit complètement visible
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Appliquer le délai si spécifié
                    const delay = this.hasDelayValue ? this.delayValue : 0;
                    
                    setTimeout(() => {
                        this.reveal(entry.target);
                    }, delay);
                    
                    // Arrêter d'observer cet élément une fois révélé
                    this.observer.unobserve(entry.target);
                }
            });
        }, options);

        // Commencer à observer l'élément
        this.observer.observe(this.element);
    }

    /**
     * Révèle l'élément avec une animation fluide
     * @param {HTMLElement} element - L'élément à révéler
     */
    reveal(element) {
        element.style.opacity = '1';
        element.style.transform = 'translate(0, 0)';
        
        // Ajouter une classe pour indiquer que l'élément est révélé
        element.classList.add('revealed');
    }
}


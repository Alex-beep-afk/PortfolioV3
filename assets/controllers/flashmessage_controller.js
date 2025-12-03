import { Controller } from '@hotwired/stimulus';

/*
 * Contrôleur Stimulus pour gérer les messages flash
 * 
 * Fonctionnalités :
 * - Fermeture manuelle via le bouton de fermeture
 * - Fermeture automatique après un délai configurable
 * - Animation de fermeture
 */
export default class extends Controller {
    static values = {
        duration: { type: Number, default: 5000 }, // Durée en millisecondes (5 secondes par défaut)
        autoClose: { type: Boolean, default: true } // Fermeture automatique activée par défaut
    }

    static targets = ['closeButton']

    connect() {
        console.log('FlashMessage controller connected');
        // Démarrer le timer de fermeture automatique si activé
        if (this.autoCloseValue) {
            this.timeoutId = setTimeout(() => {
                this.close();
            }, this.durationValue);
        }
    }

    disconnect() {
        // Nettoyer le timeout si le contrôleur est déconnecté avant la fermeture
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }
    }

    // Méthode pour fermer le message manuellement
    close(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Annuler le timeout si le message est fermé manuellement
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }

        // Ajouter une classe pour l'animation de fermeture
        this.element.classList.add('animate-slide-out');
        
        // Attendre la fin de l'animation avant de supprimer l'élément
        setTimeout(() => {
            this.element.remove();
        }, 300); // Durée de l'animation (doit correspondre au CSS)
    }

    // Méthode pour suspendre la fermeture automatique (quand on survole le message)
    pause() {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
            this.timeoutId = null;
        }
    }

    // Méthode pour reprendre la fermeture automatique (quand on quitte le message)
    resume() {
        if (this.autoCloseValue && !this.timeoutId) {
            this.timeoutId = setTimeout(() => {
                this.close();
            }, this.durationValue);
        }
    }
}

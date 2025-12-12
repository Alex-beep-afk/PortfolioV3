import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['menu', 'openIcon', 'closeIcon', 'toggle'];
    
    static values = {
        isOpen: { type: Boolean, default: false }
    }
    
    connect() {
        // Fermer le menu par défaut
        this.close();
    }
    
    toggle() {
        if (this.isOpenValue) {
            this.close();
        } else {
            this.open();
        }
    }
    
    open() {
        this.isOpenValue = true;
        if (this.hasMenuTarget) {
            this.menuTarget.classList.remove('hidden');
        }
        if (this.hasOpenIconTarget) {
            this.openIconTarget.classList.add('hidden');
        }
        if (this.hasCloseIconTarget) {
            this.closeIconTarget.classList.remove('hidden');
        }
        if (this.hasToggleTarget) {
            this.toggleTarget.setAttribute('aria-expanded', 'true');
        }
    }
    
    close() {
        this.isOpenValue = false;
        if (this.hasMenuTarget) {
            this.menuTarget.classList.add('hidden');
        }
        if (this.hasOpenIconTarget) {
            this.openIconTarget.classList.remove('hidden');
        }
        if (this.hasCloseIconTarget) {
            this.closeIconTarget.classList.add('hidden');
        }
        if (this.hasToggleTarget) {
            this.toggleTarget.setAttribute('aria-expanded', 'false');
        }
    }
}


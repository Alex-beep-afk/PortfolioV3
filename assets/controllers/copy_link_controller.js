import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['input', 'button', 'icon'];
    
    static values = {
        copiedText: { type: String, default: 'Copié !' }
    }
    connect() {
        console.log('CopyLinkController connected');
    }
    copy() {
        if (this.hasInputTarget) {
            // Sélectionner le texte dans l'input
            this.inputTarget.select();
            this.inputTarget.setSelectionRange(0, 99999); // Pour mobile
            
            try {
                // Copier dans le presse-papiers
                document.execCommand('copy');
                
                // Feedback visuel
                this.showSuccess();
            } catch (err) {
                // Fallback pour les navigateurs modernes
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(this.inputTarget.value).then(() => {
                        this.showSuccess();
                    }).catch(() => {
                        console.error('Erreur lors de la copie');
                    });
                }
            }
        }
    }
    
    showSuccess() {
        if (this.hasButtonTarget) {
            const originalHTML = this.buttonTarget.innerHTML;
            const originalClasses = this.buttonTarget.className;
            
            // Changer l'icône et le style
            this.buttonTarget.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
            this.buttonTarget.classList.remove('bg-green-600/50', 'hover:bg-green-600/70', 'border-green-500/50');
            this.buttonTarget.classList.add('bg-green-500', 'border-green-400');
            
            // Restaurer après 2 secondes
            setTimeout(() => {
                this.buttonTarget.innerHTML = originalHTML;
                this.buttonTarget.className = originalClasses;
            }, 2000);
        }
    }
}


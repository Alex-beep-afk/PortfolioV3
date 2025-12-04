import { Controller } from '@hotwired/stimulus';

/**
 * Contrôleur Stimulus pour la validation en temps réel du formulaire d'inscription
 * 
 * Ce contrôleur valide les champs du formulaire d'inscription en temps réel
 * et affiche des messages d'erreur appropriés selon les règles définies.
 * 
 * Attributs data requis :
 * - data-controller="register" : Active le contrôleur sur le formulaire
 * 
 * Targets requis dans le template :
 * - data-register-target="usernameInput" : Champ username
 * - data-register-target="usernameError" : Zone d'erreur pour username
 * - data-register-target="firstnameInput" : Champ firstName
 * - data-register-target="firstnameError" : Zone d'erreur pour firstName
 * - data-register-target="lastnameInput" : Champ lastName
 * - data-register-target="lastnameError" : Zone d'erreur pour lastName
 * - data-register-target="emailInput" : Champ email
 * - data-register-target="emailError" : Zone d'erreur pour email
 * - data-register-target="passwordInput" : Champ password
 * - data-register-target="passwordError" : Zone d'erreur pour password
 * - data-register-target="submitButton" : Bouton de soumission du formulaire
 */
export default class extends Controller {
    // Définition des targets pour chaque champ
    static targets = [
        'usernameInput', 'usernameError',
        'firstnameInput', 'firstnameError',
        'lastnameInput', 'lastnameError',
        'emailInput', 'emailError',
        'passwordInput', 'passwordError',
        'submitButton'
    ];

    /**
     * Méthode appelée lors de la connexion du contrôleur
     */
    connect() {
        // Initialiser les écouteurs d'événements pour chaque champ
        this.setupValidation();
        
        // Désactiver le bouton de soumission au départ
        this.updateSubmitButton();
        
        // Empêcher la soumission du formulaire si les champs ne sont pas valides
        this.element.addEventListener('submit', (event) => {
            if (!this.validateAll()) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    }

    /**
     * Configure les écouteurs d'événements pour tous les champs
     */
    setupValidation() {
        // Validation du username
        if (this.hasUsernameInputTarget) {
            this.usernameInputTarget.addEventListener('input', () => this.validateUsername());
            this.usernameInputTarget.addEventListener('blur', () => this.validateUsername());
        }

        // Validation du firstName
        if (this.hasFirstnameInputTarget) {
            this.firstnameInputTarget.addEventListener('input', () => this.validateFirstname());
            this.firstnameInputTarget.addEventListener('blur', () => this.validateFirstname());
        }

        // Validation du lastName
        if (this.hasLastnameInputTarget) {
            this.lastnameInputTarget.addEventListener('input', () => this.validateLastname());
            this.lastnameInputTarget.addEventListener('blur', () => this.validateLastname());
        }

        // Validation de l'email
        if (this.hasEmailInputTarget) {
            this.emailInputTarget.addEventListener('input', () => this.validateEmail());
            this.emailInputTarget.addEventListener('blur', () => this.validateEmail());
        }

        // Validation du password
        if (this.hasPasswordInputTarget) {
            this.passwordInputTarget.addEventListener('input', () => this.validatePassword());
            this.passwordInputTarget.addEventListener('blur', () => this.validatePassword());
        }
    }

    /**
     * Valide le champ username
     * Règle : non vide (required)
     */
    validateUsername() {
        const value = this.usernameInputTarget.value.trim();
        const isValid = value.length > 0;

        this.updateFieldValidation(
            this.usernameInputTarget,
            this.hasUsernameErrorTarget ? this.usernameErrorTarget : null,
            isValid,
            isValid ? '' : 'Le nom d\'utilisateur est obligatoire.'
        );

        // Mettre à jour l'état du bouton de soumission
        this.updateSubmitButton();

        return isValid;
    }

    /**
     * Valide le champ firstName
     * Règle : non vide (required)
     */
    validateFirstname() {
        const value = this.firstnameInputTarget.value.trim();
        const isValid = value.length > 0;

        this.updateFieldValidation(
            this.firstnameInputTarget,
            this.hasFirstnameErrorTarget ? this.firstnameErrorTarget : null,
            isValid,
            isValid ? '' : 'Le prénom est obligatoire.'
        );

        // Mettre à jour l'état du bouton de soumission
        this.updateSubmitButton();

        return isValid;
    }

    /**
     * Valide le champ lastName
     * Règle : non vide (required)
     */
    validateLastname() {
        const value = this.lastnameInputTarget.value.trim();
        const isValid = value.length > 0;

        this.updateFieldValidation(
            this.lastnameInputTarget,
            this.hasLastnameErrorTarget ? this.lastnameErrorTarget : null,
            isValid,
            isValid ? '' : 'Le nom est obligatoire.'
        );

        // Mettre à jour l'état du bouton de soumission
        this.updateSubmitButton();

        return isValid;
    }

    /**
     * Valide le champ email
     * Règle : format email valide
     */
    validateEmail() {
        const value = this.emailInputTarget.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = value.length === 0 || emailRegex.test(value);

        let errorMessage = '';
        if (value.length === 0) {
            errorMessage = 'L\'email est obligatoire.';
        } else if (!emailRegex.test(value)) {
            errorMessage = 'L\'email n\'est pas valide.';
        }

        this.updateFieldValidation(
            this.emailInputTarget,
            this.hasEmailErrorTarget ? this.emailErrorTarget : null,
            isValid && value.length > 0,
            errorMessage
        );

        // Mettre à jour l'état du bouton de soumission
        this.updateSubmitButton();

        return isValid && value.length > 0;
    }

    /**
     * Valide le champ password
     * Règles :
     * - Minimum 12 caractères
     * - Au moins une lettre majuscule
     * - Au moins une lettre minuscule
     * - Au moins un chiffre
     * - Au moins un caractère spécial
     */
    validatePassword() {
        const value = this.passwordInputTarget.value;
        
        // Vérifier si le champ est vide
        if (value.length === 0) {
            this.updateFieldValidation(
                this.passwordInputTarget,
                this.hasPasswordErrorTarget ? this.passwordErrorTarget : null,
                false,
                'Le mot de passe est obligatoire.'
            );
            
            // Mettre à jour l'état du bouton de soumission
            this.updateSubmitButton();
            
            return false;
        }

        // Vérifier la longueur minimale (12 caractères)
        const hasMinLength = value.length >= 12;
        
        // Vérifier la présence d'une majuscule
        const hasUpperCase = /[A-Z]/.test(value);
        
        // Vérifier la présence d'une minuscule
        const hasLowerCase = /[a-z]/.test(value);
        
        // Vérifier la présence d'un chiffre
        const hasNumber = /[0-9]/.test(value);
        
        // Vérifier la présence d'un caractère spécial
        const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value);

        // Construire le message d'erreur détaillé
        const errors = [];
        if (!hasMinLength) errors.push('12 caractères minimum');
        if (!hasUpperCase) errors.push('une lettre majuscule');
        if (!hasLowerCase) errors.push('une lettre minuscule');
        if (!hasNumber) errors.push('un chiffre');
        if (!hasSpecialChar) errors.push('un caractère spécial');

        const isValid = hasMinLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar;
        const errorMessage = isValid 
            ? '' 
            : `Le mot de passe doit contenir : ${errors.join(', ')}.`;

        this.updateFieldValidation(
            this.passwordInputTarget,
            this.hasPasswordErrorTarget ? this.passwordErrorTarget : null,
            isValid,
            errorMessage
        );

        // Mettre à jour l'état du bouton de soumission
        this.updateSubmitButton();

        return isValid;
    }

    /**
     * Met à jour l'état visuel et le message d'erreur d'un champ
     * @param {HTMLElement} input - L'élément input à valider
     * @param {HTMLElement|null} errorElement - L'élément pour afficher l'erreur
     * @param {boolean} isValid - Si le champ est valide
     * @param {string} errorMessage - Le message d'erreur à afficher
     */
    updateFieldValidation(input, errorElement, isValid, errorMessage) {
        // Mettre à jour les classes CSS de l'input
        if (isValid) {
            // Champ valide : bordure verte
            input.classList.remove('border-red-500/50', 'border-red-400');
            input.classList.add('border-green-500/50');
            input.style.boxShadow = '0 0 10px rgba(16, 185, 129, 0.3)';
        } else {
            // Champ invalide : bordure rouge
            input.classList.remove('border-green-500/50');
            input.classList.add('border-red-500/50', 'border-red-400');
            input.style.boxShadow = '0 0 10px rgba(239, 68, 68, 0.3)';
        }

        // Mettre à jour le message d'erreur
        if (errorElement) {
            if (errorMessage && !isValid) {
                errorElement.innerHTML = `
                    <div class="mt-2 bg-red-500/20 border border-red-500/50 rounded-lg p-3" style="box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-red-400">${errorMessage}</div>
                        </div>
                    </div>
                `;
                errorElement.style.display = 'block';
            } else {
                errorElement.innerHTML = '';
                errorElement.style.display = 'none';
            }
        }
    }

    /**
     * Valide tous les champs du formulaire sans déclencher les effets de bord
     * Utile pour vérifier l'état global sans mettre à jour les messages d'erreur
     */
    validateAll() {
        const validations = [];

        // Validation username
        if (this.hasUsernameInputTarget) {
            const value = this.usernameInputTarget.value.trim();
            validations.push(value.length > 0);
        }

        // Validation firstName
        if (this.hasFirstnameInputTarget) {
            const value = this.firstnameInputTarget.value.trim();
            validations.push(value.length > 0);
        }

        // Validation lastName
        if (this.hasLastnameInputTarget) {
            const value = this.lastnameInputTarget.value.trim();
            validations.push(value.length > 0);
        }

        // Validation email
        if (this.hasEmailInputTarget) {
            const value = this.emailInputTarget.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            validations.push(value.length > 0 && emailRegex.test(value));
        }

        // Validation password
        if (this.hasPasswordInputTarget) {
            const value = this.passwordInputTarget.value;
            if (value.length === 0) {
                validations.push(false);
            } else {
                const hasMinLength = value.length >= 12;
                const hasUpperCase = /[A-Z]/.test(value);
                const hasLowerCase = /[a-z]/.test(value);
                const hasNumber = /[0-9]/.test(value);
                const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value);
                validations.push(hasMinLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar);
            }
        }

        return validations.every(v => v === true);
    }

    /**
     * Met à jour l'état du bouton de soumission selon la validation de tous les champs
     */
    updateSubmitButton() {
        if (!this.hasSubmitButtonTarget) {
            return;
        }

        const allFieldsValid = this.validateAll();

        if (allFieldsValid) {
            // Activer le bouton
            this.submitButtonTarget.disabled = false;
            this.submitButtonTarget.classList.remove('opacity-50', 'cursor-not-allowed');
            this.submitButtonTarget.classList.add('hover:from-purple-500', 'hover:via-cyan-500', 'hover:to-purple-500', 'hover:border-purple-300');
        } else {
            // Désactiver le bouton
            this.submitButtonTarget.disabled = true;
            this.submitButtonTarget.classList.add('opacity-50', 'cursor-not-allowed');
            this.submitButtonTarget.classList.remove('hover:from-purple-500', 'hover:via-cyan-500', 'hover:to-purple-500', 'hover:border-purple-300');
        }
    }
}


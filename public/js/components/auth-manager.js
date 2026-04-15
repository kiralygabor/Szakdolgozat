/**
 * Auth Manager Component
 * Handles unified authentication page logic like password toggles and OTP inputs.
 */
export class AuthManager {
    constructor() {
        this.init();
    }

    init() {
        this.initPasswordToggles();
        this.initOtpInputs();
        
        // Refresh feather icons
        if (window.feather && typeof window.feather.replace === 'function') {
            window.feather.replace();
        }
    }

    initPasswordToggles() {
        document.querySelectorAll('.password-toggle').forEach(icon => {
            icon.addEventListener('click', function(e) {
                const targetSelector = this.getAttribute('data-target') 
                    ? '#' + this.getAttribute('data-target') 
                    : '#password';
                
                const input = document.querySelector(targetSelector);
                if (!input) return;

                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    }

    initOtpInputs() {
        const inputs = document.querySelectorAll(".otp-input");
        const hiddenCode = document.getElementById("verificationCode");
       
        if (inputs.length === 0 || !hiddenCode) return;

        const updateHiddenCode = () => {
            hiddenCode.value = Array.from(inputs).map(i => i.value).join("");
        };

        inputs.forEach((input, index) => {
            input.addEventListener("input", function (e) {
                this.value = this.value.replace(/[^0-9]/g, ""); // Keep only numbers
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHiddenCode();
            });

            input.addEventListener("keydown", function (e) {
                if (e.key === "Backspace" && !this.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
         
            input.addEventListener("paste", function (e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData("text").replace(/[^0-9]/g, "").slice(0, 6);
                if (pastedData) {
                    for (let i = 0; i < pastedData.length; i++) {
                        if (i < inputs.length) {
                            inputs[i].value = pastedData[i];
                        }
                    }
                    const focusIndex = Math.min(pastedData.length, inputs.length - 1);
                    inputs[focusIndex].focus();
                    updateHiddenCode();
                }
            });
        });
    }
}

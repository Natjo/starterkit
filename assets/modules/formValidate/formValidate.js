/* eslint-disable */

/**
 * formValidate
 */

const FormValidate = function(form, onSend){
    const fields = form.querySelectorAll(':required');
    const mandatory = form.getAttribute('data-mandatory');
    let validity = true;
    let init = true;

    this.reset = () => {
        init = true;
        for (let field of fields) {
            field.classList.remove('error');
            field.parentNode.querySelector('.field-error-msg').innerHTML = '';
        }
    }
 
    const validate = () => {
        if(init) return;
        validity = true;
        for (let field of fields) {
            const error_msg = field.parentNode.querySelector('.field-error-msg');
            const dataTypeMismatch = field.dataset.typemismatch;
            const dataPatternMismatch = field.dataset.patternmismatch;
            const typeMismatch = field.validity.typeMismatch;
			const tooShort = field.validity.tooShort;
			const tooLong = field.validity.tooLong; 
			const stepMismatch = field.validity.stepMismatch;
            const patternMismatch = field.validity.patternMismatch;
            const valueMissing = field.validity.valueMissing;
            if (!field.checkValidity()) {
					field.classList.add('error');
					var msg = "";
					if((typeMismatch || stepMismatch || tooShort || tooLong) && dataTypeMismatch){
						msg = dataTypeMismatch;
					}
					if(patternMismatch && dataPatternMismatch){
						msg = dataPatternMismatch;
					}
					if(valueMissing && mandatory){
						msg = mandatory;
					} 
					field.setCustomValidity(msg);
					error_msg.innerHTML = field.validationMessage;
					validity = false;
            } else {
                field.classList.remove('error');
                error_msg.innerHTML = '';
            }
        }
        return validity;
    };

	for (let field of fields) {
		if(!field.parentNode.querySelector('.field-error-msg')){
			const msg = document.createElement('div');
			msg.className = 'field-error-msg';
			msg.id = field.getAttribute('aria-describedby');
			field.parentNode.appendChild(msg);
		}
		field.addEventListener('input', () => validate());
		field.addEventListener('change', () => validate());
	}

	form.onsubmit = e => {
		e.preventDefault();
		init = false;
		validate() && onSend();
	};
};

export default FormValidate;

/* eslint-disable */

// Enable submit if consent checkbox
// checkvalidity only if clicked on submit (enabled)

const formValidate = (form, onSend) => {
	const fields = form.querySelectorAll(':required');
	let init = true;
	let validity = true;

	for(let field of fields){
		const msg = document.createElement('div');
		msg.className = 'field-error-msg';
		msg.id = field.getAttribute('aria-describedby');
		field.parentNode.appendChild(msg);
		field.addEventListener('change', () => validate());
	}
	
	const validate = () => {
		if(init) return;
		validity = true
		for(let field of fields){
			const msg = field.parentNode.querySelector('.field-error-msg');
			if(!field.checkValidity()){
				field.classList.add('error');
				msg.innerHTML = field.validationMessage;
				validity = false;
			}else{
				field.classList.remove('error');
				msg.innerHTML = "";
			}
		}
		return validity;
	}
		
	form.onsubmit = e => {
		e.preventDefault();
		init = false;
		validate() ? onSend() : null;
	}
}

export default formValidate;

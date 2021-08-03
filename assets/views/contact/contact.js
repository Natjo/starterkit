import formValidate from '../../modules/formValidate/formValidate.js';

const el = document.querySelector('[data-view=contact]');
const form = el.querySelector('#form-contact');
const url = paramsData.wp_ajax_url;

new formValidate(form, e => {
    const formData = new FormData(form);
    formData.append('nonce', form.dataset.nonce);
    formData.append('action', form.getAttribute('action'));

    const xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.onload = () => {
        const response = JSON.parse(xhr.response);
        if(response.status === 200){
            form.reset();
            e.reset();
        }
        if(response.status === 300){
            console.log(response.msg);
        }
        if(response.status === 500){
            console.log(response.msg);
        }
    }
    xhr.error = () => {
       console.log('error');
    }

    xhr.send(formData);
});
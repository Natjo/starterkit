import formValidate from "../../modules/formValidate/formValidate.js";

export default (el) => {
    const form = el.querySelector('form');

    if (form) {
        new formValidate(form, () => {
            form.submit();
        });
    }

}
import formValidate from "../../modules/formValidate/formValidate.js";

export default (el) => {
    const form = el.querySelector('form');

    new formValidate(form, () => {
        form.submit();
    });
}
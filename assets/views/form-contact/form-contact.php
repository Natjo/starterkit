<form action="/contact" method="post" enctype='multipart/form-data' novalidate data-mandatory="Vous devez remplir ce champs" data-view="form-contact">
    <?php wp_nonce_field('contact', 'contact_nonce'); ?>
    <div class="container">
        <fieldset>
            <h2>Informations générales</h2>

            <div class="field">
                <label for="contact-infos">Vous êtes*</label>
                <select id="contact-infos" name="contact-infos" required aria-describedby="error-infos" data-mandatory="Vous devez sélectionner un item">
                    <option value="" hidden>Sélectionnez...</option>
                    <option value="1">Une collectivité territoriale</option>
                    <option value="2">Un centre de tri</option>
                    <option value="3">Une entreprise</option>
                    <option value="3">Autre</option>
                </select>
            </div>

            <div class="field">
                <label for="contact-msg">Votre message*</label>
                <textarea name="contact-msg" id="contact-msg" required aria-describedby="error-msg"></textarea>
            </div>

            <div class="field">
                <input type="checkbox" name="contact-consent" id="contact-consent" required aria-describedby="error-optin">
                <label for="contact-consent" class="label-checkbox rte">J'accepte</label>
            </div>

            <div class="field">
                <input type="checkbox" name="contact-newsletter" id="contact-newsletter">
                <label for="contact-newsletter" class="label-checkbox rte">
                    J’accepte de recevoir par e-mail des informations en provenance de Valorplast, via sa newsletter
                </label>
            </div>

            <button type="submit">Envoyer</button>
        </fieldset>
    </div>
</form>
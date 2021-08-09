<section class="contact" data-view="contact">
	<div class="container">
		<form id="form-contact" action="contact" method="post" data-nonce="<?= wp_create_nonce("contactNonce"); ?>" novalidate="novalidate" role="form" aria-label="Contact information" data-mandatory="Mandatory field">
			<fieldset>
				<legend>Personal information</legend>
				<div class="field">
					<label for="form-contat-name">Name</label>
					<input id="form-contat-name" type="text" name="name" aria-label="Name">
				</div>
				<div class="field">
					<label for="form-contat-email">Email</label>
					<input id="form-contat-email" type="email" name="email" aria-label="Email">
				</div>
				<div class="field">
					<label for="form-contat-msg">Message</label>
					<textarea id="form-contat-msg" name="message" aria-label="Msg">
                </textarea>
				</div>
				<div class="action">
					<input type="submit">
				</div>
			</fieldset>
		</form>
	</div>
</section>
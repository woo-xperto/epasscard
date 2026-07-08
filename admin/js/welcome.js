;(function ($) {
	$(document).ready(function () {
		$(document).on('click', '#epc_welcome_no_thanks, #epc_welcome_subscribe', function (e) {
			e.preventDefault();

			const button = $(this);
			const originalText = button.html();
			const adminEmail = $('#epc_admin_email').val();
			const actionType = button.attr('name') === 'epc_welcome_subscribe' ? 'subscribe' : 'no_thanks';

			if (actionType === 'subscribe' && (!adminEmail || !isValidEmail(adminEmail))) {
				window.alert(epcWelcomePage.i18n.invalidEmail);
				return;
			}

			button.html('<span class="spinner is-active" style="float:none;margin:0;"></span> ' + epcWelcomePage.i18n.processing);
			button.prop('disabled', true);

			$.ajax({
				url: epcWelcomePage.ajaxUrl,
				type: 'POST',
				data: {
					action: 'epc_welcome_api_call',
					nonce: epcWelcomePage.nonce,
					admin_email: adminEmail,
					type: actionType,
				},
				success: function (response) {
					if (response.success && response.data && response.data.url) {
						window.location.href = response.data.url;
						return;
					}

					window.alert(epcWelcomePage.i18n.error);
					button.html(originalText);
					button.prop('disabled', false);
				},
				error: function () {
					window.alert(epcWelcomePage.i18n.error);
					button.html(originalText);
					button.prop('disabled', false);
				},
			});
		});

		function isValidEmail(email) {
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailRegex.test(email);
		}
	});
})(jQuery);

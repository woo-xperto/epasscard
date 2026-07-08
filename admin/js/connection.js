(function ($) {
	'use strict';

	if (typeof epcConnection === 'undefined') {
		return;
	}

	function post(action, data) {
		return $.ajax({
			url: epcConnection.ajaxUrl,
			method: 'POST',
			dataType: 'json',
			data: $.extend({ action: action, nonce: epcConnection.nonce }, data || {}),
		});
	}

	function setStatus(message, type) {
		var $el = $('#epc-connection-status');
		$el.removeClass('is-success is-error').addClass(type === 'error' ? 'is-error' : 'is-success');
		$el.text(message || '');
	}

	$(function () {
		$(document).on('click', '#epc-connect-key', function () {
			var $btn = $(this);
			var apiKey = $('#epc-api-key').val();
			$btn.prop('disabled', true);
			setStatus(epcConnection.i18n.connecting, 'success');

			post('epc_connect_api_key', { api_key: apiKey })
				.done(function (resp) {
					if (resp.success) {
						setStatus(resp.data.message || epcConnection.i18n.connected, 'success');
						window.location.reload();
						return;
					}
					setStatus((resp.data && resp.data.message) || epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				})
				.fail(function () {
					setStatus(epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				});
		});

		$(document).on('click', '#epc-connect-credentials', function () {
			var $btn = $(this);
			$btn.prop('disabled', true);
			setStatus(epcConnection.i18n.connecting, 'success');

			post('epc_connect_credentials', {
				email: $('#epc-email').val(),
				password: $('#epc-password').val(),
			})
				.done(function (resp) {
					if (resp.success) {
						setStatus(resp.data.message || epcConnection.i18n.connected, 'success');
						window.location.reload();
						return;
					}
					setStatus((resp.data && resp.data.message) || epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				})
				.fail(function () {
					setStatus(epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				});
		});

		$(document).on('click', '#epc-disconnect', function () {
			var $btn = $(this);
			$btn.prop('disabled', true);

			post('epc_disconnect')
				.done(function (resp) {
					if (resp.success) {
						window.location.reload();
						return;
					}
					setStatus((resp.data && resp.data.message) || epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				})
				.fail(function () {
					setStatus(epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				});
		});

		$(document).on('click', '#epc-save-modules', function (event) {
			event.preventDefault();

			var $btn = $(this);
			var modules = [];

			$('input[name="epc_enabled_modules[]"]:checked').each(function () {
				modules.push($(this).val());
			});

			$btn.prop('disabled', true);
			setStatus(epcConnection.i18n.savingModules || 'Saving…', 'success');

			post('epc_save_modules', {
				modules: modules,
				modules_json: JSON.stringify(modules),
			})
				.done(function (resp) {
					if (resp.success) {
						setStatus(resp.data.message || epcConnection.i18n.modulesSaved || 'Saved.', 'success');
						window.location.reload();
						return;
					}
					setStatus((resp.data && resp.data.message) || epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				})
				.fail(function () {
					setStatus(epcConnection.i18n.error, 'error');
					$btn.prop('disabled', false);
				});
		});

		$(document).on('click', '.epc-copy-snippet', function () {
			var targetId = $(this).data('copy-target');
			var $pre = $('#' + targetId);
			var text = $pre.text();
			var $btn = $(this);

			function markCopied() {
				var original = $btn.text();
				$btn.addClass('is-copied').text(epcConnection.i18n.copied || 'Copied');
				setTimeout(function () {
					$btn.removeClass('is-copied').text(original);
				}, 2000);
			}

			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text).then(markCopied).catch(function () {
					setStatus(epcConnection.i18n.copyFailed || 'Copy failed', 'error');
				});
				return;
			}

			var $temp = $('<textarea></textarea>');
			$temp.val(text).appendTo('body').select();
			try {
				document.execCommand('copy');
				markCopied();
			} catch (e) {
				setStatus(epcConnection.i18n.copyFailed || 'Copy failed', 'error');
			}
			$temp.remove();
		});
	});
})(jQuery);

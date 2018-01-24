(function($) {

	var live_chat = {
		last_activity: null,
		chat_status: null,
		active: false,
		start: function() {

			$.ajax({
				type: 'POST',
				url: absolute_url + 'update',
				dataType: 'json',
				timeout: 130 * 1000,
				cache: false,
				data: {
					last_activity: live_chat.last_activity,
					chat_status : live_chat.chat_status
				},
				success: function(data) {

					$('#chat-status span:first').html(data.chat_status['message']);

					if (!live_chat.active && data.chat_messages['chat_expired']) {
						
						live_chat.stop();
						
					}

					if ($('#chat-content').is(':visible')) {

						if (data.chat_messages['success']) {
							
							live_chat.active = true;
							
							data.chat_messages['queue'] ? $('#chat-queue').hide(0) : $('#chat-queue').show(0);
							
							$('#chat-arrow').show(0, function() {
								
								$('#chat-loader-1').addClass('chat-hidden');
								
								$('#chat-loader-2').hide(0, function() {
									
									$('#chat-messages').html(data.chat_messages['messages']);
									
								});
								
							});

							$('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);

							if (data.chat_messages['new_message']) {
								
								$('#audio-1').trigger('play');
								$('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);
								
							}

							data.chat_messages['operator_typing'] ? $('#chat-operator-typing').show() : $('#chat-operator-typing').hide();
						
						} else {

							if (live_chat.active) {
								
								live_chat.stop();
								
							}
							
						}

					} else {
						
						$('#chat-arrow').show(0, function() {
							
							$('#chat-loader-1').addClass('chat-hidden');
							
						});

					}

					if (!live_chat.active && live_chat.chat_status !== data.chat_status['success']) {

						if ($('#chat-form-container').is(':visible')) {

							$('#chat-status').trigger('click');

						}

					}

					if (data.invitation_message['success'] && !$('#chat-form-container').is(':visible')) {
						
						$('#chat-alert-1').show(function() {
							
							$("#chat-alert-1 a").html(data.invitation_message['message']);
							
						});

					}

					live_chat.last_activity = data.chat_messages['last_activity'];
					live_chat.chat_status = data.chat_status['success'];
					
					setTimeout(live_chat.start, 1000);
					
				},
				error: function() {

					setTimeout(live_chat.start, 1000);

				}
			});
			
		},
		stop: function() {
			
			$.ajax({
				type: 'POST',
				url: absolute_url + 'stop_chat',
				dataType: 'json',
				success: function(data) {
					
					if (data.success) {
						
						live_chat.active = false;
						live_chat.chat_status = null;

					} else {
						
						$('#chat-loader-1').addClass('chat-hidden');
						
					}
					
				}
			});
			
		}
	}
	
	live_chat.start();
	
	$(document).ready(function() {
		
		var typing_timer;
		
		$('body').on('click', '#chat-status', function() {

			$.ajax({
				type: 'POST',
				url: absolute_url + 'contact_operators',
				dataType: 'json',
				cache: false,
				success: function(data) {
					
					if (data.success) {
						
						$('#chat-arrow').hide(0, function() {
							
							$('#chat-loader-1').removeClass('chat-hidden');
							
							$('#chat-loader-2').show(0, function() {

								$('#chat-form-container #chat-form').html(data.content);

							});
							
						});

					} else {

						$('#chat-form-container #chat-form').html(data.content);

					}
					
				}
			});
			
		}).on('click', '#start-chat', function(event) {
			
			event.preventDefault();
			
			$('#chat-form').validate({
				rules: {
					username: 'required',
					email: {
						required: true,
						email: true
					},
					message: 'required'
				},
				highlight: function(element) {
					$(element).closest('.chat-form-group').addClass('chat-has-error');
				},
				unhighlight: function(element) {
					$(element).removeAttr('placeholder').closest('.chat-form-group').removeClass('chat-has-error');
				},
				errorPlacement: function(error, element) {
					element.attr('placeholder', error.text());
				}
			});
			
			if ($('#chat-form').valid()) {

				$.ajax({
					type: 'POST',
					url: absolute_url + 'start_chat',
					dataType: 'json',
					data: $('#chat-form').serialize(),
					success: function(data) {
						
						if (is_mobile()) {
							
							$('#chat-wrapper').height('auto');
							
						}
						
						if (data.success) {
							
							$('#chat-arrow').hide(0, function() {
								
								$('#chat-loader-1').removeClass('chat-hidden');
								
								$('#chat-loader-2').show(0, function() {

									$('#chat-form-container #chat-form').html(data.content);
									
								});
								
							});
							
						} else {
							
							$('#chat-form-container #chat-form').html(data.content);
							
						}
						
					}
				});
				
			}

		}).on('click', '#stop-chat', function(event) {
			
			event.preventDefault();
			
			live_chat.stop();
			
			$('#chat-arrow').hide(0, function() {
				
				$('#chat-loader-1').removeClass('chat-hidden');
				
			});
			
		}).on('keyup', '#message', function(event) {

			if (live_chat.active) {
				
				var status;
				
				clearTimeout(typing_timer);
				
				status = ($(this).val() == '') ? 0 : 1;
				
				typing_timer = setTimeout(function() {

					$.ajax({
						type: 'POST',
						url: absolute_url + 'typing',
						dataType: 'json',
						data: { 
							typing: status 
						}
					});
					
				}, 150);
				
			}
			
		}).on('keydown', '#message', function(event) {
			
			if (live_chat.active) {
				
				clearTimeout(typing_timer);
				
			}
			
			if (event.keyCode == 13) {
				
				event.preventDefault();

				$('#send-message').trigger('click');
				
			}

		}).on('click', '#send-message', function(event) {
			
			event.preventDefault();
			
			$('#chat-form').validate({
				onkeyup: false,
				onfocusout: false,
				rules: {
					message: 'required'
				},
				highlight: function(element) {
					$(element).closest('.chat-form-group').addClass('chat-has-error');
				},
				unhighlight: function(element) {
					$(element).removeAttr('placeholder').closest('.chat-form-group').removeClass('chat-has-error');
				},
				errorPlacement: function(error, element) {
					element.attr('placeholder', error.text());
				}
			});

			if ($('#chat-form').valid()) {

				$.ajax({
					type: 'POST',
					url: absolute_url + 'send_message',
					dataType: 'json',
					data: $(this).parents('form').serialize(),
					success: function(data) {
						
						clearTimeout(typing_timer);
						
						$('#chat-arrow').hide(0, function() {
							
							$('#chat-loader-1').removeClass('chat-hidden');
							
						});
						
						if (data.success) {
							
							$('#chat-form')[0].reset();
							
							$.getJSON(absolute_url + 'get_token', function(data) {

								$('#token').val(data.token);
								
							});
							
						}
						
					}
				});
				
			}

		}).on('click', '#chat-alert-1 button.chat-button-close', function(event) {
			
			event.preventDefault();

			$("#chat-alert-1").hide(0);
			$("#chat-alert-1 a").html('&nbsp;');

		}).on('click', '#chat-alert-1 a', function(event) {
			
			event.preventDefault();
			
			$('#chat-alert-1 button.chat-button-close').trigger('click');
			$('#chat-status').trigger('click');
			
		}).on('click', '#send-email', function(event) {

			event.preventDefault();

			$('#chat-form').validate({
				rules: {
					username: 'required',
					email: {
						required: true,
						email: true
					},
					message: 'required'
				},
				highlight: function(element) {
					$(element).closest('.chat-form-group').addClass('chat-has-error');
				},
				unhighlight: function(element) {
					$(element).removeAttr('placeholder').closest('.chat-form-group').removeClass('chat-has-error');
				},
				errorPlacement: function(error, element) {
					element.attr('placeholder', error.text());
				}
			});
			
			if ($('#chat-form').valid()) {
				
				$('#chat-loader-2').show(0);
				
				$.ajax({
					type: 'POST',
					url: absolute_url + 'send_email',
					dataType: 'json',
					data: $('#chat-form').serialize(),
					success: function(data) {

						$('#chat-form')[0].reset();
						
						if (data.success) {

							$('#chat-loader-2').hide(0, function() {
								
								$('#chat-alert-1').show(function() {
									
									$("#chat-alert-1 a").html(data.message);
									
								});

							});
							
						} else {
							
							$('#chat-loader-2').hide(0, function() {

								$('#chat-alert-1').show(function() {
									
									$("#chat-alert-1 a").html(data.message);
									
								});
								
							});
							
						}

						setTimeout(function() {
							
							$('#chat-status').trigger('click');
							
						}, 5000);
						
					}
				});
				
			}
			
		}).on('click', 'a#chat-sound', function(event) {
			
			event.preventDefault();
			
			if ($('a#chat-sound span').hasClass('fa-volume-up')) {
				
				$('a#chat-sound span').removeClass('fa-volume-up').addClass('fa-volume-off');
				$('#audio-1').prop('muted', true);

			} else {
				
				$('a#chat-sound span').removeClass('fa-volume-off').addClass('fa-volume-up');
				$('#audio-1').prop('muted', false);

			}

		}).on('click', '#chat-status, #stop-chat', function(event) {
			
			event.preventDefault();
			
			if ($("#chat-alert-1").is(':visible')) {
				
				$("#chat-alert-1").hide(0);

			}
			
			if ($('#chat-wrapper').hasClass("active")) {
				
				$('span#chat-arrow').addClass('fa-chevron-up').removeClass('fa-chevron-down');
				
				$('#chat-wrapper').animate({ 'height': '34px' }, 500, function() {
					
					if (is_mobile()) {
						
						$('#chat-wrapper').css('overflow-y', 'hidden');
						
					}

					$('#chat-form-container').hide(400, function() {
						
						$('#chat-form').empty();
						$('#chat-wrapper').height('auto');
						
					});

				}).removeClass('active');
				
			} else {

				$('#chat-wrapper').css('height', '34px');
				
				$('span#chat-arrow').addClass('fa-chevron-down').removeClass('fa-chevron-up');

				$('#chat-form-container').show(500, function() {
					
					$('#chat-wrapper').animate({ 'height': $('#chat-form-container').height() + 34 }, 500, function() {

						if (is_mobile()) {
							
							$('#chat-wrapper').css('overflow-y', 'auto');
							
						} else {

							$('#chat-wrapper').height('auto');
						
						}
						
					}).addClass('active');
					
				});
				
			}
			
		});

	});

})(jQuery);

function is_mobile() {
	
	return (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/iPhone|iPad|iPod/i) || navigator.userAgent.match(/Opera Mini/i) || navigator.userAgent.match(/IEMobile/i));
	
}

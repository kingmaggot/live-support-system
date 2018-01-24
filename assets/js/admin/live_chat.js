$(document).ready(function() {
	
	var typing_timer;
	var audio_1 = $('<audio id="audio-1"></audio>');
	var audio_2 = $('<audio id="audio-2"></audio>');
	
	audio_1.append('<source src="../assets/sounds/alert_1.ogg" type="audio/ogg">');
	audio_1.append('<source src="../assets/sounds/alert_1.mp3" type="audio/mpeg">');
	audio_1.append('<source src="../assets/sounds/alert_1.wav" type="audio/wav">');
	audio_1.appendTo('body');
	
	audio_2.append('<source src="../assets/sounds/alert_2.ogg" type="audio/ogg">');
	audio_2.append('<source src="../assets/sounds/alert_2.mp3" type="audio/mpeg">');
	audio_2.append('<source src="../assets/sounds/alert_2.wav" type="audio/wav">');
	audio_2.appendTo('body');
	
	$('body').on('click', '#operator-status', function(event) {
		
		event.preventDefault();
		
		$('#loader-1').removeClass('hidden');
		$('#form-1 :button').prop('disabled', true);
		
		$.ajax({
			type: 'POST',
			url: 'index/update_status'
		});
		
	}).on('click', '.start-chat', function(event) {
		
		event.preventDefault();

		$('#loader-1').removeClass('hidden');
		$(this).prop('disabled', true).next('.watch-chat').prop('disabled', true);
		
		$.ajax({
			type: 'POST',
			url: 'index/start_chat',
			dataType: 'json',
			data: { 
				chat_id: $(this).data('id') 
			},
			success: function(data) {
				
				if (data.success) {

					live_chat.active = true;
					
					$('#chat-messages').empty();
					$('#form-1 :button, #form-2 :input, #form-2 :button').prop('disabled', true);
					$('#visitor-details').closest('.panel').addClass('hidden');
					
				}

			}
		});

	}).on('click', '#stop-chat', function(event) {
		
		event.preventDefault();

		live_chat.stop();

		$('#loader-1').removeClass('hidden');
		$('#form-1 :button, #form-2 :input, #form-2 :button').prop('disabled', true);

		$('#chat-messages').empty();
		$('#visitor-details').closest('.panel').addClass('hidden');
		
	}).on('click', '.watch-chat', function(event) {
		
		event.preventDefault();

		$('#loader-1').removeClass('hidden');
		$(this).prop('disabled', true).prev('.start-chat').prop('disabled', true);
		
		$.ajax({
			type: 'POST',
			url: 'index/watch_chat',
			dataType: 'json',
			data: { 
				chat_id: $(this).data('id') 
			},
			success: function(data) {
				
				if (data.success) {
					
					$('#chat-messages').empty();
					$('#form-1 :button, #form-2 :input, #form-2 :button').prop('disabled', true);
					$('#visitor-details').closest('.panel').addClass('hidden');
			
				} else {
					
					$('#loader-1').addClass('hidden');
					
				}

			}
		});
		
	}).on('click', '.transfer-chat', function(event) {
		
		event.preventDefault();
		
		$('#loader-1').removeClass('hidden');
		
		$.ajax({
			type: 'POST',
			url: 'index/transfer_chat',
			dataType: 'json',
			data: { 
				department_id: $(this).data('id') 
			},
			success: function(data) {
				
				if (data.success) {
					
					live_chat.stop();
					
					$('#chat-messages').empty();
					$('#form-1 :button, #form-2 :input, #form-2 :button').prop('disabled', true);
					$('#visitor-details').closest('.panel').addClass('hidden');
					
				} else {
					
					$('#loader-1').addClass('hidden');
					
				}

			}
		});
		
	}).on('click', '#invite-visitor', function(event) {
		
		event.preventDefault();
		
		$('#online-visitors').scrollTop(0);
		$('#invitation-message').focus();
		$('#ip-address').val($(this).data('ip-address'));
		
	}).on('click', '#canned-messages-2', function(event) {
		
		event.preventDefault();

		$('#invitation-message').val($(event.target).text());
		
	}).on('keydown', '#invitation-message', function(event) {
		
		if (event.keyCode == 13) {
			
			event.preventDefault();

		}
		
	}).on('click', '#send-invitation', function(event) {
		
		event.preventDefault();

		$('#form-3').validate({
			onkeyup: false,
			onfocusout: false,
			rules: {
				invitation_message: 'required'
			},
			highlight: function(element) {
				$(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function(element) {
				$(element).removeAttr('placeholder').closest('.form-group').removeClass('has-error');
			},
			errorPlacement: function(error, element) {
				element.attr('placeholder', error.text());
			}
		});
		
		if ($('#form-3').valid()) {
			
			$.ajax({
				type: 'POST',
				url: 'index/send_invitation',
				dataType: 'json',
				data: $(this).parents('form').serialize(),
				success: function(data) {

					if (data.success) {
						
						$('#message-success-1').removeClass('hidden').fadeIn().delay(5000).fadeOut();
						
						$('#form-3')[0].reset();
						
					} else {
						
						$('#message-success-1').fadeOut();
						
					}
					
				}
			});
		
		}
	
	}).on('click', '#online-departments', function(event) {
		
		$.ajax({
			type: 'POST',
			url: 'index/get_online_departments',
			dataType: 'json',
			success: function(data) {
				
				$('#online-departments').next('ul').html(data);
				
			}
		});

	}).on('click', '#canned-messages-1', function(event) {
		
		event.preventDefault();
		
		$('#message').val($(event.target).text());
		
	}).on('click', '#send-message', function(event) {
		
		event.preventDefault();
		
		$('#form-2').validate({
			onkeyup: false,
			onfocusout: false,
			rules: {
				message: 'required'
			},
			highlight: function(element) {
				$(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function(element) {
				$(element).removeAttr('placeholder').closest('.form-group').removeClass('has-error');
			},
			errorPlacement: function(error, element) {
				element.attr('placeholder', error.text());
			}
		});
		
		if ($('#form-2').valid()) {
			
			$('#loader-1').removeClass('hidden');
			
			$.ajax({
				type: 'POST',
				url: 'index/send_message',
				dataType: 'json',
				data: $(this).parents('form').serialize(),
				success: function(data) {
					
					clearTimeout(typing_timer);
					
					if (data.success) {

						$('#form-2')[0].reset();
						
					} else {
						
						$('#form-2')[0].reset();
						$('#loader-1').addClass('hidden');
						
					}
					
				}
			});
		
		}

	}).on('keyup', '#message', function() {

		var status;
		
		clearTimeout(typing_timer);
		
		status = ($(this).val() == '') ? 0 : 1;

		typing_timer = setTimeout(function() {

			$.ajax({
				type: 'POST',
				url: 'index/typing',
				dataType: 'json',
				data: { 
					typing: status 
				}		
			});
			
		}, 150);

	}).on('keydown', '#message', function(event) {
		
		clearTimeout(typing_timer);
		
		if (event.keyCode == 13) {
			
			event.preventDefault();
			
			$('#send-message').trigger('click');

		}
		
	}).on('click', 'a.sound', function(event) {
		
		event.preventDefault();
		
		if ($('a.sound span').hasClass('fa-volume-up')) {
			
			$('a.sound span').removeClass('fa-volume-up').addClass('fa-volume-off');
			$('#audio-1').prop('muted', true);
			$('#audio-2').prop('muted', true);

		} else {
			
			$('a.sound span').removeClass('fa-volume-off').addClass('fa-volume-up');
			$('#audio-1').prop('muted', false);
			$('#audio-2').prop('muted', false);

		}

	});

});

var live_chat = {
	pending_chats_last_activity: null,
	chat_last_activity: null,
	visitors_last_activity: null,
	operator_status: null,
	active: false,
	start: function() {
	
		$.ajax({
			type: 'POST',
			url: 'index/update',
			dataType: 'json',
			timeout: 130 * 1000,
			cache: false,
			data: { 
				pending_chats_last_activity: live_chat.pending_chats_last_activity,
				chat_last_activity: live_chat.chat_last_activity,
				visitors_last_activity: live_chat.visitors_last_activity,
				operator_status : live_chat.operator_status
			},
			success: function(data) {

				if (data.pending_chats['success']) {

					$('#pending-chat').html(data.pending_chats['chat']);

					if (data.pending_chats['new_chat']) {
						
						$('#audio-2').trigger('play');
						
					}

				} else {

					$('#pending-chat').html(data.pending_chats['chat']);

				}
				
				if (data.operator_status['success']) {
					
					$('#operator-offline').hide(0, function() {
						
						$('#operator-online').removeClass('hidden').fadeIn(0);
						$('#operator-status').prop('disabled', false);
						
					});
					
				} else {

					$('#operator-online').hide(0, function() {
						
						$('#operator-offline').removeClass('hidden').fadeIn(0);
						$('#operator-status').prop('disabled', false);
						
					});
					
				}

				$('#visitors').html(data.online_visitors['visitors']);

				if (data.chat_messages['chat_expired']) {
					
					live_chat.stop();
					
				}
				
				if (data.chat_messages['success']) {
					
					$('#chat-messages').html(data.chat_messages['messages']);
					$('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);

					$('#visitor-details').html(data.chat_messages['visitor_details']).closest('.panel').removeClass('hidden');

					if (data.chat_messages['new_message']) {
						
						$('#audio-1').trigger('play');
						$('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);
						
					}
					
					data.chat_messages['user_typing'] ? $('#user-typing').show(0) : $('#user-typing').hide(0);
					
					if (data.chat_messages['chat_active']) {
						
						$('#form-1 :button, #form-2 :input, #form-2 :button').prop('disabled', false);

					} else {

						$('#form-1 :button, #form-2 :input, #form-2 :button').prop('disabled', false).not('#operator-status, #stop-chat').prop('disabled', true);
						
					}

				} else {

					$('#form-1 :button, #form-2 :input, #form-2 :button').not('#operator-status, #stop-chat').prop('disabled', true);

				}
				
				live_chat.pending_chats_last_activity = data.pending_chats['last_activity'];
				live_chat.chat_last_activity = data.chat_messages['last_activity'];
				live_chat.visitors_last_activity = data.online_visitors['last_activity'];
				live_chat.operator_status = data.operator_status['success'];

				$('#loader-1').addClass('hidden');
				
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
			url: 'index/stop_chat',
			dataType: 'json',
			success: function(data) {
				
				if (data.success) {
					
					live_chat.active = false;
					
				} else {
					
					$('#loader-1').addClass('hidden');
					
				}

			}
		});
		
	}
}

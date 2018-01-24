<div id="chat-wrapper">
	
	<script type="text/javascript">
		var absolute_url = '<?php echo $absolute_url; ?>';
	</script>
	
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			$.getScript('<?php echo $absolute_url; ?>assets/js/jquery.validate.min.js');
			$.getScript('<?php echo $absolute_url; ?>assets/js/live_chat.min.js', function() {
				
				if (is_mobile()) {
					
					$('#chat-wrapper').css({
						'width' : '100%', 
						'left' : '0', 
						'right' : '0'
					});

				} else {
					
					$('#chat-wrapper').css({
						'<?php echo $chat_position; ?>' : '0'
					});
					
				}

				var audio = $('<audio id="audio-1"></audio>');
				
				audio.append('<source src="<?php echo $absolute_url; ?>assets/sounds/alert_1.ogg" type="audio/ogg">');
				audio.append('<source src="<?php echo $absolute_url; ?>assets/sounds/alert_1.mp3" type="audio/mpeg">');
				audio.append('<source src="<?php echo $absolute_url; ?>assets/sounds/alert_1.wav" type="audio/wav">');
				audio.appendTo('#chat-wrapper');
				
				$('head').append('<link href="<?php echo $absolute_url; ?>assets/css/live_chat.css" rel="stylesheet">');
				
				$('#chat-wrapper').css('display', 'none').delay(2000).fadeIn();
				$('#chat-status').css('background-color', '<?php echo $background_color_1; ?>');
				$('#chat-status').css('color', '<?php echo $color_1; ?>');
				$('#chat-form-container').css('background-color', '<?php echo $background_color_2; ?>');
				$('#chat-form-container').css('color', '<?php echo $color_2; ?>');

			});
			
		});
	</script>
	
	<div class="chat-container">

		<div class="chat-row">

			<div class="chat-column">
				
				<div id="chat-alert-1" class="chat-alert chat-alert-warning" style="display: none;">
					<button type="button" class="chat-button-close">
						<span>&times;</span>
					</button>
					<a href="#" class="chat-alert-link"></a>
				</div>

				<div id="chat-status">
					
					<span class="chat-pull-left">&nbsp;</span>
					<span id="chat-arrow" class="chat-pull-right fa fa-chevron-up"></span> 
					<span id="chat-loader-1" class="chat-hidden chat-pull-right fa fa-spinner fa-spin fa-lg"></span>
				
				</div>

				<div id="chat-form-container">
					
					<div id="chat-loader-2">
						<span class="fa fa-spinner fa-spin fa-5x"></span>
					</div>
					
					<form id="chat-form"></form>

				</div>

			</div>
			
		</div>

	</div>
	
</div>

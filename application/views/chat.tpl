<div class="chat-row">
	
	<div class="chat-column">

		<div id="chat-content">

			<input type="hidden" name="token" id="token" value="<?php echo generate_token('form'); ?>">
			
			<ul id="chat-messages"></ul>

		</div>
		
	</div>

</div>

<div class="chat-row">
	
	<div class="chat-column">
		
		<div class="chat-form-group">
			
			<a href="#" id="chat-sound"><span class="fa fa-volume-up"></span></a>
			<a href="#" id="stop-chat"><span class="fa fa-times-circle"></span></a>
			
			<div id="chat-queue" class="chat-pull-right">
				<span class="chat-label chat-label-default"><?php echo get_text('message_visitor_queue'); ?></span>
			</div>
			
			<div id="chat-operator-typing" class="chat-pull-right" style="display: none;">
				<span class="chat-label chat-label-default">
					<span class="fa fa-pencil"></span> <?php echo get_text('message_operator_typing'); ?>
				</span>
			</div>

		</div>
		
	</div>
	
</div>

<div class="chat-row">
	
	<div class="chat-column">
		
		<div class="chat-form-group">
			
			<div class="chat-input-group">
				
				<input type="text" name="message" id="message" class="chat-form-control" data-msg-required="<?php sprintf(get_text('validator_required'), get_text('message')); ?>">
				<span class="chat-input-group-button">
					<button type="button" id="send-message" class="chat-button" style="background-color: <?php echo $background_color_3; ?>; color: <?php echo $color_3; ?>;"><?php echo get_text('send'); ?></button>
				</span>
				
			</div>
			
		</div>
		
		<a href="https://cokiyiya.com" target="_blank" class="chat-pull-right"><span class="chat-small">Kingmaggot</span></a>
		
	</div>
	
</div>

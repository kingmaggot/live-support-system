<div class="chat-row">
	
	<div class="chat-column">
		
		<p><?php echo get_text('message_online'); ?></p>
		
	</div>
	
</div>

<div class="chat-row">
	
	<div class="chat-column">
		
		<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
		
		<div class="chat-form-group">
			
			<label for="username"><?php echo get_text('name'); ?></label>
			<input type="text" name="username" id="username" class="chat-form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('name')); ?>">
		
		</div>
		
		<div class="chat-form-group">
			
			<label for="email"><?php echo get_text('email_address'); ?></label>
			<input type="text" name="email" id="email" class="chat-form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('email_address')); ?>">
		
		</div>
		
		<div class="chat-form-group">
			
			<label for="department-id"><?php echo get_text('department'); ?></label>
			<select name="department_id" id="department-id" class="chat-form-control">
				<?php foreach ($departments as $value): ?>
					<?php if ($value['total'] > $value['operator_timeout']): ?>
						<option value="<?php echo $value['department_id']; ?>"><?php echo $value['department_name']; ?> - <?php echo get_text('offline'); ?></option>
					<?php else: ?>
						<option value="<?php echo $value['department_id']; ?>"><?php echo $value['department_name']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			
		</div>
		
		<div class="chat-form-group">
			
			<label for="message"><?php echo get_text('message'); ?></label>
			<textarea name="message" id="message" class="chat-form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('message')); ?>"></textarea>
		
		</div>
		
		<div class="chat-form-group">
			
			<button type="button" id="start-chat" class="chat-button chat-button-small" style="background-color: <?php echo $background_color_3; ?>; color: <?php echo $color_3; ?>;"><?php echo get_text('start_chat'); ?></button>
			
			<a href="https://cokiyiya.com" target="_blank" class="chat-pull-right"><span class="chat-small">Kingmaggot</span></a>
			
		</div>
		
	</div>
	
</div>

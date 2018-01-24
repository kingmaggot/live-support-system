<div class="chat-row">

	<div class="chat-column">
		
		<p><?php echo get_text('message_offline'); ?></p>
		
	</div>
	
</div>

<div class="chat-row">

	<div class="chat-column">
		
		<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">

		<div class="chat-form-group">
			
			<label for="username"><?php echo get_text('name'); ?></label>
			<input type="text" name="username" id="username" class="chat-form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('name')); ?>" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
		
		</div>
		
		<div class="chat-form-group">
			
			<label for="email"><?php echo get_text('email_address'); ?></label>
			<input type="text" name="email" id="email" class="chat-form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('email_address')); ?>" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
		
		</div>
		
		<div class="chat-form-group">
			
			<label for="department_id"><?php echo get_text('department'); ?></label>
			<select name="department_id" id="department_id" class="chat-form-control">
				<?php foreach ($departments as $value): ?>
					<?php if (isset($_POST['department_id'])): ?>
						<option value="<?php echo $value['department_id']; ?>" <?php if ($_POST['department_id'] == $value['department_id']) echo "selected"; ?>><?php echo $value['department_name']; ?></option>
					<?php else: ?>
						<option value="<?php echo $value['department_id']; ?>"><?php echo $value['department_name']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			
		</div>
		
		<div class="chat-form-group">
			
			<label for="message"><?php echo get_text('message'); ?></label>
			<textarea name="message" id="message" class="chat-form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('message')); ?>"><?php if (isset($_POST['message'])) echo $_POST['message']; ?></textarea>
		
		</div>
		
		<div class="chat-form-group">
			
			<button type="button" id="send-email" class="chat-button chat-button-small" style="background-color: <?php echo $background_color_3; ?>; color: <?php echo $color_3; ?>;"><?php echo get_text('send_message'); ?></button>
		
			<a href="https://cokiyiya.com" target="_blank" class="chat-pull-right"><span class="chat-small">Kingmaggot</span></a>
		
		</div>
		
	</div>
	
</div>

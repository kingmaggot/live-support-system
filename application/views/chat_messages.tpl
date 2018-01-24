<?php if ($chat_messages['success']): ?>

	<?php foreach($chat_messages['messages'] as $value): ?>
	
		<?php if (!empty($value['operator_name'])): ?>
		
			<li class="chat-left">
				<span class="chat-time chat-pull-right"><?php echo $value['date']; ?> <?php echo $value['time']; ?></span>
				<p class="chat-name"><span class="chat-label chat-label-default"><?php echo $value['name']; ?></span></p>
				<?php if (empty($value['operator_email']) || !file_exists('uploads/images/' . md5($value['operator_email']) . '.png')): ?>
					<img src="<?php echo $absolute_url; ?>uploads/images/profile.png" class="chat-pull-left chat-image-circle" alt="">
				<?php else: ?>
					<img src="<?php echo $absolute_url; ?>uploads/images/<?php echo md5($value['operator_email']); ?>.png" class="chat-pull-left chat-image-circle" alt="">
				<?php endif;?>
				<div class="chat-message"><?php echo $value['message']; ?></div>
			</li>
			
		<?php else: ?>
		
			<li class="chat-right">
				<span class="chat-time chat-pull-left"><?php echo $value['date']; ?> <?php echo $value['time']; ?></span>
				<p class="chat-name chat-text-right"><span class="chat-label chat-label-primary"><?php echo $value['name']; ?></span></p>
				<img src="<?php echo $absolute_url; ?>uploads/images/profile.png" class="chat-pull-right chat-image-circle" alt="">
				<div class="chat-message"><?php echo $value['message']; ?></div>
			</li>
			
		<?php endif;?>
		
	<?php endforeach; ?>
	
<?php endif; ?>

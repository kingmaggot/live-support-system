<?php if ($chat_messages['success']): ?>

	<?php foreach($chat_messages['messages'] as $value): ?>
	
		<?php if (!empty($value['operator_name'])): ?>
		
			<li class="left">
				<span class="pull-right time"><?php echo $value['date']; ?> <?php echo $value['time']; ?></span>
				<p class="name"><span class="label label-default"><?php echo $value['name']; ?></span></p>
				<?php if (empty($value['operator_email']) || !file_exists('uploads/images/' . md5($value['operator_email']) . '.png')): ?>
					<img src="<?php echo $absolute_url; ?>uploads/images/profile.png" class="pull-left img-circle" alt="">
				<?php else: ?>
					<img src="<?php echo $absolute_url; ?>uploads/images/<?php echo md5($value['operator_email']); ?>.png" class="pull-left img-circle" alt="">
				<?php endif;?>
				<div class="message"><?php echo $value['message']; ?></div>
			</li>
			
		<?php else: ?>
		
			<li class="right">
				<span class="pull-left time"><?php echo $value['date']; ?> <?php echo $value['time']; ?></span>
				<p class="text-right name"><span class="label label-primary"><?php echo $value['name']; ?></span></p>
				<img src="<?php echo $absolute_url; ?>uploads/images/profile.png" class="pull-right img-circle" alt="">
				<div class="message"><?php echo $value['message']; ?></div>
			</li>
			
		<?php endif;?>
		
	<?php endforeach; ?>
	
<?php endif; ?>

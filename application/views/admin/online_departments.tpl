<?php if (is_array($online_departments)): ?>

	<?php foreach($online_departments as $value): ?>
	
		<li>
			<a class="transfer-chat" data-id="<?php echo $value['department_id']; ?>">
				<?php echo $value['department_name']; ?>
			</a>
		</li>
	
	<?php endforeach; ?>
	
<?php else: ?>

	<li><a><?php echo $online_departments; ?></a></li>
	
<?php endif; ?>

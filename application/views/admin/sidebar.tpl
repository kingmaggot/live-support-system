<div id="sidebar" class="collapse sidebar-collapse">
	
	<div class="media">
		
		<a href="<?php echo $absolute_url; ?>admin/profile" class="pull-left">
			<?php if (file_exists('uploads/images/' . md5($_SESSION['user_email']) . '.png')): ?>
				<img src="<?php echo $absolute_url; ?>uploads/images/<?php echo md5($_SESSION['user_email']) . '.png'; ?>" class="img-circle" alt="">
			<?php else: ?>
				<img src="<?php echo $absolute_url; ?>uploads/images/profile.png" class="img-circle" alt="">
			<?php endif; ?>
		</a>
		
		<div class="media-body">
			<?php echo get_text('welcome_back'); ?>,
			<h4 class="media-heading"><?php echo $full_name; ?></h4>
			<a href="<?php echo $absolute_url; ?>admin/profile"><?php echo get_text('profile'); ?></a>
			<a href="logout"><?php echo get_text('logout'); ?></a>
		</div>
		
	</div>
	
	<ul>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/index" <?php if ($current_page == 'index'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-home fa-lg"></span> <?php echo get_text('chat_panel'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/chat_history" <?php if ($current_page == 'chat_history'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-history fa-lg"></span> <?php echo get_text('chat_history'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/canned_messages" <?php if ($current_page == 'canned_messages'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-comment-o fa-lg"></span> <?php echo get_text('canned_messages'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/departments" <?php if ($current_page == 'departments'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-cubes fa-lg"></span> <?php echo get_text('departments'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/operators" <?php if ($current_page == 'operators'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-headphones fa-lg"></span> <?php echo get_text('operators'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/groups" <?php if ($current_page == 'groups'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-group fa-lg"></span> <?php echo get_text('groups'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/blocked_visitors" <?php if ($current_page == 'blocked_visitors'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-ban fa-lg"></span> <?php echo get_text('blocked_visitors'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/access_logs" <?php if ($current_page == 'access_logs'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-clock-o fa-lg"></span> <?php echo get_text('access_logs'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo $absolute_url; ?>admin/settings" <?php if ($current_page == 'settings'): ?>class="active"<?php endif; ?>>
				<span class="fa fa-cogs fa-lg"></span> <?php echo get_text('settings'); ?>
			</a>
		</li>
	</ul>
	
</div>

<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('chat_history'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-7">
				
				<div class="panel panel-default">
					
					<div class="panel-heading"><?php echo get_text('chat_history'); ?></div>
					
					<div id="chat-content" class="panel-body">
						
						<ul id="chat-messages">
							<?php if ($chat['success']): ?>
								<?php foreach($chat['data'] as $value): ?>
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
						</ul>
						
					</div>
					
				</div>
				
			</div>
			
			<div class="col-md-5">
				
				<div class="panel panel-default">
					
					<div class="panel-heading"><?php echo get_text('details'); ?></div>
					
					<div class="panel-body">
						
						<?php if ($chat['success']): ?>
						
							<p class="small"><?php echo get_text('email_address'); ?>: <?php echo $chat['visitor_details']['email']; ?></p>
							<p class="small"><?php echo get_text('department'); ?>: <?php echo $chat['visitor_details']['department_name']; ?></p>
							<p class="small"><?php echo get_text('platform'); ?>: <?php echo $chat['visitor_details']['platform']; ?></p>
							<p class="small"><?php echo get_text('browser'); ?>: <?php echo $chat['visitor_details']['browser']; ?></p>
							
							<?php if (!empty($chat['visitor_details']['referer'])): ?>
								<p class="small">
									<?php echo get_text('referer'); ?>:
									<a href="<?php echo $chat['visitor_details']['referer_url']; ?>" target="_blank">
										<?php echo $chat['visitor_details']['referer_from']; ?>
									</a>
								</p>
							<?php endif; ?>
							
							<p class="small"><?php echo get_text('time_in_chat'); ?>: <?php echo $chat['visitor_details']['elapsed_time']; ?></p>
							<p class="small">
								<?php echo get_text('ip_address'); ?>:
								<?php if (!empty($ip_tracker_url)): ?>
									<a href="<?php echo str_replace('ip_address', $chat['visitor_details']['ip_address'], $ip_tracker_url); ?>" class="geolocation">
										<?php echo $chat['visitor_details']['ip_address']; ?>
									</a>
								<?php else: ?>
									<?php echo $chat['visitor_details']['ip_address']; ?>
								<?php endif; ?>
							</p>
							
							<div id="map-wrapper">
								
								<div id="map"></div>
								
								<p class="small text-center"></p>
								
							</div>
							
						<?php endif; ?>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>

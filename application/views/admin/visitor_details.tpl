<?php if ($chat_messages['success']): ?>

	<div class="row">
			
		<div class="col-md-6">
			
			<p><strong><?php echo $chat_messages['visitor_details']['username']; ?></strong></p>
			
			<p class="small text-muted"><?php echo $chat_messages['visitor_details']['email']; ?></p>
			
		</div>
		
		<div class="col-md-6">
			
			<div class="pull-right">
				
				<p class="small">
					<?php echo get_text('platform'); ?>:
					<span class="text-muted"><?php echo $chat_messages['visitor_details']['platform']; ?></span>
				</p>
				
				<p class="small">
					<?php echo get_text('browser'); ?>:
					<span class="text-muted"><?php echo $chat_messages['visitor_details']['browser']; ?></span>
				</p>
				
				<p class="small">
					<?php echo get_text('from'); ?>:
					<a href="<?php echo $chat_messages['visitor_details']['referer_url']; ?>" target="_blank">
						<?php echo $chat_messages['visitor_details']['referer_from']; ?>
					</a>
				</p>
				
				<p class="small">
					<?php echo get_text('ip_address'); ?>:
					<?php if (!empty($ip_tracker_url)): ?>
						<a href="<?php echo str_replace('ip_address', $chat_messages['visitor_details']['ip_address'], $ip_tracker_url); ?>" class="geolocation">
							<?php echo $chat_messages['visitor_details']['ip_address']; ?>
						</a>
					<?php else: ?>
						<?php echo $chat_messages['visitor_details']['ip_address']; ?>
					<?php endif; ?>
				</p>
				
			</div>
			
		</div>
		
	</div>
	
	<div class="row">
		
		<div class="col-md-12">
			
			<div id="map-wrapper">
				
				<div id="map"></div>
				
				<p class="small text-center"></p>
				
			</div>
			
		</div>
		
	</div>
	
<?php endif; ?>

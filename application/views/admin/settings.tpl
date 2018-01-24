<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#background-color-1').minicolors({
			<?php if (isset($options['background_color_1'])): ?>
				defaultValue: '<?php echo ($options['background_color_1']); ?>',
			<?php endif; ?>
			theme: 'bootstrap',
			letterCase: 'uppercase'
		});

		$('#background-color-2').minicolors({
			<?php if (isset($options['background_color_2'])): ?>
				defaultValue: '<?php echo ($options['background_color_2']); ?>',
			<?php endif; ?>
			theme: 'bootstrap',
			letterCase: 'uppercase'
		});

		$('#background-color-3').minicolors({
			<?php if (isset($options['background_color_3'])): ?>
				defaultValue: '<?php echo ($options['background_color_3']); ?>',
			<?php endif; ?>
			theme: 'bootstrap',
			letterCase: 'uppercase'
		});

		$('#color-1').minicolors({
			<?php if (isset($options['color_1'])): ?>
				defaultValue: '<?php echo ($options['color_1']); ?>',
			<?php endif; ?>
			theme: 'bootstrap',
			letterCase: 'uppercase'
		});

		$('#color-2').minicolors({
			<?php if (isset($options['color_2'])): ?>
				defaultValue: '<?php echo ($options['color_2']); ?>',
			<?php endif; ?>
			theme: 'bootstrap',
			letterCase: 'uppercase'
		});

		$('#color-3').minicolors({
			<?php if (isset($options['color_3'])): ?>
				defaultValue: '<?php echo ($options['color_3']); ?>',
			<?php endif; ?>
			theme: 'bootstrap',
			letterCase: 'uppercase'
		});

		$('#tab a').click(function (event) {
			
			event.preventDefault();
			
			$(this).tab('show');
			
			if ($(event.target).attr('href') == '#embed-code') {
				
				$("button[name='save']").hide();
				
			} else {
				
				$("button[name='save']").show();
				
			}

		});
		
	});
</script>

<div id="content">
	
	<div class="container-fluid">
		
		<div class="page-header">
			
			<h1><?php echo get_text('settings'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading">

						<div class="row">
							
							<div class="col-md-7"><?php echo get_text('edit_settings'); ?></div>
							
						</div>
						
					</div>
					
					<div class="panel-body">

						<?php if (isset($has_errors) && $has_errors): ?>
						
							<div class="alert alert-danger">
								
								<strong><?php echo get_text('error_found_errors'); ?></strong>
								
								<ul>
									<?php foreach ($errors as $value): ?>
										<li><?php echo $value; ?></li>
									<?php endforeach; ?>
								</ul>
								
							</div>
							
						<?php endif; ?>

						<?php if (isset($success) && $success): ?>
						
							<p class="text-center">
								<span class="label label-success"><?php echo get_text('success_data_saved'); ?></span>
							</p>
							
						<?php endif; ?>
						
						<ul id="tab" class="nav nav-tabs">
							<li class="active"><a href="#general"><?php echo get_text('general'); ?></a></li>
							<li><a href="#server"><?php echo get_text('server'); ?></a></li>
							<li><a href="#authentication"><?php echo get_text('authentication'); ?></a></li>
							<li><a href="#geolocation"><?php echo get_text('geolocation'); ?></a></li>
							<li><a href="#style"><?php echo get_text('style'); ?></a></li>
							<li><a href="#embed-code"><?php echo get_text('embed_code'); ?></a></li>
						</ul>
						
						<form method="post">
							
							<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
							
							<div class="tab-content">
								
								<div id="general" class="tab-pane active">
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="site-title"><?php echo get_text('site_title'); ?></label>
												<input type="text" name="site_title" id="site-title" class="form-control" value="<?php echo $options['site_title']; ?>">
												
											</div>
											
											<div class="form-group">
												
												<label for="admin-email"><?php echo get_text('email_address'); ?></label>
												<input type="text" name="admin_email" id="admin-email" class="form-control" value="<?php echo $options['admin_email']; ?>">
												
											</div>

											<div class="form-group">
												
												<label for="charset"><?php echo get_text('charset'); ?></label>
												<input type="text" name="charset" id="charset" class="form-control" value="<?php echo $options['charset']; ?>">
												
											</div>

											<div class="form-group">
												
												<label for="absolute-url"><?php echo get_text('use_https'); ?></label>
												<select name="absolute_url" id="absolute-url" class="form-control">
													<?php if ($options['absolute_url'] == 'https'): ?>
														<option value="<?php echo str_replace('http://', 'https://', $absolute_url); ?>" selected><?php echo get_text('yes'); ?></option>
														<option value="<?php echo str_replace('https://', 'http://', $absolute_url); ?>"><?php echo get_text('no'); ?></option>
													<?php else: ?>
														<option value="<?php echo str_replace('http://', 'https://', $absolute_url); ?>"><?php echo get_text('yes'); ?></option>
														<option value="<?php echo str_replace('https://', 'http://', $absolute_url); ?>" selected><?php echo get_text('no'); ?></option>
													<?php endif; ?>
												</select>
												
											</div>

											<div class="form-group">
												
												<label for="chat-position"><?php echo get_text('position'); ?></label>
												<select name="chat_position" id="chat-position" class="form-control">
													<option value="left" <?php if ($options['chat_position'] == 'left') echo 'selected'; ?>><?php echo get_text('bottom_left'); ?></option>
													<option value="right" <?php if ($options['chat_position'] == 'right') echo 'selected'; ?>><?php echo get_text('bottom_right'); ?></option>
												</select>
												
											</div>
											
										</div>
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="operator-timeout"><?php echo get_text('operator_timeout'); ?></label>
												<select name="operator_timeout" id="operator-timeout" class="form-control">
													<?php foreach ($timeout_1 as $key => $value): ?>
														<option value="<?php echo $key; ?>" <?php if ($options['operator_timeout'] == $key) echo 'selected'; ?>><?php echo $value; ?></option>
													<?php endforeach; ?>
												</select>
												
											</div>
											
											<div class="form-group">
												
												<label for="max-connections"><?php echo get_text('connections_per_ip_address'); ?></label>
												<input type="text" name="max_connections" id="max-connections" class="form-control" value="<?php echo $options['max_connections']; ?>">
												
											</div>
											
											<div class="form-group">
												
												<label for="max-visitors"><?php echo get_text('number_of_visitors'); ?></label>
												<input type="text" name="max_visitors" id="max-visitors" class="form-control" value="<?php echo $options['max_visitors']; ?>">
												
											</div>
											
											<div class="form-group">
												
												<label for="chat-timeout"><?php echo get_text('chat_timeout'); ?></label>
												<select name="chat_timeout" id="chat-timeout" class="form-control">
													<?php foreach ($timeout_2 as $key => $value): ?>
														<option value="<?php echo $key; ?>" <?php if ($options['chat_timeout'] == $key) echo 'selected'; ?>><?php echo $value; ?></option>
													<?php endforeach; ?>
												</select>
												
											</div>
											
											<div class="form-group">
												
												<label for="records-per-page"><?php echo get_text('records_per_page'); ?></label>
												<input type="text" name="records_per_page" id="records-per-page" class="form-control" value="<?php echo $options['records_per_page']; ?>">
												
											</div>
											
										</div>
										
									</div>
									
								</div>
								
								<div id="server" class="tab-pane">
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="timezone"><?php echo get_text('timezone'); ?></label>
												<select name="timezone" id="timezone" class="form-control">
													<?php foreach ($timezones as $key => $value): ?>
														<option value="<?php echo $key; ?>" <?php if ($options['timezone'] == $key) echo 'selected'; ?>><?php echo $value; ?></option>
													<?php endforeach; ?>
												</select>
												
											</div>
											
											<div class="form-group">
												
												<label for="date-format"><?php echo get_text('date_format'); ?></label>
												<select name="date_format" id="date-format" class="form-control">
													<?php foreach ($date_format as $key => $value): ?>
														<option value="<?php echo $key; ?>" <?php if ($options['date_format'] == $key) echo 'selected'; ?>><?php echo $value; ?></option>
													<?php endforeach; ?>
												</select>
												
											</div>
											
											<div class="form-group">
												
												<label for="time-format"><?php echo get_text('time_format'); ?></label>
												<select name="time_format" id="time-format" class="form-control">
													<?php foreach ($time_format as $key => $value): ?>
														<option value="<?php echo $key; ?>" <?php if ($options['time_format'] == $key) echo 'selected'; ?>><?php echo $value; ?></option>
													<?php endforeach; ?>
												</select>
												
											</div>
											
										</div>
										
									</div>
									
								</div>
								
								<div id="authentication" class="tab-pane">

									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="user-timeout"><?php echo get_text('session_timeout'); ?></label>
												<select name="user_timeout" id="user-timeout" class="form-control">
													<?php foreach ($timeout_3 as $key => $value): ?>
														<option value="<?php echo $key; ?>" <?php if ($options['user_timeout'] == $key) echo 'selected'; ?>><?php echo $value; ?></option>
													<?php endforeach; ?>
												</select>
												
											</div>

											<div class="form-group">
												
												<label for="access-logs"><?php echo get_text('access_logs'); ?></label>
												<select name="access_logs" id="access-logs" class="form-control">
													<?php if ($options['access_logs']): ?>
														<option value="1" selected><?php echo get_text('yes'); ?></option>
														<option value="0"><?php echo get_text('no'); ?></option>
													<?php else: ?>
														<option value="1"><?php echo get_text('yes'); ?></option>
														<option value="0" selected><?php echo get_text('no'); ?></option>
													<?php endif; ?>
												</select>
												
											</div>
											
										</div>
										
									</div>
									
								</div>
								
								<div id="geolocation" class="tab-pane">

									<div class="row">
										
										<div class="col-md-6">
											
											<p><?php echo get_text('geolocation_1'); ?></p>

											<p><?php echo get_text('geolocation_2'); ?></p>

											<table class="table table-bordered">
												<thead>
													<tr>
														<th><?php echo get_text('name'); ?></th>
														<th><?php echo get_text('description'); ?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?php echo get_text('city'); ?></td>
														<td><?php echo get_text('geolocation_3'); ?></td>
													</tr>
													<tr>
														<td><?php echo get_text('country'); ?></td>
														<td><?php echo get_text('geolocation_4'); ?></td>
													</tr>
													<tr>
														<td><?php echo get_text('latitude'); ?></td>
														<td><?php echo get_text('geolocation_5'); ?></td>
													</tr>
													<tr>
														<td><?php echo get_text('longitude'); ?></td>
														<td><?php echo get_text('geolocation_6'); ?></td>
													</tr>
												</tbody>
											</table>
											
										</div>
										
										<div class="col-md-6">
											
											<p><?php echo get_text('geolocation_7'); ?></p>
											
											<ul>
												<li><code>http://www.example.com/8.8.8.8</code></li>
												<li><code>http://www.example.com/8.8.8.8/json</code></li>
												<li><code>http://www.example.com/json/8.8.8.8</code></li>
											</ul>
											
											<p><?php echo get_text('geolocation_8'); ?></p>
											
											<ul>
												<li><code>http://www.example.com/ip_address</code></li>
												<li><code>http://www.example.com/ip_address/json</code></li>
												<li><code>http://www.example.com/json/ip_address</code></li>
											</ul>
											
											<div class="form-group">
												
												<label for="ip-tracker-url"><?php echo get_text('ip_tracker_url'); ?></label>
												<input type="text" name="ip_tracker_url" id="ip-tracker-url" class="form-control" value="<?php echo $options['ip_tracker_url']; ?>">
												
											</div>

										</div>
										
									</div>
									
								</div>
								
								<div id="style" class="tab-pane">
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="background-color-1"><?php echo get_text('header_background_color'); ?></label>
												<input type="text" name="background_color_1" id="background-color-1" class="form-control">
												
											</div>

											<div class="form-group">
												
												<label for="background-color-2"><?php echo get_text('content_background_color'); ?></label>
												<input type="text" name="background_color_2" id="background-color-2" class="form-control">
												
											</div>

											<div class="form-group">
												
												<label for="background-color-3"><?php echo get_text('button_background_color'); ?></label>
												<input type="text" name="background_color_3" id="background-color-3" class="form-control">
												
											</div>
											
										</div>
										
										<div class="col-md-6">

											<div class="form-group">
												
												<label for="color-1"><?php echo get_text('header_text_color'); ?></label>
												<input type="text" name="color_1" id="color-1" class="form-control">
												
											</div>

											<div class="form-group">
												
												<label for="color-2"><?php echo get_text('content_text_color'); ?></label>
												<input type="text" name="color_2" id="color-2" class="form-control">
												
											</div>

											<div class="form-group">
												
												<label for="color-3"><?php echo get_text('button_text_color'); ?></label>
												<input type="text" name="color_3" id="color-3" class="form-control">
												
											</div>
											
										</div>
										
									</div>
									
								</div>
								
								<div id="embed-code" class="tab-pane">

									<p><?php echo get_text('embed_code_1'); ?></p>
									
									<div class="form-group">
										<textarea class="form-control" rows="15"><?php echo $embed_code; ?></textarea>
									</div>
									
								</div>
								
							</div>
							
							<button type="submit" name="submit" class="btn btn-primary"><?php echo get_text('save'); ?></button>
							
						</form>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>

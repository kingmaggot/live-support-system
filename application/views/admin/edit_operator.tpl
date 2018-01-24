<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('operators'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading"><?php echo get_text('edit_operator'); ?></div>
					
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
						
						<form method="post">
							
							<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
							
							<?php if ($operator['success']): ?>
							
								<?php foreach($operator['data'] as $value): ?>
									
									<input type="hidden" name="user_id" value="<?php echo $value['user_id']; ?>">
									<input type="hidden" name="operator_id" value="<?php echo $value['operator_id']; ?>">
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="first-name"><?php echo get_text('first_name'); ?></label>
												<input type="text" name="first_name" id="first-name" class="form-control" value="<?php echo $value['first_name']; ?>">
												
											</div>
											
											<div class="form-group">
												
												<label for="last-name"><?php echo get_text('last_name'); ?></label>
												<input type="text" name="last_name" id="last-name" class="form-control" value="<?php echo $value['last_name']; ?>">
												
											</div>

											<div class="form-group">
												
												<label for="user-password"><?php echo get_text('password'); ?></label>
												<input type="password" name="user_password" id="user-password" class="form-control">
												
											</div>

											<div class="form-group">
												
												<label for="confirm-password"><?php echo get_text('confirm_password'); ?></label>
												<input type="password" name="confirm_password" id="confirm-password" class="form-control">
												
											</div>

											<div class="form-group">
												
												<label for="departments"><?php echo get_text('departments'); ?></label>
												<select name="departments[]" id="departments" class="form-control" multiple>
													<?php foreach ($value['departments'] as $value2): ?>
														<?php if (in_array($value2['department_id'], $value['department_operators'])): ?>
															<option value="<?php echo $value2['department_id']; ?>" selected><?php echo $value2['department_name']; ?></option>
														<?php else: ?>
															<option value="<?php echo $value2['department_id']; ?>"><?php echo $value2['department_name']; ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												</select>
												
											</div>
											
										</div>
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="user-email"><?php echo get_text('email_address'); ?></label>
												<input type="text" name="user_email" class="form-control" id="user-email" value="<?php echo $value['user_email']; ?>">
												
											</div>
											
											<div class="form-group">
												
												<label for="user-status"><?php echo get_text('status'); ?></label>
												<select name="user_status" id="user-status" class="form-control">
													<?php if ($value['user_status']): ?>
														<option value="1" selected><?php echo get_text('active'); ?></option>
														<option value="0"><?php echo get_text('inactive'); ?></option>
													<?php else: ?>
														<option value="1"><?php echo get_text('active'); ?></option>
														<option value="0" selected><?php echo get_text('inactive'); ?></option>
													<?php endif; ?>
												</select>
												
											</div>
											
											<div class="form-group">
												
												<label for="hide-online"><?php echo get_text('hide_online'); ?></label>
												<select name="hide_online" id="hide-online" class="form-control">
													<?php if ($value['hide_online']): ?>
														<option value="1" selected><?php echo get_text('yes'); ?></option>
														<option value="0"><?php echo get_text('no'); ?></option>
													<?php else: ?>
														<option value="1"><?php echo get_text('yes'); ?></option>
														<option value="0" selected><?php echo get_text('no'); ?></option>
													<?php endif; ?>
												</select>
												
											</div>

											<div class="form-group">
												
												<label for="group-id"><?php echo get_text('group'); ?></label>
												<select name="group_id" id="group-id" class="form-control">
													<?php foreach ($value['groups'] as $value3): ?>
														<?php if ($value['group_id'] == $value3['group_id']): ?>
															<option value="<?php echo $value3['group_id']; ?>" selected><?php echo $value3['group_name']; ?></option>
														<?php else: ?>
															<option value="<?php echo $value3['group_id']; ?>"><?php echo $value3['group_name']; ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												</select>
												
											</div>
											
										</div>
										
									</div>
									
								<?php endforeach; ?>
								
							<?php endif; ?>
							
							<button type="submit" name="submit" class="btn btn-primary"><?php echo get_text('save'); ?></button>
							
						</form>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>

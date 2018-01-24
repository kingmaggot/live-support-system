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
					
					<div class="panel-heading"><?php echo get_text('add_new_operator'); ?></div>
					
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
							
							<div class="row">
								
								<div class="col-md-6">
									
									<div class="form-group">
										
										<label for="first-name"><?php echo get_text('first_name'); ?></label>
										<input type="text" name="first_name" id="first-name" class="form-control" value="<?php if (!empty($_POST['first_name'])) echo sanitize($_POST['first_name'], 'string'); ?>">
										
									</div>
									
									<div class="form-group">
										
										<label for="last-name"><?php echo get_text('last_name'); ?></label>
										<input type="text" name="last_name" id="last-name" class="form-control" value="<?php if (!empty($_POST['last_name'])) echo sanitize($_POST['last_name'], 'string'); ?>">
										
									</div>

									<div class="form-group">
										
										<label for="user-password"><?php echo get_text('password'); ?></label>
										<input type="password" name="user_password" id="user-password" class="form-control" value="<?php if (!empty($_POST['user_password'])) echo sanitize($_POST['user_password'], 'string'); ?>">
										
									</div>

									<div class="form-group">
										
										<label for="confirm-password"><?php echo get_text('confirm_password'); ?></label>
										<input type="password" name="confirm_password" id="confirm-password" class="form-control" value="<?php if (!empty($_POST['confirm_password'])) echo sanitize($_POST['confirm_password'], 'string'); ?>">
										
									</div>
									
									<div class="form-group">
										
										<label for="departments"><?php echo get_text('departments'); ?></label>
										<select name="departments[]" id="departments" class="form-control" multiple>
											<?php foreach ($departments['data'] as $value): ?>
												<option value="<?php echo $value['department_id']; ?>"><?php echo $value['department_name']; ?></option>
											<?php endforeach; ?>
										</select>
										
									</div>
									
								</div>
								
								<div class="col-md-6">

									<div class="form-group">
										
										<label for="user-email"><?php echo get_text('email_address'); ?></label>
										<input type="text" name="user_email" id="user-email" class="form-control" value="<?php if (!empty($_POST['user_email'])) echo sanitize($_POST['user_email'], 'string'); ?>">
										
									</div>
									
									<div class="form-group">
										
										<label for="user-status"><?php echo get_text('status'); ?></label>
										<select name="user_status" id="user-status" class="form-control">
											<option value="1"><?php echo get_text('active'); ?></option>
											<option value="0"><?php echo get_text('inactive'); ?></option>
										</select>
										
									</div>
									
									<div class="form-group">
										
										<label for="hide-online"><?php echo get_text('hide_online'); ?></label>
										<select name="hide_online" id="hide-online" class="form-control">
											<option value="1"><?php echo get_text('yes'); ?></option>
											<option value="0" selected><?php echo get_text('no'); ?></option>
										</select>
										
									</div>

									<div class="form-group">
										
										<label for="group-id"><?php echo get_text('group'); ?></label>
										<select name="group_id" id="group-id" class="form-control">
											<?php foreach ($groups['data'] as $value): ?>
												<option value="<?php echo $value['group_id']; ?>"><?php echo $value['group_name']; ?></option>
											<?php endforeach; ?>
										</select>
										
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

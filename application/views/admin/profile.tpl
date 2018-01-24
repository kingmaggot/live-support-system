<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<script type="text/javascript">
	$(document).on('change', '.btn-file :file', function() {

		label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
		$(this).parents('.input-group').find(':text').val(label);
		
	});
</script>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('profile'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading"><?php echo get_text('edit_profile'); ?></div>
					
					<div class="panel-body">

						<?php if (isset($success) && $success): ?>
						
							<p class="text-center">
								<span class="label label-success"><?php echo get_text('success_data_saved'); ?></span>
							</p>
							
						<?php endif; ?>
						
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
						
						<form method="post" enctype="multipart/form-data">
							
							<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
							
							<?php if ($operator['success']): ?>
							
								<?php foreach($operator['data'] as $value): ?>
									
									<input type="hidden" name="user_id" value="<?php echo $value['user_id']; ?>">
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<?php if (file_exists('uploads/images/' . md5($_SESSION['user_email']) . '.png')): ?>
													<img src="<?php echo $absolute_url; ?>uploads/images/<?php echo md5($_SESSION['user_email']) . '.png'; ?>" class="img-circle" alt="">
												<?php else: ?>
													<img src="<?php echo $absolute_url; ?>uploads/images/profile.png" class="img-circle" alt="">
												<?php endif; ?>
												<span class="help-block">&nbsp;</span>
												
											</div>
											
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

										</div>
										
										<div class="col-md-6">

											<div class="form-group">
												
												<label for="profile-image"><?php echo get_text('profile_image'); ?></label> 
												<?php if (file_exists('uploads/images/' . md5($_SESSION['user_email']) . '.png')): ?>
													<a href="<?php echo $absolute_url; ?>admin/profile/delete_image/">
														<span class="help-block label label-danger"><?php echo get_text('delete'); ?></span>
													</a>
												<?php endif; ?>
												<div class="input-group">
													
													<span class="input-group-btn">
														
														<span class="btn btn-default btn-file">
															<?php echo get_text('browse'); ?> <input type="file" name="profile_image" id="profile-image">
														</span>
														
													</span>
													
													<input type="text" class="form-control" disabled>

												</div>
												<span class="help-block"><?php echo get_text('error_image'); ?></span>
												
											</div>
											
											<div class="form-group">
												
												<label for="user-email"><?php echo get_text('email_address'); ?></label>
												<input type="text" name="user_email" id="user-email" class="form-control" value="<?php echo $value['user_email']; ?>">
												
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
												
												<label for="confirm-password"><?php echo get_text('confirm_password'); ?></label>
												<input type="password" name="confirm_password" id="confirm-password" class="form-control">
												
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

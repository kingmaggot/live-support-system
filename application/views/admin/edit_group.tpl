<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('groups'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading"><?php echo get_text('edit_group'); ?></div>
					
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
							
							<?php if ($group['success']): ?>
							
								<?php foreach($group['data'] as $value): ?>
									
									<input type="hidden" name="group_id" value="<?php echo $value['group_id']; ?>">
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="group-name"><?php echo get_text('group_name'); ?></label>
												<input type="text" name="group_name" id="group-name" class="form-control" value="<?php echo $value['group_name']; ?>">
												
											</div>

											<div class="form-group">

												<label for="group-permissions"><?php echo get_text('permissions'); ?></label>
												<select name="group_permissions[]" id="group-permissions" class="form-control" style="min-height: 200px;" multiple>
													<?php foreach ($pages as $key => $value2): ?>
														<?php if (in_array($key, $value['group_permissions'])): ?>
															<option value="<?php echo $key; ?>" selected><?php echo $value2; ?></option>
														<?php else: ?>
															<option value="<?php echo $key; ?>"><?php echo $value2; ?></option>
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

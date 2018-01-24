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
					
					<div class="panel-heading"><?php echo get_text('add_new_group'); ?></div>
					
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
										
										<label for="group-name"><?php echo get_text('group_name'); ?></label>
										<input type="text" name="group_name" id="group-name" class="form-control" value="<?php if (!empty($_POST['group_name'])) echo sanitize($_POST['group_name'], 'string'); ?>">
										
									</div>
									
									<div class="form-group">
										
										<label for="group-permissions"><?php echo get_text('permissions'); ?></label>
										<select name="group_permissions[]" id="group-permissions" class="form-control" style="min-height: 200px;" multiple>
											<?php foreach ($pages as $key => $value): ?>
												<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
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

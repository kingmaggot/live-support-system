<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#select-all').click(function() {
			
			if ($(this).is(':checked')) {
				
				$('input:checkbox').prop('checked', true);
				
			} else {
				
				$('input:checkbox').prop('checked', false);
				
			}

		});
		
	});
</script>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('canned_messages'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading">

						<form method="post">
							
							<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
							
							<div class="row">
								
								<div class="col-md-7"><?php echo get_text('messages'); ?></div>
								
								<div class="col-md-5">
									
									<div class="input-group input-group-sm">

										<input type="text" name="canned_message" class="form-control" placeholder="<?php echo get_text('insert_new_message'); ?>">
										<div class="input-group-btn">
											<button type="submit" class="btn btn-default">
												<span class="fa fa-plus"></span>
											</button>
										</div>
										
									</div>
									
								</div>
								
							</div>
							
						</form>
						
					</div>
					
					<div class="panel-body">
						
						<?php if (isset($success) && $success): ?>
						
							<p class="text-center">
								<span class="label label-success"><?php echo get_text('success_data_saved'); ?></span>
							</p>
							
						<?php endif; ?>
						
						<?php if ($canned_messages['success']): ?>
						
							<div class="table-responsive">
								
								<form method="post" action="<?php echo $absolute_url; ?>admin/canned_messages/delete">
									
									<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
									
									<table class="table">
										<thead>
											<tr>
												<th><input type="checkbox" id="select-all" value="cb"></th>
												<th><?php echo get_text('message'); ?></th>
												<th><?php echo get_text('edit'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($canned_messages['data'] as $value): ?>
												<tr>
													<td><input type="checkbox" name="cb[]" value="<?php echo $value['message_id']; ?>"></td>
													<td><?php echo $value['canned_message']; ?></td>
													<td>
														<a href="<?php echo $absolute_url; ?>admin/canned_messages/edit/<?php echo $value['message_id']; ?>">
															<span class="fa fa-pencil"></span>
														</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>

									<button type="submit" class="btn btn-danger"><?php echo get_text('delete'); ?></button>

								</form>
								
							</div>

							<ul class="pager">
								<?php if ($canned_messages['current_page'] > 1 && ($canned_messages['current_page'] - 1) < $canned_messages['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/canned_messages/page/<?php echo ($canned_messages['current_page'] - 1); ?>"><?php echo get_text('previous'); ?></a></li>
								<?php endif; ?>
								<?php if ($canned_messages['pages'] > $canned_messages['current_page'] && ($canned_messages['current_page'] - 1) < $canned_messages['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/canned_messages/page/<?php echo ($canned_messages['current_page'] + 1); ?>"><?php echo get_text('next'); ?></a></li>
								<?php endif; ?>
							</ul>
							
						<?php else: ?>
						
							<span class="label label-primary"><?php echo get_text('records_not_found'); ?></span>
							
						<?php endif; ?>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>

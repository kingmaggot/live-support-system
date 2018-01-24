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
			
			<h1><?php echo get_text('chat_history'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading">

						<form method="post">
							
							<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
							
							<div class="row">
								
								<div class="col-md-9"><?php echo get_text('chat_list'); ?></div>
								
								<div class="col-md-3">

									<div class="input-group input-group-sm">
										
										<input type="text" name="filter" class="form-control" placeholder="<?php echo get_text('visitor_email'); ?>">
										<div class="input-group-btn">
											<button type="submit" class="btn btn-default"><span class="fa fa-search"></span></button>
										</div>
										
									</div>
									
								</div>
								
							</div>
							
						</form>
						
					</div>
					
					<div class="panel-body">
						
						<?php if ($chats['success']): ?>
						
							<div class="table-responsive">
								
								<form method="post" action="<?php echo $absolute_url; ?>admin/chat_history/delete">
									
									<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
									
									<table class="table">
										<thead>
											<tr>
												<th><input type="checkbox" id="select-all" value="cb"></th>
												<th><?php echo get_text('name'); ?></th>
												<th><?php echo get_text('ip_address'); ?></th>
												<th><?php echo get_text('department'); ?></th>
												<th><?php echo get_text('time_in_chat'); ?></th>
												<th><?php echo get_text('date'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($chats['data'] as $value): ?>
												<tr>
													<td><input type="checkbox" name="cb[]" value="<?php echo $value['chat_id']; ?>"></td>
													<td>
														<a href="<?php echo $absolute_url; ?>admin/chat_history/view_chat/<?php echo $value['chat_id']; ?>">
															<?php echo $value['username']; ?>
														</a>
													</td>
													<td><?php echo $value['ip_address']; ?></td>
													<td><?php echo $value['department_name']; ?></td>
													<td><?php echo $value['elapsed_time']; ?></td>
													<td><?php echo $value['date']; ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>

									<button type="submit" class="btn btn-danger"><?php echo get_text('delete'); ?></button>
									
								</form>
								
							</div>

							<ul class="pager">
								<?php if ($chats['current_page'] > 1 && ($chats['current_page'] - 1) < $chats['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/chat_history/page/<?php echo ($chats['current_page'] - 1); ?>"><?php echo get_text('previous'); ?></a></li>
								<?php endif; ?>
								<?php if ($chats['pages'] > $chats['current_page'] && ($chats['current_page'] - 1) < $chats['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/chat_history/page/<?php echo ($chats['current_page'] + 1); ?>"><?php echo get_text('next'); ?></a></li>
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

<?php if ($pending_chats['success']): ?>

	<div class="row">

		<div class="col-md-12">

			<div class="table-responsive">
				
				<table class="table">
					<tbody>
						<?php foreach($pending_chats['data'] as $value): ?>
							<tr>
								<td>
									<?php if ($value['current_chat_id'] == $value['chat_id']): ?>
										<span class="label label-success"><?php echo $value['username']; ?></span>
									<?php else: ?>
										<span class="small"><?php echo $value['username']; ?></span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($value['chat_status'] == 0): ?>
										<span class="small"><?php echo get_text('in_queue'); ?></span>
									<?php elseif ($value['chat_status'] == 2): ?>
										<span class="small"><?php echo get_text('in_chat'); ?></span>
									<?php endif; ?>
								</td>
								<td>
									<span class="small"><?php echo $value['department_name']; ?></span>
								</td>
								<td>
									<?php if ($value['new_activity'] && $value['current_chat_id'] != $value['chat_id']): ?>
										<a href="#" class="start-chat" data-id="<?php echo $value['chat_id']; ?>" title="<?php echo get_text('reply'); ?>"><span class="fa fa-reply"></span></a>
									<?php else: ?>
										&nbsp;
									<?php endif; ?>
								</td>
								<td>
									<div class="btn-group btn-group-xs pull-right">
										<?php if ($value['chat_status'] == 0): ?>
											<button class="btn btn-default start-chat" data-id="<?php echo $value['chat_id']; ?>"><span class="fa fa-comments-o"></span></button>
											<button class="btn btn-default watch-chat" data-id="<?php echo $value['chat_id']; ?>"><span class="fa fa-eye"></span></button>
											<a href="blocked_visitors/add/<?php echo $value['ip_address']; ?>" class="btn btn-danger" target="_blank"><span class="fa fa-ban"></span></a>
										<?php elseif ($value['chat_status'] == 2 && $value['current_chat_id'] != $value['chat_id'] && !$value['chat_active']): ?>
											<button class="btn btn-default start-chat" data-id="<?php echo $value['chat_id']; ?>"><span class="fa fa-comments-o"></span></button>
											<a href="blocked_visitors/add/<?php echo $value['ip_address']; ?>" class="btn btn-danger" target="_blank"><span class="fa fa-ban"></span></a>
										<?php else: ?>
											<a href="blocked_visitors/add/<?php echo $value['ip_address']; ?>" class="btn btn-danger" target="_blank"><span class="fa fa-ban"></span></a>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				
			</div>
			
		</div>
		
	</div>
	
<?php else: ?>

	<span class="label label-default"><?php echo get_text('message_chat_list_empty'); ?></span>
	
<?php endif; ?>

<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('chat_panel'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-6">
				
				<div class="row">
					
					<div class="col-md-12">
						
						<div class="panel panel-default">
							
							<div class="panel-heading">
								
								<form id="form-1">

									<div class="btn-group">
										
										<button id="operator-status" class="btn btn-default btn-xs">
											<span id="operator-online" class="hidden"><span class="fa fa-circle online"></span><?php echo get_text('Online'); ?></span>
											<span id="operator-offline" class="hidden"><span class="fa fa-circle offline"></span><?php echo get_text('Offline'); ?></span>
										</button>
										
										<div class="btn-group">

											<button type="button" id="online-departments" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
												<?php echo get_text('transfer_chat'); ?> <span class="caret"></span>
											</button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li><a href="#">&nbsp;</a></li>
											</ul>
											
										</div>
										
										<button id="stop-chat" class="btn btn-default btn-xs"><?php echo get_text('close'); ?></button>
										
									</div>
										
									<div class="pull-right">
										
										<span id="loader-1" class="fa fa-spinner fa-spin fa-lg hidden"></span>

									</div>
									
								</form>
								
							</div>

							<div id="chat-content" class="panel-body">

								<ul id="chat-messages"></ul>

								<div id="user-typing" class="text-muted">
									
									<span class="label label-default">
										<span class="fa fa-pencil"></span>
										<?php echo get_text('message_user_typing'); ?>
									</span>
									
								</div>
								
							</div>
							
							<div class="panel-footer">
								
								<form id="form-2">
									
									<div class="form-group">
										
										<div class="input-group input-group-sm">
											
											<input type="text" name="message" id="message" class="form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('message')); ?>">

											<div class="input-group-btn">
												
												<button type="button" id="send-message" class="btn btn-default"><?php echo get_text('send'); ?></button>
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
												</button>
												<ul id="canned-messages-1" class="dropdown-menu dropdown-menu-right">
													<?php foreach($canned_messages['data'] as $value): ?>
														<li><a href="#"><?php echo $value['canned_message']; ?></a></li>
													<?php endforeach; ?>
												</ul>
												
											</div>
											
										</div>
										
									</div>
									
								</form>
								
							</div>
							
						</div>
					
					</div>
					
				</div>
				
				<div class="row">
					
					<div class="col-md-12">
						
						<div class="panel panel-default hidden">
							
							<div class="panel-heading"><?php echo get_text('visitor_details'); ?></div>
							
							<div class="panel-body">
								
								<div id="visitor-details"></div>
								
							</div>
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
			<div class="col-md-6">

				<div class="row">
					
					<div class="col-md-12">
						
						<div class="panel panel-default">
							
							<div class="panel-heading"><?php echo get_text('visitors'); ?></div>
							
							<div id="pending-chat" class="panel-body"></div>
							
						</div>
						
					</div>
					
				</div>

				<div class="row">
					
					<div class="col-md-12">
						
						<div class="panel panel-default">
							
							<div class="panel-heading">
								
								<?php echo get_text('online_visitors'); ?>
								<span id="message-success-1" class="label label-success pull-right hidden"><?php echo get_text('success_message_sent'); ?></span>
							
							</div>
							
							<div id="online-visitors" class="panel-body">

								<form id="form-3">
									
									<div class="form-group">
										
										<div class="input-group input-group-sm">
											
											<input type="text" name="invitation_message" id="invitation-message" class="form-control" data-msg-required="<?php printf(get_text('validator_required'), get_text('message')); ?>">
											<input type="hidden" name="ip_address" id="ip-address">
											
											<div class="input-group-btn">
												
												<button type="button" id="send-invitation" class="btn btn-default"><?php echo get_text('send'); ?></button>
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
												</button>
												<ul id="canned-messages-2" class="dropdown-menu dropdown-menu-right">
													<?php foreach($canned_messages['data'] as $value): ?>
														<li><a href="#"><?php echo $value['canned_message']; ?></a></li>
													<?php endforeach; ?>
												</ul>
												
											</div>
											
										</div>
										
									</div>
									
								</form>
								
								<div id="visitors"></div>
								
							</div>
							
						</div>
						
					</div>
					
				</div>

			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>

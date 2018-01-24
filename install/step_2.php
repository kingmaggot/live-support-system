<?php

session_start();

if (!$_SESSION['step_2']) {
	
	header('Location: step_1.php');
	
}

define('ABSOLUTE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
define('ABSOLUTE_URL', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), 'install'));

$errors = array();

function hash_password($password) {
	
	if (function_exists('openssl_random_pseudo_bytes')) {
		
		$salt = openssl_random_pseudo_bytes(16);
		
	} else {
		
		$salt = str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 5));
		
	}

	$salt = substr(str_replace('+', '.', base64_encode($salt)), 0, 22);

	return crypt($password, '$2y$12$' . $salt);
	
}
	
if (isset($_POST['submit'])) {
	
	$fields = array(
		'site_title' 		=> 'The site title field is required.',
		'first_name'		=> 'The first name field is required.',
		'user_password'		=> 'The password field is required.'
	);

	foreach ($fields as $key => $value) {

		if (empty($_POST[$key])) {

			array_push($errors, $value);
			
		}
		
	}

	if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {

		array_push($errors, 'The email address field is required.');

	}
	
	if (!empty($_POST['user_password']) && !strcmp($_POST['user_password'], $_POST['confirm_password']) == 0) {

		array_push($errors, 'The password field does not match the confirm password field.');

	}

	if (isset($_SESSION['upgrade']) && $_SESSION['upgrade'] && !isset($_POST['disclaimer'])) {

		array_push($errors, 'The disclaimer field is mandatory.');

	}

	try {

		$pdo = new PDO('mysql:host=' . $_SESSION['db_server'] . ';port=' . $_SESSION['db_port'] . ';dbname=' . $_SESSION['db_name'], $_SESSION['db_username'], $_SESSION['db_password']);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	} catch (PDOException $exception) {
		
		array_push($errors, $exception->getMessage());
	
	}
	
	if (isset($_SESSION['upgrade']) && $_SESSION['upgrade'] && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {

		$stmt = $pdo->prepare('SELECT user_email FROM ' . $_SESSION['table_prefix'] . 'users WHERE user_email = :user_email');
		
		$stmt->bindValue(':user_email', filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL));
		$stmt->execute();
		
		if ($stmt->rowCount() == 0) {
			
			array_push($errors, 'The email address does not exists.');
			
		}
		
	}

	if (empty($errors)) {

		$config_database = file_get_contents('../config/database.php');

		$replace = array(
			'__DB_SERVER__' 	=> $_SESSION['db_server'],
			'__DB_PORT__' 		=> $_SESSION['db_port'],
			'__DB_USERNAME__' 	=> $_SESSION['db_username'],
			'__DB_PASSWORD__' 	=> $_SESSION['db_password'],
			'__DB_NAME__' 		=> $_SESSION['db_name'],
			'__TABLE_PREFIX__' 	=> $_SESSION['table_prefix']
		);

		$output	= str_replace(array_keys($replace), $replace, $config_database);

		$file = fopen('../config/database.php', 'w');
		fwrite($file, $output);
		fclose($file);
		
		$sql = (isset($_SESSION['upgrade']) && $_SESSION['upgrade']) ? file_get_contents(ABSOLUTE_PATH . 'install/upgrade.sql') : file_get_contents(ABSOLUTE_PATH . 'install/database.sql');
		
		$replace = array(
			'__TABLE_PREFIX__' => $_SESSION['table_prefix']
		);
		
		$sql = str_replace(array_keys($replace), $replace, $sql);

		$pdo->exec($sql);
		
		if (isset($_SESSION['upgrade']) && $_SESSION['upgrade']) {

			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'options SET option_value = :option_value WHERE option_name = :option_name');
			
			$stmt->bindValue(':option_value', filter_var($_POST['site_title'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':option_name', 'site_title');
			$stmt->execute();

			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'options SET option_value = :option_value WHERE option_name = :option_name');
			
			$stmt->bindValue(':option_value', filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL));
			$stmt->bindValue(':option_name', 'admin_email');
			$stmt->execute();

			$stmt = $pdo->prepare('SELECT user_id FROM ' . $_SESSION['table_prefix'] . 'users WHERE user_email = :user_email');
			
			$stmt->bindValue(':user_email', filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL));
			$stmt->execute();
			$user_id = $stmt->fetchColumn();

			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'users SET user_password = :user_password WHERE user_id = :user_id');
			
			$stmt->bindValue(':user_password', hash_password(filter_var($_POST['user_password'], FILTER_SANITIZE_STRING)));
			$stmt->bindValue(':user_id', $user_id);
			$stmt->execute();

			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'user_profiles SET first_name = :first_name, last_name = :last_name WHERE user_id = :user_id');
			
			$stmt->bindValue(':first_name', filter_var($_POST['first_name'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':last_name', filter_var($_POST['last_name'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':user_id', $user_id);
			$stmt->execute();

			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'operators SET first_name = :first_name, last_name = :last_name WHERE user_id = :user_id');
			
			$stmt->bindValue(':first_name', filter_var($_POST['first_name'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':last_name', filter_var($_POST['last_name'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':user_id', $user_id);
			$stmt->execute();

			$stmt = $pdo->prepare('INSERT INTO ' . $_SESSION['table_prefix'] . 'user_groups SET group_id = :group_id, group_name = :group_name, group_permissions = :group_permissions');
			
			$stmt->bindValue(':group_id', 1);
			$stmt->bindValue(':group_name', 'Administrator');
			$stmt->bindValue(':group_permissions', serialize(array('index', 'chat_history', 'canned_messages', 'departments', 'operators', 'groups', 'blocked_visitors', 'access_logs', 'settings', 'profile')));
			$stmt->execute();
	
			$stmt = $pdo->prepare('INSERT INTO ' . $_SESSION['table_prefix'] . 'user_groups SET group_id = :group_id, group_name = :group_name, group_permissions = :group_permissions');
			
			$stmt->bindValue(':group_id', 2);
			$stmt->bindValue(':group_name', 'Operator');
			$stmt->bindValue(':group_permissions', serialize(array('index', 'chat_history', 'canned_messages', 'profile')));
			$stmt->execute();

		} else {
			
			$stmt = $pdo->prepare('INSERT INTO ' . $_SESSION['table_prefix'] . 'users SET user_id = :user_id, group_id = :group_id, user_email = :user_email, user_password = :user_password, user_status = :user_status, user_approved = :user_approved, user_created = :user_created');

			$stmt->bindValue(':user_id', 1);
			$stmt->bindValue(':group_id', 1);
			$stmt->bindValue(':user_email', filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL));
			$stmt->bindValue(':user_password', hash_password(filter_var($_POST['user_password'], FILTER_SANITIZE_STRING)));
			$stmt->bindValue(':user_status', 1);
			$stmt->bindValue(':user_approved', 1);
			$stmt->bindValue(':user_created', time());
			$stmt->execute();

			$stmt = $pdo->prepare('INSERT INTO ' . $_SESSION['table_prefix'] . 'user_profiles SET profile_id = :profile_id, user_id = :user_id, first_name = :first_name, last_name = :last_name');
			
			$stmt->bindValue(':profile_id', 1);
			$stmt->bindValue(':user_id', 1);
			$stmt->bindValue(':first_name', filter_var($_POST['first_name'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':last_name', filter_var($_POST['last_name'], FILTER_SANITIZE_STRING));
			$stmt->execute();
			
			$stmt = $pdo->prepare('INSERT INTO ' . $_SESSION['table_prefix'] . 'operators SET operator_id = :operator_id, user_id = :user_id, first_name = :first_name, last_name = :last_name, last_activity = :last_activity, hide_online = :hide_online');
			
			$stmt->bindValue(':operator_id', 1);
			$stmt->bindValue(':user_id', 1);
			$stmt->bindValue(':first_name', filter_var($_POST['first_name'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':last_name', filter_var($_POST['last_name'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':last_activity', 0);
			$stmt->bindValue(':hide_online', 0);
			$stmt->execute();
	
			$stmt = $pdo->prepare('INSERT INTO ' . $_SESSION['table_prefix'] . 'user_groups SET group_id = :group_id, group_name = :group_name, group_permissions = :group_permissions');
			
			$stmt->bindValue(':group_id', 1);
			$stmt->bindValue(':group_name', 'Administrator');
			$stmt->bindValue(':group_permissions', serialize(array('index', 'chat_history', 'canned_messages', 'departments', 'operators', 'groups', 'blocked_visitors', 'access_logs', 'settings', 'profile')));
			$stmt->execute();
	
			$stmt = $pdo->prepare('INSERT INTO ' . $_SESSION['table_prefix'] . 'user_groups SET group_id = :group_id, group_name = :group_name, group_permissions = :group_permissions');
			
			$stmt->bindValue(':group_id', 2);
			$stmt->bindValue(':group_name', 'Operator');
			$stmt->bindValue(':group_permissions', serialize(array('index', 'chat_history', 'canned_messages', 'profile')));
			$stmt->execute();
			
			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'options SET option_value = :option_value WHERE option_name = :option_name');
			
			$stmt->bindValue(':option_value', filter_var($_POST['site_title'], FILTER_SANITIZE_STRING));
			$stmt->bindValue(':option_name', 'site_title');
			$stmt->execute();

			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'options SET option_value = :option_value WHERE option_name = :option_name');
			
			$stmt->bindValue(':option_value', ABSOLUTE_URL);
			$stmt->bindValue(':option_name', 'absolute_url');
			$stmt->execute();
			
			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'options SET option_value = :option_value WHERE option_name = :option_name');
			
			$stmt->bindValue(':option_value', ABSOLUTE_PATH);
			$stmt->bindValue(':option_name', 'absolute_path');
			$stmt->execute();
			
			$stmt = $pdo->prepare('UPDATE ' . $_SESSION['table_prefix'] . 'options SET option_value = :option_value WHERE option_name = :option_name');
			
			$stmt->bindValue(':option_value', filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL));
			$stmt->bindValue(':option_name', 'admin_email');
			$stmt->execute();
		
		}
		
		$_SESSION['step_3'] = true;
		
		header('Location: step_3.php');

	}
	
}

?>
<!DOCTYPE html>
<html>
	<head>
		
		<meta charset="UTF-8">
		
		<title>GokmenDC Kurulum Ekranı</title>
		
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		
		<style>
			body {
				background-color: #F4F4F4;
				margin-top: 35px;
			}

			a:hover {
				text-decoration: none;
			}

			#steps ul li{
				width: 25%;
			}
			
			#steps ul li {
				background: #EEEEEE;
				color: #AAAAAA;
				padding: 8px;
				border-radius: 5px;
			}
			
			#steps ul li.active {
				background: #337AB7;
				color: #FFFFFF;
			}
			
			.alert {
				background: #FFFFFF;
			}

			.alert-danger {
				border-left: 5px solid #A94442;
			}

			.alert-warning {
				border-left: 5px solid #8A6D3B;
			}
		</style>
		
	</head>
	<body>
		
		<div class="container">

			<div class="row">
				
				<div class="col-md-12">
					
					<div class="panel panel-default">

						<div class="panel-body">
							
							<div id="steps" class="text-center">
								
								<ul class="list-inline">
									<li>1. Veritabanı</li>
									<li class="active">2. Ayarlar</li>
									<li>3. Son</li>
								</ul>
								
							</div>

						</div>
						
					</div>
					
				</div>
				
			</div>

			<div class="row">
				
				<div class="col-md-12">
					
					<div class="panel panel-default">
						
						<div class="panel-body">
							
							<p>Aşağıda, yönetici bilgilerinizi girmeniz ve bazı ayarları tanımlamanız gerekir.</p>
							
							<hr>
							
							<?php if (count($errors) > 0): ?>
							
								<div class="alert alert-danger">
									
									<strong>Hata Sonuçları:</strong>
									
									<ul>
										<?php foreach ($errors as $value): ?>
											<li><?php echo $value; ?></li>
										<?php endforeach; ?>
									</ul>
									
								</div>
								
							<?php endif; ?>

							<form method="post">
								
								<div class="row">
									
									<div class="col-md-6">
										
										<div class="form-group">
											
											<label for="site-title">Site başlığı</label>
											<input type="text" name="site_title" id="site-title" class="form-control">
											<span class="help-block">Site başlığınız (title).</span>
											
										</div>
										
										<div class="form-group">
											
											<label for="first-name">Ad</label>
											<input type="text" name="first_name" id="first-name" class="form-control">
											<span class="help-block">Adınız.</span>
											
										</div>
										
										<div class="form-group">
											
											<label for="last-name">Soyad</label>
											<input type="text" name="last_name" id="last-name" class="form-control">
											<span class="help-block">Soyadınız.</span>
											
										</div>
										
									</div>
									
									<div class="col-md-6">

										<div class="form-group">
											
											<label for="user-email">Email Adresi</label>
											<input type="text" name="user_email" id="user-email" class="form-control">
											<span class="help-block">Kontrol paneli mail adresi.</span>
											
										</div>

										<div class="form-group">
											
											<label for="user-password">Şifre</label>
											<input type="password" name="user_password" id="user-password" class="form-control">
											<span class="help-block">Kontrol paneli şifresi.</span>
										
										</div>
										
										<div class="form-group">
											
											<label for="confirm-password">Şifre Tekrar</label>
											<input type="password" name="confirm_password" id="confirm-password" class="form-control">
											<span class="help-block">Kontrol paneli şifresi tekrar.</span>
											
										</div>
										
									</div>
									
								</div>
								
								<?php if (isset($_SESSION['upgrade']) && $_SESSION['upgrade']): ?>
								
									<div class="row">
										
										<div class="col-md-12">
											
											<div class="alert alert-warning">
												Devam etmeden önce manuel yedekleme yapılması şiddetle önerilir, lütfen mevcut uygulama dosyalarınızı ve veritabanınızı yedekleyin.
											</div>
											
											<div class="checkbox">
												<label>
													<input type="checkbox" name="disclaimer" value="1"> Bu yükseltme ile ilgili herhangi bir veri kaybı veya hasarın sorumluluğunu üstleniyorum.
												</label>
											</div>
											
										</div>
										
									</div>
									
									<button type="submit" name="submit" class="btn btn-primary">Yükselt</button>
									
								<?php else: ?>
								
									<button type="submit" name="submit" class="btn btn-primary">Kur</button>
									
								<?php endif; ?>
								
							</form>
							
						</div>
						
					</div>
					
				</div>
				
			</div>

			<div class="row">
				
				<div class="col-md-12 text-right">
					
					<p class="small"><a href="https://gokmendc.com" target="_blank">GokmenDC</a></p>

				</div>
				
			</div>

		</div>
		
	</body>
</html>

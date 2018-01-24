<?php

session_start();

if (!$_SESSION['step_1']) {
	
	header('Location: index.php');
	
}

$errors = array();

if (isset($_POST['submit'])) {
	
	$fields = array(
		'db_server' 	=> 'The server field is required.',
		'db_username' 	=> 'The username field is required.',
		'db_name' 		=> 'The database name field is required.'
	);

	if (!filter_var($_POST['db_port'], FILTER_VALIDATE_INT)) {

		array_push($errors, 'The port field is required.');

	}

	foreach ($fields as $key => $value) {

		if (empty($_POST[$key])) {

			array_push($errors, $value);
			
		}
		
	}

	if (empty($errors)) {
		
		$db_server = filter_var($_POST['db_server'], FILTER_SANITIZE_STRING);
		$db_port = filter_var($_POST['db_port'], FILTER_SANITIZE_NUMBER_INT);
		$db_username = filter_var($_POST['db_username'], FILTER_SANITIZE_STRING);
		$db_password = filter_var($_POST['db_password'], FILTER_SANITIZE_STRING);
		$db_name = filter_var($_POST['db_name'], FILTER_SANITIZE_STRING);
		$table_prefix = filter_var($_POST['table_prefix'], FILTER_SANITIZE_STRING);
		
		try {

			$pdo = new PDO('mysql:host=' . $db_server . ';port=' . $db_port . ';dbname=' . $db_name, $db_username, $db_password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			if (version_compare($pdo->getAttribute(constant('PDO::ATTR_SERVER_VERSION')), '5.0.15', '<=')) {
				
				array_push($errors, 'The database version ' . $pdo->getAttribute(constant('PDO::ATTR_SERVER_VERSION')) . ' is less than the minimum required version 5.0.15');
				
			} else {
				
				$_SESSION['db_server'] = $db_server;
				$_SESSION['db_port'] = $db_port;
				$_SESSION['db_username'] = $db_username;
				$_SESSION['db_password'] = $db_password;				
				$_SESSION['db_name'] = $db_name;
				$_SESSION['table_prefix'] = $table_prefix;
				$_SESSION['step_2'] = true;
				
				header('Location: step_2.php');
			
			}
			
		} catch (PDOException $exception) {
			
			array_push($errors, $exception->getMessage());
			
		}
		
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
				margin-top: 20px;
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
									<li class="active">1. Veritabanı</li>
									<li>2. Ayarlar</li>
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

							<p>Veritabanı tüm verilerinizi saklamak için kullanılır, lütfen aşağıdaki alanları veritabanınızın parametreleriyle doldurun. Bu bilgiye sahip değilseniz, barındırma desteğiyle iletişim kurmanız gerekir.</p>
							
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
											
											<label for="db-server">Sunucu</label>
											<input type="text" name="db_server" id="db-server" class="form-control" value="localhost">
											<span class="help-block">Sunucu ip adresi.</span>
											
										</div>
										
										<div class="form-group">
											
											<label for="db-username">Kullanıcı</label>
											<input type="text" name="db_username" id="db-username" class="form-control">
											<span class="help-block">Veritabanı kullanıcısı.</span>
											
										</div>
										
										<div class="form-group">
											
											<label for="db-password">Şifre</label>
											<input type="password" name="db_password" id="db-password" class="form-control">
											<span class="help-block">Veritabanı şifresi.</span>
											
										</div>
										
									</div>
									
									<div class="col-md-6">

										<div class="form-group">
											
											<label for="db-port">Port</label>
											<input type="text" name="db_port" id="db-port" class="form-control" value="3306">
											<span class="help-block">Standart veritabanı portu 3306.</span>
											
										</div>

										<div class="form-group">
											
											<label for="db-name">Veritabanı adı</label>
											<input type="text" name="db_name" id="db-name" class="form-control">
											<span class="help-block">Veritabanı adı.</span>
											
										</div>

										<div class="form-group">
											
											<label for="table-prefix">Tablo öneki</label>
											<input type="text" name="table_prefix" id="table-prefix" class="form-control" value="gokmendc_">
											<span class="help-block">Tablo öneki (birden fazla kurulumda gerekebilir).</span>
											
										</div>
										
									</div>
									
								</div>

								<button type="submit" name="submit" class="btn btn-primary">İleri</button>
								
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

<!DOCTYPE html>
<html>
	<head>
		
		<meta charset="UTF-8">
		
		<title>Oops!</title>

		<style type="text/css">
			
			body {
				font: 13px Arial, Helvetica, sans-serif;
				margin: 40px;
				color: #000000;
			}
			
			h1 {
				font-size: 19px;
				border-bottom: 1px solid #D0D0D0;
				padding: 10px;
			}
			
			#container {
				border: 1px solid #CCCCCC;
			}
			
			#content {
				padding: 10px;
			}
			
		</style>
		
	</head>
	<body>
		
		<div id="container">
			
			<h1>Oops!</h1>
			
			<div id="content">
				
				<p>Message: <?php echo $message; ?></p>
				<p>Code: <?php echo $code; ?></p>
				<p>File: <?php echo $file; ?></p>
				<p>Line: <?php echo $line; ?></p>

			</div>

		</div>
		
	</body>
</html>

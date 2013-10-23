<!DOCTYPE html>
<html>
<head>
	<header>Where runners come together</header>
	<title><?php if(isset($title)) echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
					
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	

	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>
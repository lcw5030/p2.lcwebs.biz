<!DOCTYPE html>
<html>
<div id="wrapper">
<head>
	<header>Where runners come together</header>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<!-- JS/CSS File we want on every page -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> 

	<!-- Controller Specific JS/CSS -->
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	
	<nav>
                <menu>
                        <div id="navigation">
                        <?php if($user): ?>
                                <li><a href='/users/profile'>View Profile</a></li>
                                <li><a href='/posts/add'>Add Post</a></li>
                                <li><a href='/posts/'>View Posts</a></li>
                                <li><a href='/posts/users'>Follow users</a></li>
                                <li><a href='/users/logout'>Logout</a></li>
                        <?php else: ?>
                                <li><a href='/users/signup'>Sign up</a></li>
                                <li><a href='/users/login'>Log in</a></li>
                        <?php endif; ?>
                        </div>
                </menu>
    </nav>

	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
<footer>
        RunSpot is an application for Harvard Extension CSCI E-15 built by Lisa Weber</br>
        Photo credit belongs to http://parrotheadjeff.com/ 
</footer>
</div>
</html>
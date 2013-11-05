<div id="mainContent">
	<h2>Log in</h2>


	<form method='POST' action='/users/p_login'>

	        Email: <input type='text' name='email'><br>
	        Password: <input type='password' name='password'><br><br>
	        
	        <?php if(isset($error)): ?>
		        <div class='error'>
		            Login failed. Please double check your email and password.
		        </div>
	        <br>
	    	<?php endif; ?>

	        <input type='Submit' value='Log in'>        

	</form>
</div>
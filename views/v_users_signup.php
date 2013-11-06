<div id="mainContent">
    <form method='POST' action='/users/p_signup'>

        First Name<br>
        <input type='text' name='first_name'>
        <br><br>

        Last Name<br>
        <input type='text' name='last_name'>
        <br><br>

        Email<br>
        <input type='text' name='email'>
        <br><br>

        Password<br>
        <input type='password' name='password'>
        <br><br>

        <?php if(isset($error) && $error == 'error'): ?>
            <div class='error'>
                Signup Failed. All fields are required.
            </div>
            

        <?php endif; ?>

        <?php if(isset($error) && $error == 'exists'): ?>
            <div class='error'>
                There is already an account associated with this email. 
                <a href="/users/login">Login</a>
            </div>
            

        <?php endif; ?>

        <input type='submit' value='Sign up'>
    </form>
</div>

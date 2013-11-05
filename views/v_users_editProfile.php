<div id="mainContent">
<?php if($user): ?>
        <h2>Edit <?=$user["first_name"]?>'s profile...</h2>
        <br/>
<?php else: ?>
<?php         Router::redirect("/users/login"); ?>
<?php endif; ?>
        <form id="myForm" method='POST' action='/users/p_editProfile/<?=$user["user_id"]?>'>
                <label>First Name</label><br>
                <input type='text' name='first_name' value='<?=$user["first_name"];?>' required autofocus>
                <br><br>

                <label>Last Name</label><br>
                <input type='text' name='last_name' value='<?=$user["last_name"];?>' required>
                <br><br>

                <label>Email</label><br>
                <input type='text' name='email' value='<?=$user["email"]; ?>' required>
                <br><br>

                <label>About Me</label><br>
                <input type='text' name='bio' value='<?=$user["bio"]; ?>' required>
                <br><br>

                <!-- we don't pre-fill the password because we want them to enter one and we force them to do so-->
                <label>Re-enter or Update your Password</label><br>
                <input id="password" type='password' name='password' required>
                <br><br>
                <label>Confirm password</label><br>
                        <input id="confirm_password" name="confirm_password" type="password" required>
                        
                        <br/><br/>
                        <?php if(isset($error)): ?>
                        <div class='error'>
                                <?php echo $error; ?>
                        Update failed.
                        </div>
                        <br>
                        <?php endif; ?>

                <input class="buttons" type='submit' value='Update Profile'>

        </form>
</div>

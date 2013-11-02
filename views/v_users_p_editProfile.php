<?php if(!$user): ?>
        <?php Router::redirect("/users/login"); ?>
<?php else: ?>
        <h2>This is <?=$user->first_name?>'s profile...</h2>
<?php endif; ?>
<br/>

<?php if($user) echo $user->first_name;?>
<?php echo ' '; ?>
<?php if($user) echo $user->last_name; ?>
<?php echo '<br/>'; ?>
<?php if($user) echo 'email: '.$user->email; ?>
<?php echo '<br/>'; ?>
<?php if($user)
        $convert_time = $user->created;
        echo 'Member since: ';
        echo date('M d Y', $convert_time);
?>
<br/>
        <h3>
                <a href='/users/editProfile' >Edit my profile</a>
        </h3>
<hr/>
<br/>

<?php if(isset($error)): ?>
<div class='error'>
<?php echo $error; ?>
        Update failed.
</div>
<br>
<?php endif; ?>
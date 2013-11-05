<div id="mainContent">
<form method ='POST' action ='/posts/p_edit/<?=$post['post_id']; ?>'>

Edit your post
        <br>
        <!-- the next line has to have the PHP right after the "require>" or I get spaces at left in edit post -->
        <textarea id='myPost' cols="52" rows="1" type='text' name='content' maxlength="72" required><?=$post['content'] = trim($post['content'], " \t\n\r" )?>
        </textarea>
<br>
<input class="buttons" type='submit' value='Update Post'>
<h3>Maximum post length is 72 characters</h3>
</form>
</div>
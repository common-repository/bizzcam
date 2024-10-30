<?php

if($_POST['id'])
{
    global $wpdb;
    $table = $wpdb->prefix."bizzcam";

    $wpdb->delete( $table, array( 'ID' => $_POST['id'] )) or die('not able to delete');
    echo $table;
}

else{
    $_GET['id'];

    $up=show_value($_GET['id']);

    ?>

    <div id="embed_form">
        <form action="admin.php?page=my-first" method="post">

            <label>Title:</label>
            <div id="titlediv">


                <input style="width:30%;" type="text" autocomplete="off" id="title" value="<?php echo $up['0']->title;?>"  name="title" >
            </div>


            <input name="user_id" id="id" type="hidden" value="<?php echo $up['0']->user_id; ?>" />
            <input name="user_name" id="u_name" type="hidden" value="<?php echo $up['0']->user_name; ?>" />
            <input name="code_id" id="code_id" type="hidden" value="<?php echo $_GET['id']; ?>" />



            <label>Paste the Embeded code here: </label>
            <div class="wp-editor-container" id="wp-content-editor-container">
                <textarea cols="110" rows="1" class="wp-editor-area" name="code" id="content"   required><?php echo $up['0']->code;?></textarea>
            </div>
            <p style="color:#949193;"><?php echo 'Paste video code in this format : ';print htmlentities('<iframe width="360" height="203" src="//bizzcam.com/core/embed.aspx?uid=1007" frameborder="0" scrolling="no" allowfullscreen></iframe>.'); ?></p>
            <div class="submit_div">
                <input type="submit" name="submit_em" id="" value="Update">

            </div>
        </form>

    </div>
<?php
}
?>
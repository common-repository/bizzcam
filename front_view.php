<?php
if ($_POST['eid']) {
    $code = $_POST['eid'];
    global $wpdb;
    $title = $_POST['c_p_title'];
    $code_id = $_POST['c_p_id'];
    $table = $wpdb->prefix."bizzcam";
    $wpdb->update(
        $table,
        array(
            'title' => $title,
            'code' => $code
        ),
        array('id' => $code_id)
    );
}
/*front view*/
function front_view_func()
{
    error_reporting(1);
    if (is_user_logged_in())
    {
        global $wpdb;
        $table = "wp_bizzcam";

        global $user_c_id, $current_user, $po_id;
        get_currentuserinfo();
        $user_c_id = $current_user->ID;
        $user_c_name = $current_user->user_login;
        $check_user = $wpdb->get_results("select * from $table where `user_id`=$user_c_id order by id desc limit 0,1");
        //echo '<pre>';print_r($check_user);
        $po_id = $check_user['0']->id;


        if (!empty($check_user))
        {

            // $fh = fopen("http://web1.kindlebit.com/clientdemo/bizzCam/wp-admin/admin.php?page=bizzCam-text", "w") or die('lp');
            echo '<div id="after_suc_view" style="display: block">';
            $iframe_title = $check_user['0']->title;
            echo $iframe_code = $check_user['0']->code;
            echo '</div>';
            ?>
            <p class="form-submit">
                <input type="button" value="Edit" id="edit_code" name="edit_code">
            </p>
            <p class="form-submit" style='margin : -68px 0 0 110px;'>
                <input type="button" value="Download" id="download_code" name="edit_code">
                <!--<a href='http://bizzcam.com/embed?uid=l8ubZB5l5bEXKsPs5PKCyA%3d%3d'>download</a>-->
            </p>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#edit_code').click(function () {
                        jQuery('#after_suc_view').css("display", "none");
                        jQuery('#edit_code').css("display", "none");
                        jQuery('#download_code').css("display", "none");
                        jQuery('#edit_form').css("display", "block");

                    });
                    jQuery('#download_code').click(function () {

                        loadPopupBox();
                    });
                    jQuery('#close_pop').click(function () {

                        unloadPopupBox();
                    });


                    function unloadPopupBox() {    // TO Unload the Popupbox
                        jQuery('#popup_box').fadeOut("slow");
                        jQuery("#container").css({ // this is just for style
                            "opacity": "1"
                        });
                    }

                    function loadPopupBox() {    // To Load the Popupbox
                        jQuery('#popup_box').fadeIn("slow");
                        jQuery("#container").css({ // this is just for style
                            "opacity": "0.3"
                        });
                    }

                    jQuery('#edit_view').click(function () {
                        var tp = jQuery('textarea#comment_edi').val();
                        var cy_id = jQuery('#id').val();
                        var cy_name = jQuery('#u_name').val();
                        var cy_po_id = jQuery('#po_id').val();
                        var cy_po_title = jQuery('#title_nae').val();

                        jQuery.ajax({
                            type: 'POST',
                            url: '',
                            data: {

                                'eid': tp, 'c_id': cy_id, 'c_name': cy_name, 'c_p_id': cy_po_id, 'c_p_title': cy_po_title,
                            },
                            success: function (data) {
                                window.location.reload(true);
                            }
                        });
                    });
                });
            </script>
            <div id="popup_box">
            <p style="color:#FFFFFF;">Copy the video code:</p>

                                               <p id= 'close_pop' style="color:#FF0000; font-weight:bold; font-size:40px; position: relative; right: -26px; top: -92px; float:right;">X</p>
<textarea aria-required="true" style="margin: 15px 0 0 110px;" rows="5" cols="45" name="code" id="po_comment" required placeholder='Paste your code Here...' ><?php echo $iframe_code; ?></textarea>
</div>
<?php
if ($_POST['edit_view']) {


    $iframe = edit($_POST);

    echo '<div style="background-color:#FFD700;padding-left:10px">';
    echo 'Submitted Successfully';
    echo '</div>';
    echo '<div style="border: 6px solid; text-align: center; margin-bottom: 35px;">';
    echo $iframe;
    echo '</div>';
    echo'<script type="text/javascript">
    jQuery(document).ready(function (){
    jQuery("#embed_form").css("display", "none");
    jQuery("#after_suc_view").css("display", "none");
    jQuery("#edit_code").css("display", "none");
    })
    </script>';
}
?>
<div id='edit_form' style='display: none;'>
<form style='margin-top:80px;' class="comment-form" name='dynamic' method="post" action="">
<p class="comment-form-author"><label for="author">Title<span class="required">*</span></label> <input id='title_nae' type="text" aria-required="true" size="30" value="<?php echo $iframe_title; ?>" name="title" id="author" required  />

<label for="comment">Paste your code<span class="required">*</span></label>
                                                                     <textarea aria-required="true" rows="5" cols="40" name="code" id="comment_edi" required placeholder='Paste your code Here...' ><?php echo $iframe_code; ?></textarea>
</p><p id='opo' style='display:none;'><?php echo $iframe_code; ?></p>
                                                                   <p style="color:#949193;"><?php echo 'Paste video code in this format : ';print htmlentities('<iframe width="360" height="203" src="//bizzcam.com/core/embed.aspx?uid=1007" frameborder="0" scrolling="no" allowfullscreen></iframe>.'); ?></p>
<input name="user_id" id="id" type="hidden" value="<?php echo $user_c_id; ?>" />
<input name="user_name" id="u_name" type="hidden" value="<?php echo $user_c_name; ?>" />
<!--<input name="cod_name" id="cod_name" type="hidden" value="<?php /*echo $iframe_code;*/ ?>" />-->
<input name="po_id" id="po_id" type="hidden" value="<?php echo $po_id; ?>" />

<p class="form-submit">
<input type="button" value="Update" id="edit_view" name="edit_view">
                                                        </p>
                                                        </form>
                                                        </div>
    <?php
 }
 else {
     if ($_POST['Submit_view']) {
         $iframe = add($_POST);
         echo '<div style="background-color:#FFD700;padding-left:10px">';
         echo 'Submitted Successfully';
         echo '</div>';
         echo '<div style="border: 6px solid; text-align: center; margin-bottom: 35px;">';
         echo $iframe;
         echo '</div>';
         echo'<script type="text/javascript">
    jQuery(document).ready(function (){
    jQuery("#edit_form").css("display", "none");
    })
    </script>';
     }


     ?>
     <div id="embed_form" style="display:block;">
     <form style='margin-top:80px;' class="comment-form" name='dynamic' method="post" action="">
     <p class="comment-form-author"><label for="author">Title<span class="required">*</span></label> <input type="text" aria-required="true" size="30" value="" name="title" id="author" required>
                                                                                                                                                                                         </p>


                                                                                                                                                                                         <p class="comment-form-comment">
     <label for="comment">Paste your code<span class="required">*</span></label> <textarea aria-required="true" rows="8" cols="45" name="code" id="comment" placeholder='Paste your code Here...'  required></textarea>
                                                                                                                                                                                                   </p>
                                                                                                                                                                                                   <p style="color:#949193;"><?php echo 'Paste video code in this format : ';print htmlentities('<iframe width="360" height="203" src="//bizzcam.com/core/embed.aspx?uid=1007" frameborder="0" scrolling="no" allowfullscreen></iframe>.'); ?></p>
     <input name="user_id" id="id" type="hidden" value="<?php echo $user_c_id; ?>" />
     <input name="user_name" id="u_name" type="hidden" value="<?php echo $user_c_name; ?>" />

     <p class="form-submit">
     <input type="submit" value="Save" id="submit" name="Submit_view">
     </p>
     </form>
     </div>
<?php
    }
    }
    else {
        ?>
        Please <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">Login</a> First.
    <?php
    }
}

add_shortcode( 'front_view', 'front_view_func' );
function add($post)
{
    global $wpdb;
    $table = $wpdb->prefix."bizzcam";
    $user_c_id = $post['user_id'];
    $title=$post['title'];
    $code=$post['code'];
    $user_c_name= $post['user_name'];
    $wpdb->query("INSERT INTO ".$table." (`title` ,`code` ,`user_id`,`user_name`)VALUES ('".$title."', '".$code."', '".$user_c_id."', '".$user_c_name."')") or die('not inserted');
    $real=$wpdb->get_results("select * from $table where `user_id`=$user_c_id order by `id` DESC");


    $show_code=$real['0']->code;
    return($show_code);
}

function edit($post)
{
    echo '<pre>';print_r($post); die('sfsdsfsdsdffssf');
    echo $post['cod_name']; die;

    global $wpdb;
    $table = $wpdb->prefix."bizzcam";
    $user_c_id = $post['user_id'];
    $code_id=$post['po_id'];
    //$up_co=show_value($code_id);
    $title=$post['title'];
    $code=$post['code'];
    $in_code=htmlentities($code);
    $user_c_name= $post['user_name'];

    $wpdb->update(
        $table,
        array(
            'title' => $title,
            'code' => "$code"	// integer (number)
        ),
        array( 'id' => $code_id )

    );
    /*  echo "select * from $table where `user_id`=$user_c_id order by `id` DESC";
      die();*/

    $real=$wpdb->get_results("select * from $table where `user_id`=$user_c_id order by `id` DESC");


    $show_code=$code;

    //print_r($show_code);
    return($show_code);

}

function show_value($post)
{
    global $wpdb;

    $table = $wpdb->prefix."bizzcam";
    $real=$wpdb->get_results("select * from $table where `id`=$post");
    return($real);
}

function update_bk($post)
{

    global $wpdb;
    $table = $wpdb->prefix."bizzcam";
    $code_id=$post['code_id'];
    $user_c_id = $post['user_id'];
    $title=$post['title'];
    $code=$post['code'];
    $in_code=htmlentities($code);
    $user_c_name= $post['user_name'];


    $wpdb->update(
        $table,
        array(
            'title' => $title,
            'code' => "$code"
        ),
        array( 'id' => $code_id )

    );
    $real=$wpdb->get_results("select * from $table where `user_id`=$user_c_id order by `id` DESC");


    $show_code=$code;

    return($show_code);

}
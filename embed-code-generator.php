<?php
/*
Plugin Name: bizzCam Video
Plugin URI: http://www.bizzcam.com
Description:  Allows bizzCam customers to easily embed the bizzCam code and send short bizzClip video commercials to their website.
Version: 0.1
Author: bizzCam LLC

*/

/*  2014  bizzCam LLC  (email : brian@bizzcam.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


register_activation_hook(__FILE__, 'my_first_plugin_install');

function my_first_plugin_install()
{

    global $wpdb;
    $table = $wpdb->prefix . "bizzcam";
    $structure = "CREATE TABLE IF NOT EXISTS " . $table . " (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `code` varchar(1000) NOT NULL,
        `user_id` int(11) NOT NULL,
        `user_name` varchar(1000) NOT NULL,
    PRIMARY KEY (`id`)
    ) ;";

    $wpdb->query($structure);

}

add_action('admin_menu', 'my_video_admin_menu');
add_action('wp_enqueue_scripts', 'bizz_enquee');

function bizz_enquee(){
    wp_enqueue_style('bizz_css', plugins_url('/embed-style.css', __FILE__));
}

function my_video_admin_menu()
{

    add_submenu_page('my-first', 'bizzCam Edit', '', 'read', 'bizzCam-edit', 'bizzCam_Edit');
    add_submenu_page('my-first', 'bizzCam text', '', 'read', 'bizzCam-text', 'bizzCam_text');
    add_options_page('Plugin Admin Options', 'bizzCam', 'read', 'my-first', 'plugin_admin_options_page');

}

include('front_view.php');

if ($_POST['submit_em']) {

    update_bk($_POST);

}

function bizzCam_Edit()
{
    include('edit_form.php');

}

function bizzCam_text()
{

    include('download.txt');

}

function plugin_admin_options_page() {
    global $user_c_id,$current_user;
    get_currentuserinfo();
    $user_c_id=$current_user->ID;
    $user_c_name=$current_user->user_login;
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h1>Add the video page</h1>
    <?php if($_POST['Submit'])
    {
        add($_POST);
        echo "Your value submitted.";
    }
    if($_POST['update'])
    {
        edit($_POST);
        echo "Your value updated.";
    }
    ?>
    <div id="embed_form">
        <form action="" method="post">

            <label>Title:</label>
            <div id="titlediv">
                <input style="width:30%;" type="text" autocomplete="off" id="title" value=""  name="title">
            </div>
            <input name="user_id" id="id" type="hidden" value="<?php echo $user_c_id; ?>" />
            <input name="user_name" id="u_name" type="hidden" value="<?php echo $user_c_name; ?>" />
            <label>Paste the embeded code here: </label>
            <div class="wp-editor-container" id="wp-content-editor-container">
                <textarea cols="110" rows="1" class="wp-editor-area" name="code" id="content"   required></textarea>
            </div>
            <p style="color:#949193;"><?php echo 'Paste video code in this format : ';print htmlentities('<iframe width="360" height="203" src="//bizzcam.com/core/embed.aspx?uid=1007" frameborder="0" scrolling="no" allowfullscreen></iframe>.'); ?></p>
            <div class="submit_div">
                <input type="submit" name="Submit" id="s_bu" value="Save">
                <input type="submit" name="update" id="u_bu" value="Update" style="display: none;">
            </div>
        </form>
        <p>To show the video in front-end, add the short-code [front_view] in the page or widget.</p>
    </div>
</div>
<div class='code_listin'>
    <table cellspacing="0" class="wp-list-table widefat fixed users">
        <thead>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><label for="cb-select-all-1" class="screen-reader-text">Select All</label><input type="checkbox" id="cb-select-all-1"></th><th style="" class="manage-column column-username sortable desc" id="username" scope="col"><a href=""><span>Title</span><span class="sorting-indicator"></span></a></th><th style="" class="manage-column column-name sortable desc" id="name" scope="col"><a href=""><span>Author</span><span class="sorting-indicator"></span></a></th><th style="" class="manage-column column-email sortable desc" id="email" scope="col"><a href=""><span>Source</span><span class="sorting-indicator"></span></a></th><th style="" class="manage-column column-posts num" id="posts" scope="col">Action</th></tr>
        </thead>

        <tbody data-wp-lists="list:user" id="the-list">
        <?php
        get_currentuserinfo();
        $user_c_id=$current_user->ID;
        $table = "wp_bizzcam";
        $query = "SELECT * FROM ".$table." where `user_id` = ".$user_c_id;
        $result = mysql_query($query);
        while($row = mysql_fetch_array( $result )) {
            ?>
            <tr class="alternate" id="<?php echo $row['id'];?>">
                <th class="check-column" scope="row">
                    <label for="cb-select-1" class="screen-reader-text">Select admin</label>
                    <input type="checkbox" value="1" class="administrator" id="user_1" name="users[]"></th>

                <td id='show_title' class="username column-username"><?php echo $row['title'] ?></td>
                <td class="name column-name"><?php echo $row['user_name'] ?></td>


                <td id='show_code' class="username column-username"><?php echo $rt=htmlentities($row['code']); ?></td>


                <td class="email column-email"><a href='admin.php?page=bizzCam-edit&id=<?php echo $row["id"];?>' id='edit_bu' ">Edit</a>/<a  id='<?php echo $row["id"];?>' class= 'delete_bu' ">Delete</a></td>
            </tr>
            <?php  }
        ?>
        </tbody>

        <thead>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><label for="cb-select-all-1" class="screen-reader-text">Select All</label><input type="checkbox" id="cb-select-all-1"></th><th style="" class="manage-column column-username sortable desc" id="username" scope="col"><a href=""><span>Title</span><span class="sorting-indicator"></span></a></th><th style="" class="manage-column column-name sortable desc" id="name" scope="col"><a href=""><span>Author</span><span class="sorting-indicator"></span></a></th><th style="" class="manage-column column-email sortable desc" id="email" scope="col"><a href=""><span>Source</span><span class="sorting-indicator"></span></a></th><th style="" class="manage-column column-posts num" id="posts" scope="col">Action</th></tr>
        </thead>
    </table>
</div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#edit_bu').click(function () {
                var title = jQuery('#show_title').text();
                var code = jQuery('#show_code').text();
                //jQuery('#title').attr("value",title );
                //jQuery('#content').attr("value",code );
                jQuery('#s_bu').css("display", "none");
                jQuery('#u_bu').css("display", "block");


            });
            jQuery('.delete_bu').click(function () {
                var pid = jQuery(this).attr('id');
                jQuery.ajax({
                    type: 'POST',
                    url: 'admin.php?page=bizzCam-edit',
                    data: {
                        'id': pid
                    },
                    success: function (data) {
                        window.location.reload(true);
                    }
                });
            });
        });
    </script>

<?php
}
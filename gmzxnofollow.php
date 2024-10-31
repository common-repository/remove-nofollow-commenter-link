<?php
/*
 * Plugin Name: Remove NoFollow Commenter URL
 * Plugin URI: http://myhub.gumz-ex-press.com/remove-comment-nofollow-plugin
 * Description: This plugin will easily removes commentors nofollow and replaces dofollow. And display an information on the comment form.
 * Version: 1.0
 * Author: Garry James Agum
 * Author URI: http://myhub.gumz-ex-press.com/
 */

function gmz_comment_options_activate() {
    add_option('gmz_comment_info_display', 'Commentor\'s URL Will be Followed');
}

function gmz_comment_options_deactivate() {
    delete_option('gmz_comment_info_display');
}

function gmz_comment_follow_menu() {
    add_submenu_page('options-general.php', 'GMZ Comment Option', 'GMZ Comment Follow', 9, 'gmz_comment_option', 'gmz_comment_settings');
}

function gmz_comment_settings_save($settings) {
    update_option('gmz_comment_info_display', htmlentities($settings['gmz_comment_info_display']));
}

function gmz_comment_settings_reset() {
    update_option('gmz_comment_info_display', 'Commentor\'s URL Being Followed');
}

function gmz_comment_settings() {
    if (isset($_POST['gmz_comment_display_save']) && $_POST['gmz_comment_display_save']) {
        gmz_comment_settings_save($_POST);
    }
    if (isset($_POST['gmz_comment_display_reset']) && $_POST['gmz_comment_display_reset']) {
        gmz_comment_settings_reset();
    }
?>
    <div class="wrap">
        <div class="icon32" id="icon-options-general"><br></div>
        <h2>GMZ Remove NoFollow Options</h2>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            <p>
                <label>
                    Text To Display In Comment Area <br />
                    <textarea cols="5" rows="5" name="gmz_comment_info_display" style="width: 300px; height: 50px;"><?php echo stripcslashes(get_option('gmz_comment_info_display')); ?></textarea
                </label>
            </p>
            <span class="submit">
                <input name="gmz_comment_display_save" id="save" value="<?php _e('Save Changes', 'gmz') ?>" type="submit"/>
                <input name="gmz_comment_display_reset" id="reset" value="<?php _e('Reset Options', 'gmz') ?>" type="submit"/>
            </span>
        </form>
    </div>
<?php
}

function gmz_do_follow_information($id) {
    echo '<p>**';
    echo stripslashes(get_option('gmz_comment_info_display'));
    echo '<p>';
    return $id;
}

function gmz_replace_no_follow() {
    $url = get_comment_author_url();
    $author = get_comment_author();
    if ($url != "") {
        $link = "<a rel='dofollow' href='$url' class='url'>$author</a>";
        return $link;
    } else {
        return $author;
    }
}

function gumz_comment_settings_link($links, $file) {
    if ($file == 'gmzxnofollow/gmzxnofollow.php' ) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=gmz_comment_option') . '">' . __('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_action('admin_menu', 'gmz_comment_follow_menu');
add_action('comment_form', 'gmz_do_follow_information');
add_action('get_comment_author_link', 'gmz_replace_no_follow');
add_filter('plugin_action_links','gumz_comment_settings_link',10, 2 );
register_activation_hook(__FILE__, 'gmz_comment_options_activate');
register_deactivation_hook(__FILE__, 'gmz_comment_options_deactivate');
?>

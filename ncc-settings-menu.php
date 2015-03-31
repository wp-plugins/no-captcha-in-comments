<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action('admin_menu', 'ncc_create_menu');

function ncc_create_menu() {

	//create new top-level menu
	add_menu_page('NCC Plugin Settings', 'NCC Settings', 'administrator', __FILE__, 'ncc_settings_page');
//
//	//call register settings function
//	add_action( 'admin_init', 'register_mysettings' );
}

//function register_mysettings() {
//	//register our settings
//	register_setting( 'ncc-settings-group', 'ncc_email_notify' );
//	register_setting( 'ncc-settings-group', 'ncc_cus_email' );
//	register_setting( 'ncc-settings-group', 'ncc_cus_spam' );
//}
function ncc_settings_page() {
?>
<div class="wrap">
<h2>No Captcha in Comments</h2>
<?php if(isset($_POST['ncc_cus_email'])||isset($_POST['ncc_cus_spam']) ) {
    $ncc_email_notify = isset($_POST['ncc_email_notify']) ? 1 : 0;
        $ncc_cus_email = isset($_POST['ncc_cus_email']) ? $_POST['ncc_cus_email'] : '';
        $ncc_cus_spam = isset($_POST['ncc_cus_spam']) ? $_POST['ncc_cus_spam'] : '';
        
     update_option('ncc_email_notify',$ncc_email_notify);
         update_option('ncc_cus_email',$ncc_cus_email);
     update_option('ncc_cus_spam',$ncc_cus_spam);

    ?>
    <div id="message" class="updated fade">
        <p><strong><?php echo 'Options Saved...' ?></strong></p>
    </div>
<?php } ?>

<?php
 $ncc_email_notify = get_option('ncc_email_notify');
 
 if($ncc_email_notify == 1) {
     $check_value = 'checked';
 }
 else {
     $check_value ='';
 }
        $ncc_cus_email = get_option('ncc_cus_email');
        $ncc_cus_spam = get_option('ncc_cus_spam');
?>
<form method="post">
    <?php // settings_fields( 'ncc-settings-group' ); ?>
    <?php // do_settings_sections( 'ncc-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Send spam comments to email</th>
        <td><input type="checkbox" id="ncc_checkboxOne" name="ncc_email_notify" value=""<?php echo $check_value ?>/></td>
        </tr>
        <script>
            jQuery(function(){
                var checkedStatus = jQuery('#ncc_checkboxOne').attr('checked');
                if(checkedStatus != 'checked')
                    jQuery("#ncc_email_id").val('');
});    
                    </script>
        <tr valign="top">
        <th scope="row">Custom Email to send spam comments</th>
        <td><input type="email" id="ncc_email_id" name="ncc_cus_email" value="<?php echo $ncc_cus_email; ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Custom spam words to eliminate</th>
        <td><textarea name="ncc_cus_spam" ><?php echo $ncc_cus_spam; ?></textarea></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 
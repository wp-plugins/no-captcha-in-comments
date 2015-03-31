<?php

function ncc_spam_update() {
	
		$ncc_user_id = get_current_user_id();
		
			update_user_meta($ncc_user_id, 'ncc_info_visibility', 1);
		
	
}
add_action('admin_init', 'ncc_spam_update');


function ncc_admin_notice() {
	global $pagenow;
	if ($pagenow == 'edit-comments.php'):
		$ncc_user_id = get_current_user_id();
		$ncc_info_visibility = get_user_meta($ncc_user_id, 'ncc_info_visibility', true);
		if ($ncc_info_visibility == 1 OR $ncc_info_visibility == ''):
			$ncc_stats = get_option('ncc_spam_stats', array());
			$ncc_blocked_total = $ncc_stats['blocked_total'];
			?>
			<div class="update-nag spam-panel-info">
				<p style="margin: 0;">
					<?php echo $ncc_blocked_total; ?> spam comments were blocked by plugin so far.
					
				</p>
			</div>
			<?php
		endif; 
	endif; 
}
add_action('admin_notices', 'ncc_admin_notice');



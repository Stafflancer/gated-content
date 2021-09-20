/*
* must be installed ACF & CF7
* in ACF create "True/False" switcher "g_status" to be able on/off function -- get_field("g_status");
* in ACF create "Num" field "g_paragraphs_count" to be able controll count of <p> -- get_field("g_paragraphs_count")
* in CF7 create and setup form, then in code paste form ID -- do_shortcode('[contact-form-7 id="7"]')
*/
function gate_post($content) {
	$addit_return = '';

        // if single post
        if(is_single()) {
        	// if gate active
        	if (get_field("g_status") && !isset($_COOKIE["wordpress_gateopn_stat"])) {
        		// work
        		$cont_tmp = $content;
				$cont_tmp = explode("</p>", $cont_tmp);
				$count = count($cont_tmp);
                
                // clear content
                $content = "";

                for ($i = 0; $i < $count; $i++ ) {
                	if ($i <= get_field("g_paragraphs_count")) {
                		$content .= $cont_tmp[$i];
                	}
				}

				$cf7_sent_list = "<script>document.addEventListener( 'wpcf7mailsent', function( event ) { setCookie('wordpress_gateopn_stat','ok','365'); location.reload(); }, false ); function setCookie(cname, cvalue, exdays) { var d = new Date(); d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000)); var expires = 'expires=' + d.toUTCString(); document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/'; }</script>";
				$addit_return = '<div id="gate_form"><h3>To Continue Reading...</h3>' . do_shortcode('[contact-form-7 id="7"]') . '</div>' . $cf7_sent_list;
            }
        }

        return $content . $addit_return;
}
add_filter ('the_content', 'gate_post');

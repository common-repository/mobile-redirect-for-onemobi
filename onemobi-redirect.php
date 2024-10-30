<?php  
/* 
    Plugin Name: Mobile Redirect for onemobi 
    Plugin URI: http://www.onemobi.net 
    Description: This plugin will let you redirect your website to your onemobi mobile website
    Author: Meisam Mulla (Insight Technologies Ltd.) 
    Version: 1.0 
    Author URI: http://www.onemobi.net 
*/ 
/**
 * MIT License
 * ===========
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 * @author      Meisam Mulla <meisam@onemobi.net>
 *				Insight Technologies Ltd. <support@onemobi.net>
 * @license     MIT License http://opensource.org/licenses/MIT
 * @link        Official page: http://www.onemobi.net
 */

require 'onemobi-detect.php';

function onemobi_admin() {
	require 'onemobi-admin.php';
}

function onemobi_admin_actions() {
	add_options_page('onemobi Mobile Redirect Settings', 'onemobi Redirect', 1, 'onemobi-redirect', 'onemobi_admin');
}

function onemobi_should_redirect() {
	if(get_option('onemobi_enabled')) {
		$onemobi = new onemobi_detect();

		if($onemobi->isMobile()) {
			if(!isset($_COOKIE['nomobile'])) {
				$redirect = true;
			}

			if(isset($_GET['nomobile']) || isset($_COOKIE['nomobile'])) {
				$redirect = false;
				setcookie("nomobile", 'true', time()+3600, "/");
			}

			if($redirect) {
				wp_redirect('http://' . get_option('onemobi_redirect_url'), '301');
			}
		}
	}
}


add_action('admin_menu', 'onemobi_admin_actions');
add_action('template_redirect', 'onemobi_should_redirect');


<?php
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

if(isset($_POST['Submit'])) {
	$email = (isset($_POST['onemobi_email']) && $_POST['onemobi_email'] !== '') ? $_POST['onemobi_email'] : null;
	$apikey = (isset($_POST['onemobi_apikey']) && $_POST['onemobi_apikey'] !== '') ? $_POST['onemobi_apikey'] : null;
	$enabled = (isset($_POST['onemobi_enabled']) && $_POST['onemobi_enabled'] !== '') ? $_POST['onemobi_enabled'] : null;

	if ($email && $apikey) {

		$fields = array('email' => urlencode($email),'apikey' => urlencode($apikey));

		foreach($fields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}

		rtrim($fields_string, '&');

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'http://api.onemobi.net/whoami');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST, 2);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		$api = json_decode(curl_exec($ch));
		curl_close($ch);

		if($api->status == 'ok'){
			update_option('onemobi_enabled', $enabled);
			update_option('onemobi_email', $email);
			update_option('onemobi_apikey', $apikey);
			update_option('onemobi_redirect_url', $api->url);

			echo '<div class="updated"><p><strong>Options saved.</strong></p></div>';
		} else {
			echo '<div class="error"><p><strong>' . $api->message . '</strong></p></div>';
		}
	} else {
		echo '<div class="error"><p><strong>Both Email Address & API Key are required. Settings have not been saved.</strong></p></div>';
	}
}

$enabled = get_option('onemobi_enabled');
$email = get_option('onemobi_email');
$apikey = get_option('onemobi_apikey');
$redirect_url = get_option('onemobi_redirect_url');

echo '<div class="wrap">
		<h2>' . __( 'onemobi Mobile Redirect Options', 'onemobi_trdom' ) . '</h2>  

		<form name="onemobi_form" method="post" action="' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '"> 
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							Redirection Settings
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Redirection Settings</span></legend>
								<input type="checkbox" name="onemobi_enabled" ' . (($enabled) ? 'checked': '') .' id="enabled"> <label for="enabled">Enable mobile redirection</label>
								<br />
								<small><em>Checking this box will enable redirection for mobile devices including tablets</em></small>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="email">Email Address</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Email Address</span></legend>
								<input type="text" name="onemobi_email" id="email" value="' . $email . '" size="60">
								<br />
								<small><em>The email address with which you login to manage your mobile website</em></small>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="api">API Key:</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>API Key</span></legend>
								<input type="text" name="onemobi_apikey" id="api" value="' . $apikey . '" size="60">
								<br />
								<small><em>Your API Key can be found in your <a href="http://login.onemobi.net">onemobi control panel</a> under Settings</em></small>
							</fieldset>
						</td>
					</tr>';

			if($redirect_url) {
				echo '<tr valign="top">
						<th scope="row">
							<label for="domain">Mobile Domain:</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Mobile Domain</span></legend>
								<input type="text" name="onemobi_apikey" disabled id="domain" value="' . $redirect_url . '" size="60">
								<br />
								<small><em>This is your onemobi domain</em></small>
							</fieldset>
						</td>
					</tr>';
			}
			
			echo '</tbody>
			</table>

			<input type="submit" name="Submit" id="submit" class="button button-primary" value="Save Changes">
		</form>
	  </div>';



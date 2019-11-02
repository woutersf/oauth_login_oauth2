<?php
namespace Drupal\oauth_login_oauth2;
    class feedback{
	public static function oauth_login_oauth2_feedback()
	{
			global $base_url;
			if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url')))
				$baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url');
		  	else
				$baseUrlValue = $base_url;
			$feedback_url = $baseUrlValue.'/feedback';
            $_SESSION['mo_other']= "True";
			$myArray = array();
			$myArray = $_POST;
			$form_id=$_POST['form_id'];
			$form_token=$_POST['form_token'];

?>
			<html>
				<head>
            <title>Feedback</title>
            <link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
			</head>
			<body style="font-family: 'PT Serif', serif;">
			<h5 style="font-size:20px;color: black;margin-left:26%;margin-top:3%">Hey, it seems like you want to deactivate miniOrange OAuth Client Module</h5>
			<!-- The Modal -->
			<div id="myModal" style="margin-left:40%;margin-top: 2%"/>
			<!-- Modal content -->
            
			<h3 style="font-size:42px;color: maroon"/>What Happened? </h3>

				<div style="padding:10px;">
					<div class="alert alert-info" style="margin-bottom:0px">
						<p style="font-size:15px"></p>
                    </div>
                 </div>

			<form name="f" method="post" action="<?php echo $feedback_url; ?>" id="mo_feedback">
			<div>
				<p style="margin-left:0%">
				<?php
				if(empty(\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_customer_admin_email')))
				{ ?>
				<br>Email ID: <input type="text" required style="border-color: black;padding: 5px 15px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" name="miniorange_feedback_email" />
				<?php
					}
				?>
						<br>
					<?php
						$deactivate_reasons = array
						(
							"Not Working",
							"Not Receiving OTP During Registration",
							"Does not have the features I'm looking for",
							"Redirecting back to login page after Authentication",
							"Confusing Interface",
							"Bugs in the plugin",
							"Other Reasons:"
						);
						foreach ( $deactivate_reasons as $deactivate_reasons )
						{
							?>
							<div  class="radio" style="padding:2px;font-size: 8px">
								<label style="font-weight:normal;font-size:14.6px;color:maroon" for="<?php echo $deactivate_reasons; ?>">

								<input type="radio" name="deactivate_plugin" value="<?php echo $deactivate_reasons;?>" required>
								<?php echo $deactivate_reasons;

									?>

								</label>
							</div>

						<?php

						}
							?>
							<input type="hidden" name="mo_oauth_client_check" value="True">
							<input type="hidden" name="form_token" value=<?php echo $form_token ?> >
							<input type="hidden" name="form_id" value= <?php echo $form_id ?>>
						<br>
						<textarea id="query_feedback" name="query_feedback"  rows="4" style="margin-left:2%" cols="50" placeholder="Write your query here"></textarea>
						<br><br>
						<div class="mo2f_modal-footer">
							<input type="submit" style="background-color: #4CAF50;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" name="miniorange_feedback_submit" class="button button-primary button-large" value="Submit and Continue" />
						</div>
						<?php
							echo "<br><br>";
							foreach($_POST as $key => $value) {
								self::hiddenOauthClientFields($key,$value);
							}
						?>
					</div>
			</form>
			</body>
			</html>
			<?php
			exit;
	}
	public static function hiddenOauthClientFields($key,$value)
	{
		$hiddenOauthClientField = "";
        $value2 = array();
        if(is_array($value)) {
            foreach($value as $key2 => $value2)
            {
                if(is_array($value2)){
                    hiddenOauthClientFields($key."[".$key2."]",$value2);
                } else {
                    $hiddenOauthClientField = "<input type='hidden' name='".$key."[".$key2."]"."' value='".$value2."'>";
                }
            }
        }else{
            $hiddenOauthClientField = "<input type='hidden' name='".$key."' value='".$value."'>";
        }

		echo $hiddenOauthClientField;
	}
}
?>
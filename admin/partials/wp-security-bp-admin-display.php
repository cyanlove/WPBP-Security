<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

class WP_Security_BP_Admin_Get_Users {

	private $users;

	public function __construct(){
		$this->$users = get_users(); 
	}
	
	public function check_danger_id_admin(){ 

		$danger_ids = array();

			foreach( $this->$users as $user ){
				if( $user->ID < 5 ){
					$result = [
						'user_id'    => $user->ID,
						'user_name'  => $user->display_name,
						'user_email' => $user->user_email,
					];
					array_push($danger_ids, $result);
				}
			}

		return $danger_ids;
	}
}

?>	
<!--------- V I E W --------->
<h2>WP SECURITY PLUGIN</h2>
<?php 

$check = new WP_Security_BP_Admin_Get_Users();
$check_danger_id_admin = $check->check_danger_id_admin();
	
	if( empty($check_danger_id_admin) ) :
?>
	<button class="accordion" style="background: green;">CHECK 1</button>
	<div class="panel"></div>
<?php
	else :;
?>
	<button class="accordion" style="background: #E56464 ;">CHECK 1</button>
	<div class="panel">
		<p>Los siguientes usuarios tienen una id poco segura:</p>
		<table border="1">
		<?php
			foreach( $check_danger_id_admin as $error ) :
		?>
			<tr>
				  <td><?php echo $error['user_id'];?></td>
				  <td><?php echo $error['user_name'];?></td>
				  <td><?php echo $error['user_email'];?></td>
			</tr>
		<?php endforeach;?>
		</table>
		<p>Para resolver el problema llame a Damaso</p>
	</div>
<?php
 	endif;
?>
<button class="accordion" >CHECK 2</button>
<div class="panel">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>

<button class="accordion" >CHECK 3</button>
<div class="panel">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>	


<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>



















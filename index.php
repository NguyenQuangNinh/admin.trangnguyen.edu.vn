<?php
	include './config/config.php';
	include './model/ModelBase.php';
	//require_once './model/MenusData.php';
	include './model/UserAdminData.php';

	if(!isset($_SESSION)){
		session_start();
	}

	$admin_info = isset($_SESSION[session_user])?$_SESSION[session_user]:null;

	if($admin_info == null){
		include './ajax/LoginUtil.php';
		$admin_info = Relogin();
	}

	if($admin_info!=null){
		//render menu
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<?php require_once('./include/header.php');?>
		<script>
			var admin_info = { username: "<?php echo $admin_info["username"] ?>", id: <?php echo $admin_info["_id"] ?> };
			Raven.setUserContext(admin_info);
		</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
		<div class="wrapper">
			<header class="main-header">
				<a href="/" class="logo" title="Home">
					<span class="logo-mini"><b>Admin</b></span>
					<span class="logo-lg"><b>Admin</b> Cpanel</span>
				</a>
				<nav class="navbar navbar-static-top" role="navigation">
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" title="mở rộng - thu hẹp menu">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<?php require_once('./include/notifications.php');?>
							<?php require_once('./include/accout.php');?>
							<!-- <li>
								<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
							</li> -->
						</ul>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar">
				<section class="sidebar">
					<?php require_once('./include/search.php');?>
					<ul class="sidebar-menu">
						<?php require_once('./include/left_menu.php');?>
					</ul>
				</section>
			</aside>
			<div class="content-wrapper">
				<?php
					// $title='home - trangnguyen';
					// $buffer=ob_get_contents();
					// ob_end_clean();
					//require_once('./views/news/category.php');
					// $buffer=str_replace("{%TITLE%}",$title,$buffer);
					// echo $buffer;
				?>
				<?php
					$page = 'home';
					if(isset($_GET['page'])) $page = $_GET['page'];
					
					$page_file = './views/' . $page .'.php';
					if(file_exists($page_file)){
						// if($admin_info['is_super_admin']=='1' || in_array($page, $listRulerMenu)){
							include($page_file);
						// }
						// else{
						// 	include './views/401.html';
						// }
					}
					else{
						$page_file = './views/' . $page .'/index.php';
						if(file_exists($page_file)){
							// if($admin_info['is_super_admin']=='1' || in_array($page, $listRulerMenu)){
								include($page_file);
							// }
							// else{
							// 	include './views/401.html';
							// }
						}
						else{
							include './views/404.html';
						}
					}
				?>
			</div>
			<?php //require_once('./include/footer.php');?>
		</div>
		<div id="spinner" style=" position: fixed; top: 0; right: 0; padding: 0 20px;z-index:999999; background: rgba(0, 0, 0, 0.62); border: 1px solid #020202; border-top: 0; border-right: 0; border-bottom-left-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; -ms-border-bottom-left-radius: 5px; display: none; "><!--?xml version="1.0" encoding="utf-8"?--> <svg width="32px" height="32px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ellipsis"> <rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect> <circle cx="34.7825" cy="50" r="0" fill="#ffffff"> <animate id="anir11" attributeName="r" from="0" to="15" begin="0s;anir14.end" dur="0.125s" fill="freeze"></animate> <animate id="anir12" attributeName="r" from="15" to="15" begin="anir11.end" dur="0.625s" fill="freeze"></animate> <animate id="anir13" attributeName="r" from="15" to="0" begin="anir12.end" dur="0.125s" fill="freeze"></animate> <animate id="anir14" attributeName="r" from="0" to="0" begin="anir13.end" dur="0.125s" fill="freeze"></animate> <animate id="anix11" attributeName="cx" from="16" to="16" begin="0s;anix18.end" dur="0.125s" fill="freeze"></animate> <animate id="anix12" attributeName="cx" from="16" to="16" begin="anix11.end" dur="0.125s" fill="freeze"></animate> <animate id="anix13" attributeName="cx" from="16" to="50" begin="anix12.end" dur="0.125s" fill="freeze"></animate> <animate id="anix14" attributeName="cx" from="50" to="50" begin="anix13.end" dur="0.125s" fill="freeze"></animate> <animate id="anix15" attributeName="cx" from="50" to="84" begin="anix14.end" dur="0.125s" fill="freeze"></animate> <animate id="anix16" attributeName="cx" from="84" to="84" begin="anix15.end" dur="0.125s" fill="freeze"></animate> <animate id="anix17" attributeName="cx" from="84" to="84" begin="anix16.end" dur="0.125s" fill="freeze"></animate> <animate id="anix18" attributeName="cx" from="84" to="16" begin="anix17.end" dur="0.125s" fill="freeze"></animate> </circle> <circle cx="16" cy="50" r="15" fill="#0084ff"> <animate id="anir21" attributeName="r" from="15" to="15" begin="0s;anir25.end" dur="0.5s" fill="freeze"></animate> <animate id="anir22" attributeName="r" from="15" to="0" begin="anir21.end" dur="0.125s" fill="freeze"></animate> <animate id="anir23" attributeName="r" from="0" to="0" begin="anir22.end" dur="0.125s" fill="freeze"></animate> <animate id="anir24" attributeName="r" from="0" to="15" begin="anir23.end" dur="0.125s" fill="freeze"></animate> <animate id="anir25" attributeName="r" from="15" to="15" begin="anir24.end" dur="0.125s" fill="freeze"></animate> <animate id="anix21" attributeName="cx" from="16" to="50" begin="0s;anix28.end" dur="0.125s" fill="freeze"></animate> <animate id="anix22" attributeName="cx" from="50" to="50" begin="anix21.end" dur="0.125s" fill="freeze"></animate> <animate id="anix23" attributeName="cx" from="50" to="84" begin="anix22.end" dur="0.125s" fill="freeze"></animate> <animate id="anix24" attributeName="cx" from="84" to="84" begin="anix23.end" dur="0.125s" fill="freeze"></animate> <animate id="anix25" attributeName="cx" from="84" to="84" begin="anix24.end" dur="0.125s" fill="freeze"></animate> <animate id="anix26" attributeName="cx" from="84" to="16" begin="anix25.end" dur="0.125s" fill="freeze"></animate> <animate id="anix27" attributeName="cx" from="16" to="16" begin="anix26.end" dur="0.125s" fill="freeze"></animate> <animate id="anix28" attributeName="cx" from="16" to="16" begin="anix27.end" dur="0.125s" fill="freeze"></animate> </circle> <circle cx="50" cy="50" r="15" fill="#ffffff"> <animate id="anir31" attributeName="r" from="15" to="15" begin="0s;anir35.end" dur="0.25s" fill="freeze"></animate> <animate id="anir32" attributeName="r" from="15" to="0" begin="anir31.end" dur="0.125s" fill="freeze"></animate> <animate id="anir33" attributeName="r" from="0" to="0" begin="anir32.end" dur="0.125s" fill="freeze"></animate> <animate id="anir34" attributeName="r" from="0" to="15" begin="anir33.end" dur="0.125s" fill="freeze"></animate> <animate id="anir35" attributeName="r" from="15" to="15" begin="anir34.end" dur="0.375s" fill="freeze"></animate> <animate id="anix31" attributeName="cx" from="50" to="84" begin="0s;anix38.end" dur="0.125s" fill="freeze"></animate> <animate id="anix32" attributeName="cx" from="84" to="84" begin="anix31.end" dur="0.125s" fill="freeze"></animate> <animate id="anix33" attributeName="cx" from="84" to="84" begin="anix32.end" dur="0.125s" fill="freeze"></animate> <animate id="anix34" attributeName="cx" from="84" to="16" begin="anix33.end" dur="0.125s" fill="freeze"></animate> <animate id="anix35" attributeName="cx" from="16" to="16" begin="anix34.end" dur="0.125s" fill="freeze"></animate> <animate id="anix36" attributeName="cx" from="16" to="16" begin="anix35.end" dur="0.125s" fill="freeze"></animate> <animate id="anix37" attributeName="cx" from="16" to="50" begin="anix36.end" dur="0.125s" fill="freeze"></animate> <animate id="anix38" attributeName="cx" from="50" to="50" begin="anix37.end" dur="0.125s" fill="freeze"></animate> </circle> <circle cx="84" cy="50" r="15" fill="#0084ff"> <animate id="anir41" attributeName="r" from="15" to="0" begin="0s;anir44.end" dur="0.125s" fill="freeze"></animate> <animate id="anir42" attributeName="r" from="0" to="0" begin="anir41.end" dur="0.125s" fill="freeze"></animate> <animate id="anir43" attributeName="r" from="0" to="15" begin="anir42.end" dur="0.125s" fill="freeze"></animate> <animate id="anir44" attributeName="r" from="15" to="15" begin="anir43.end" dur="0.625s" fill="freeze"></animate> <animate id="anix41" attributeName="cx" from="84" to="84" begin="0s;anix48.end" dur="0.125s" fill="freeze"></animate> <animate id="anix42" attributeName="cx" from="84" to="16" begin="anix41.end" dur="0.125s" fill="freeze"></animate> <animate id="anix43" attributeName="cx" from="16" to="16" begin="anix42.end" dur="0.125s" fill="freeze"></animate> <animate id="anix44" attributeName="cx" from="16" to="16" begin="anix43.end" dur="0.125s" fill="freeze"></animate> <animate id="anix45" attributeName="cx" from="16" to="50" begin="anix44.end" dur="0.125s" fill="freeze"></animate> <animate id="anix46" attributeName="cx" from="50" to="50" begin="anix45.end" dur="0.125s" fill="freeze"></animate> <animate id="anix47" attributeName="cx" from="50" to="84" begin="anix46.end" dur="0.125s" fill="freeze"></animate> <animate id="anix48" attributeName="cx" from="84" to="84" begin="anix47.end" dur="0.125s" fill="freeze"></animate> </circle> </svg></div>
		<div class="div_msg_error" style="z-index: 999; position: fixed; left: 0; right: 0; bottom: 50px; text-align: center; display: none;"> <div class="content" style="background: rgba(0, 0, 0, 1);border-radius: 1px;margin: auto;box-shadow: 0 0 5px #000;text-shadow: none;color: #FFF;padding: 7px 10px;display: inline;">Error!</div></div>
		<!--<script>
			$(function(){
				$('.myModal').on('hidden', function () {
					$('body').css('padding-right', '0');
				});
			});
		</script>-->
	</body>
</html>

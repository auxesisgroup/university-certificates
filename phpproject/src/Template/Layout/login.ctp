<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset() ?>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
    <title>
        <?php // echo $this->fetch('title') ?>
		<?php if(!empty($title)){ echo $title ; } else { echo 'University certificates over Blokchain' ;}  ?>
    </title>
    <?php echo $this->Html->meta('icon') ?>
    <?php echo $this->Html->css('admin/assets/css/admin/module.admin.page.login.min.css') ?>
    <?php // echo $this->Html->script(array('admin/library/jquery/jquery.min','admin/plugins/less-js/less.min','admin/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min','admin/library/modernizr/modernizr')) ; ?>
    <?php echo $this->fetch('meta') ?>
    <?php echo $this->fetch('css') ?>
    <?php echo $this->fetch('script') ?>
</head>
<body class=" loginWrapper">
		  <div id="content">
	<h4 class="innerAll margin-none text-center" style="color:#fff"><i class="fa fa-lock"></i> Login to your Account</h4>

		<div class="login spacing-x2">
			<div class="placeholder text-center"><?php echo $this->Html->image('logo.png',array('width '=>'400'));?></div>
			<div class="col-sm-6 col-sm-offset-3">
			     <?php echo $this->Flash->render() ?>
			     <?php  echo $this->fetch('content') ?>
			</div>
		</div>
    <footer>
		<?php echo $this->Html->script(array('admin/library/bootstrap/js/bootstrap.min' , 'admin/modules/admin/tables/datatables/assets/custom/js/DT_bootstrap' ,'admin/plugins/holder/holder', 'admin/core/js/core.init')) ; ?>
    </footer>
</body>
</html>

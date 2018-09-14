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
        <?php if(!empty($title)){ echo $title ; } else { echo 'University certificates over Blokchain' ;}  ?>
    </title>
    <?php echo $this->Html->meta('icon') ?>
    <?php echo $this->Html->css('admin/assets/css/admin/module.admin.page.tables.min') ?>
	<?php echo $this->Html->script(array('admin/library/jquery/jquery.min','admin/plugins/less-js/less.min','admin/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min','admin/library/modernizr/modernizr')) ; ?>
    <?php echo $this->fetch('meta') ?>
    <?php echo $this->fetch('css') ?>
    <?php echo $this->fetch('script') ?>
</head>
<body>
    <div class="navbar navbar-fixed-top navbar-primary main" role="navigation">
    
			<div class="navbar-header pull-left">
				<div class="navbar-brand">
					<div class="pull-left">
						<a href="" class="toggle-button toggle-sidebar btn-navbar"><i class="fa fa-bars"></i></a>
					</div>
					 <a href='javascript:void(0)' class='appbrand innerL'>College Portal</a>
				</div>
			</div>
			<ul class="nav navbar-nav navbar-right hidden-xs">
					<li><?php echo $this->Html->link("<i class='fa fa-sign-out'></i>",array('controller'=>'universities','action'=>'logout'),array('class'=>'menu-icon','escape'=>false,'title'=>'Logout')) ;?></li>
			</ul>
		</div>
		<?php echo $this->element('collegesidebar') ;?>
		<div id="content">
		
		<div class="innerAll spacing-x2">
		            <?php echo $this->Flash->render() ?>
                    <?php echo $this->fetch('content') ?>
		</div>
	
	</div>
    <footer>
		<script>
	
	var primaryColor = '#fbad06',
		dangerColor = '#b55151',
		infoColor = '#466baf',
		successColor = '#8baf46',
		warningColor = '#ab7a4b',
		inverseColor = '#45484d';
	
	var themerPrimaryColor = primaryColor;
	</script>
	<?php echo $this->Html->script(array('admin/library/bootstrap/js/bootstrap.min' , 'admin/plugins/nicescroll/jquery.nicescroll.min','admin/core/js/animations.init' , 'admin/modules/admin/tables/datatables/assets/lib/js/jquery.dataTables.min' , 'admin/modules/admin/tables/datatables/assets/lib/extras/TableTools/media/js/TableTools.min','admin/modules/admin/tables/datatables/assets/custom/js/DT_bootstrap' , 'admin/modules/admin/tables/datatables/assets/custom/js/datatables.init','admin/plugins/holder/holder','admin/core/js/sidebar.main.init' , 'admin/core/js/sidebar.collapse.init' , 'admin/core/js/core.init')) ; ?>
    </footer>
</body>
</html>

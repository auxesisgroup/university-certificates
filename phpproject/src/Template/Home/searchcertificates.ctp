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
		<?php  echo 'University certificates over Blokchain' ;  ?>
    </title>
    <?php echo $this->Html->meta('icon') ?>
    <?php echo $this->Html->css('admin/assets/css/admin/module.admin.page.login.min.css') ?>
    <?php echo $this->fetch('meta') ?>
    <?php echo $this->fetch('css') ?>
    <?php echo $this->fetch('script') ?>
</head>
<?php $siteUrl = 'http://univcert.auxledger.org/blockchain/' ; ?>
<body class=" loginWrapper">
	 <div id="content">
	
		<div class="login spacing-x2">
			<div class="col-sm-12" style="margin-top:20px">
				<div class="panel panel-default">
					  <div class="panel-body innerAll">
						<h2>Certificates </h2>
						   <?php   ?>
						   <?php if(!empty(!$certificates->isEmpty())) { ?>
							  <table class="table table-condensed">
								<thead>
									<tr>
									   
										<th scope="col"><?php echo 'Aadhaar Number' ?></th>
										<th scope="col"><?php echo 'Mobile' ?></th>
										<th scope="col"><?php echo 'Certificate address' ?></th>
										<th scope="col" class="actions"><?php echo  __('Actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach ($certificates as $certificate):
										 ?>
									 <tr>
										
										<td><?php echo $certificate->adhar_number ; ?></td>
										<td><?php echo $certificate->mobile ; ?></td>
										<td><?php echo $certificate->blockchain_certificate_address ; ?></td>
										<td class="text-right1">
											<div class="btn-group btn-group-xs ">
												<?php echo $this->Html->link("<i class='fa fa-eye'></i>",array('controller'=>'home','action'=>'certificate',$certificate['id']),array('class'=>'btn btn-inverse','escape'=>false,'title'=>'View Certificate')) ; ?>
												
											</div>
										</td>
									</tr>
									   <?php endforeach; ?>
								</tbody>
							</table>
						 <?php } else { ?>
						    <div class="certificaterow" >
							   No certificates avalable.
							
							</div>
						 <?php } ?>
				   </div>
				</div>
			     
		 </div>
	</div>
    <footer>
		<?php echo $this->Html->script(array('admin/library/bootstrap/js/bootstrap.min' , 'admin/modules/admin/tables/datatables/assets/custom/js/DT_bootstrap' ,'admin/plugins/holder/holder','admin/core/js/core.init')) ; ?>
    </footer>
</body>
</html>

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
<?php $siteUrl = 'http://univcert.auxledger.org/blockchain/' ;
  use Cake\Core\Configure;
  $degrees =Configure::read('Site.degrees');
 ?>
<body class=" loginWrapper">
		  <div id="content">

		<div class="login spacing-x2">

			<div class="col-sm-12" style="margin-top:20px">
				<div class="panel panel-default">
					  <div class="panel-body innerAll">
					  <h2>Certificate Details </h2>
					  <?php if(!empty($certificate)) { ?>
					  <?php if($certificate->status==0) { ?>
							<div style="font-size:14px;color:red">( Not Verified by Universities )</div>
					   <?php } ?>
						<div class="col-sm-12 ">
						    <?php if(!empty($certificate->blockchain_certificate_address)) {?>
								<div class="row certificaterow" >
									  <div class="col-sm-3 certificatelabel">
										 Certificate Address :
									  </div>
									  <div  class="col-sm-9"  >
										 <a target="_blank" href="<?php echo 'https://testnet-auxledger.firebaseapp.com/#/address/'.$certificate->blockchain_certificate_address ?>" ><?= $certificate->blockchain_certificate_address ?></a>
									  </div>
								</div>
							<?php } ?>
						    <div class="row certificaterow" >
							      <div class="col-sm-3 certificatelabel" >
								    Student Name :
								  </div>
								  <div  class="col-sm-9"  >
								     <?php echo $certificate->student_name ?>
								  </div>
							</div>
							<div class="row certificaterow" >
							      <div class="col-sm-3 certificatelabel" >
								    Student Id :
								  </div>
								  <div  class="col-sm-9"  >
								     <?php echo $certificate->student_id ?>
								  </div>
							</div>
							<?php if(!empty($certificate->degree)) {?>
							<div class="row certificaterow">
							      <div class="col-sm-3 certificatelabel" >
								     Degree :
								  </div>
								  <div  class="col-sm-9" >
								     <?php echo $degrees[$certificate->degree] ; ?>
								  </div>
							</div>
							<?php } ?>
							<div class="row certificaterow">
							      <div class="col-sm-3 certificatelabel" >
								     Aadhaar Number :
								  </div>
								  <div  class="col-sm-9" >
								     <?php echo $certificate->adhar_number ?>
								  </div>
							</div>
							<div class="row certificaterow" >
							      <div class="col-sm-3 certificatelabel">
								      Mobile Number :
								  </div>
								  <div  class="col-sm-9" >
								     <?php echo $certificate->mobile ?>
								  </div>
							</div>
							<div class="row certificaterow" >
							      <div class="col-sm-3 certificatelabel" >
								     certificate Hash :
								  </div>
								  <div  class="col-sm-9" >
								     <?php echo $certificate->hash ?>
								  </div>
							</div>

							<?php if(!empty($certificate->transaction_hash)) {?>
							<div class="row certificaterow" >
							      <div class="col-sm-3 certificatelabel">
								    Transaction Id :
								  </div>
								  <div  class="col-sm-9"  >
								     <a target="_blank" href="https://testnet-auxledger.firebaseapp.com/#/transaction/<?php echo $certificate->transaction_hash ?>" ><?php echo $certificate->transaction_hash ?></a>
								  </div>
							</div>
							<?php } if(!empty($certificate->imgname)) { ?>
							<div class="row certificaterow" >
							      <div class="col-sm-3 certificatelabel">
								    View Certificate :
								  </div>
								  <div  class="col-sm-9"  >
								     <a href="<?php echo $siteUrl.'certificates/'.$certificate->imgname?>" target="_blank">View</a>
								  </div>
							</div>
							<?php } ?>
						</div>
						<?php } else{?>
						    <div class="row certificaterow" >
							      <div class="col-sm-23">
								       Certificate Not avalable.
								  </div>
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

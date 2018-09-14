<?php $siteUrl = 'https://vendor-governance.auxledger.org/universitycertificates/' ;
 use Cake\Core\Configure;
 $degrees =Configure::read('Site.degrees');
 ?>
<div class="viewcollege view large-9 medium-8 columns content">
    <div class="widget-body">
	      <h2 style="text-align:center;margin-bottom:20px">Certificate Details</h2>
			<table class="table table-condensed">
			    <tr>
					<th scope="row" colspan="1"><?= __('College Id') ?></th>
					<td colspan="2"><?= $certificate->college_id ?></td>
				</tr>
				<tr>
					<th scope="row" colspan="1"><?= __('Student Id') ?></th>
					<td colspan="2"><?= $certificate->student_id ?></td>
				</tr>
				<tr>
					<th scope="row" colspan="1"><?= __('Student Name') ?></th>
					<td colspan="2"><?= $certificate->student_name ?></td>
				</tr>
				<?php if(!empty($certificate->degree)) {?>
				<tr>
					<th scope="row"><?= __('Degree') ?></th>
					<td><?= $degrees[$certificate->degree] ?></td>
				</tr>
				<?php } ?>
				 <tr>
					<th scope="row"><?= __('Aadhaar number') ?></th>
					<td><?= $certificate->adhar_number ?></td>
				</tr>
				 <tr>
					<th scope="row"><?= __('Mobile') ?></th>
					<td><?= $certificate->mobile ?></td>
				</tr>
				<tr>
					<th scope="row"><?= __('student_public_key') ?></th>
					<td><?= $certificate->student_public_key ?></td>
				</tr>
				<tr>
					<th scope="row"><?= __('Certificate Hash') ?></th>
					<td><?= $certificate->hash ?></td>
				</tr>
				<?php if(!empty($certificate->blockchain_certificate_address)) {?>
				<tr>
					<th scope="row"><?= __('Blockchain Certificate address') ?></th>
					<td><a target="_blank" href="<?php echo 'https://testnet-auxledger.firebaseapp.com/#/address/'.$certificate->blockchain_certificate_address ?>" ><?= $certificate->blockchain_certificate_address ?></a></td>
				</tr>
				<?php } if(!empty($certificate->transaction_hash)) {?>
				<tr>
					<th scope="row"><?= __('Transaction hash') ?></th>
					<td><a target="_blank" href="https://testnet-auxledger.firebaseapp.com/#/transaction/<?php echo $certificate->transaction_hash ?>" ><?= $certificate->transaction_hash ?></a></td>
				</tr>
				<?php } if(!empty($certificate->imgname)) { ?>
				<tr>
					<th scope="row"><?= __('View Certificate') ?></th>
					<td><a href="<?php echo $siteUrl.'certificates/'.$certificate->imgname?>" target="_blank">View</a></td>
				</tr>
			     <?php } ?>
			</table>
	</div>
</div>

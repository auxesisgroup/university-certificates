	<div class="widget widget-inverse">
			<div class="widget-head">
				<h4 class="heading">Certificates</h4>
			</div>
			<div class="widget-body">
				<table class="table table-condensed">
					<thead>
						<tr>
						    <th scope="col"><?php echo 'S No.' ?></th>
							<th scope="col"><?php echo 'Aadhaar Number' ?></th>
							<th scope="col"><?php echo 'Mobile' ?></th>
							<th scope="col"><?php echo 'Student Public Key' ?></th>
							<th scope="col" class="actions"><?php echo  __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
					    <?php 
                            $i =1 ;
     						foreach ($certificates as $certificate):
							    $statusid = $certificate['status'] ;
							   
							 ?>
						 <tr>
							<td><?php echo $i ?></td>
							<td><?php echo $certificate->adhar_number ; ?></td>
							<td><?php echo $certificate->mobile ; ?></td>
							<td><?php echo $certificate->student_public_key ; ?></td>
							<td class="text-right1">
								<div class="btn-group btn-group-xs ">
								    <?php echo $this->Html->link("<i class='fa fa-eye'></i>",array('controller'=>'certificates','action'=>'detail',$certificate['id']),array('class'=>'btn btn-inverse','escape'=>false,'title'=>'View Certificate')) ; ?>
									 <?php  if(($statusid==0)&&($roleId=='1')) {  ?>
										   <?php echo $this->Html->link("<i class='fa fa-check'></i>",array('controller'=>'universities','action'=>'approve',$certificate['id']),array('class'=>'btn btn-inverse','escape'=>false,'title'=>'Approve certificate','target'=>'_blank')) ; ?>
									 <?php } ?>
								</div>
							</td>
						</tr>
						   <?php $i++ ; endforeach; ?>
					</tbody>
				</table>
			</div>
	</div>
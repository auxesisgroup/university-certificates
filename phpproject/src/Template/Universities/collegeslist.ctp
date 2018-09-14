	<div class="row">
		<div class="col-sm-10"><?php echo $this->Html->link('Add College',array('controller'=>'universities','action'=>'addcollege'),array('class'=>'btn btn-primary btn-sm')) ; ?></div>
    </div>
	<div class="widget widget-inverse">
			<div class="widget-head">
				<h4 class="heading">Colleges List</h4>
			</div>
			<div class="widget-body">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th scope="col"><?php echo 'SNo' ?></th>
							<th scope="col"><?php echo 'College Id' ?></th>
							<th scope="col"><?php echo 'public_key' ?></th>
							<th scope="col" class="actions"><?php echo  __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
					    <?php 
                            $i =1 ;
     						foreach ($colleges as $college): ?>
						 <tr>
							<td><?php echo $i ?></td>
							<td><?php echo $college->username ; ?></td>
							<td><?php echo $college->public_key ; ?></td>
							<td class="text-right1">
								<div class="btn-group btn-group-xs ">
								    <?php echo $this->Html->link("<i class='fa fa-eye'></i>",array('controller'=>'universities','action'=>'viewcollege',$college['id']),array('class'=>'btn btn-inverse','escape'=>false,'title'=>'Edit')) ; ?>
								</div>
							</td>
						</tr>
						   <?php $i++ ; endforeach; ?>
					</tbody>
				</table>
			</div>
	</div>
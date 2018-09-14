<div class="viewcollege view large-9 medium-8 columns content">
    <div class="widget-body">
	      <h2 style="text-align:center;margin-bottom:20px">College Infromation</h2>
			<table class="table table-condensed">
				<tr>
					<th scope="row"><?= __('College Id') ?></th>
					<td><?= $college->username ; ?></td>
				</tr>
				<tr>
					<th scope="row"><?= __('College Name') ?></th>
					<td><?= $college->name ; ?></td>
				</tr>
				 <tr>
					<th scope="row"><?= __('Public key') ?></th>
					<td><?= $college->public_key ; ?></td>
				</tr>
				 <tr>
					<th scope="row"><?= __('Degree offered') ?></th>
					<td><?= $college->degree_offered ;?></td>
				</tr>
				<tr>
					<th scope="row"><?= __('Email') ?></th>
					<td><?= $college->email ; ?></td>
				</tr>
				<tr>
					<th scope="row"><?= __('phone') ?></th>
					<td><?= $college->phone ; ?></td>
				</tr>
			   
			</table>
	</div>
</div>

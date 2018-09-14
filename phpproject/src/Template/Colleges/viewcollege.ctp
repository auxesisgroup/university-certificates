<?php
/**
  * @var \App\View\AppView $this
  */
?>

<div class="viewcollege view large-9 medium-8 columns content">
    <div class="widget-body">
			<table class="table table-condensed">
				<tr>
					<th scope="row"><?= __('College Id') ?></th>
					<td><?= h($college->username) ?></td>
				</tr>
				 <tr>
					<th scope="row"><?= __('Public key') ?></th>
					<td><?= h($college->public_key) ?></td>
				</tr>
				 <tr>
					<th scope="row"><?= __('Degree offered') ?></th>
					<td><?= h($college->degree_offered) ?></td>
				</tr>
				<tr>
					<th scope="row"><?= __('Email') ?></th>
					<td><?= h($college->email) ?></td>
				</tr>
				<tr>
					<th scope="row"><?= __('phone') ?></th>
					<td><?= h($college->email) ?></td>
				</tr>
			   
			</table>
	</div>
</div>

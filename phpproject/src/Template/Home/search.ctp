<div class="panel panel-default">
	<div class="panel-body innerAll">
        <?php echo $this->Form->create('Certificates') ; ?>
			<div class="form-group">
				<label>Aadhaar Number/Certificate Smart Contact Address</label>
				<?php echo $this->Form->input('adhar',array('label'=>false,'class'=>'form-control','placeholder'=>'Enter Aadhaar Number/Certificate Smart Contact Address','type'=>'text')) ?>
				
			</div>
			<?php echo $this->Form->button(__('Submit'),array('class'=>'btn btn-primary btn-block')); ?>
		<?php echo $this->Form->end() ; ?>
	</div>
</div>
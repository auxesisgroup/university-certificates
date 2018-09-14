<div class="panel panel-default">
	<div class="panel-body innerAll">
        <?php echo $this->Form->create('Certificates') ; ?>
			<div class="form-group">
				<label>Adhar Number/Certificate Smart Contact Address</label>
				<?php echo $this->Form->input('adhar',array('label'=>false,'class'=>'form-control','placeholder'=>'Enter Adhar Number/Certificate Smart Contact Address','type'=>'text')) ?>
				
			</div>
			<div class="form-group">
				<label>Otp</label>
				<?php echo $this->Form->input('otp',array('label'=>false,'class'=>'form-control','placeholder'=>'Enter Otp')) ?>
				
			</div>
			<?php echo $this->Form->button(__('Submit'),array('class'=>'btn btn-primary btn-block')); ?>
			
		<?php echo $this->Form->end() ; ?>
	</div>
</div>
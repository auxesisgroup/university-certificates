<div class="panel panel-default">
	<div class="panel-body innerAll">
        <?php echo $this->Form->create('Users') ; ?>
			<div class="form-group">
				<label for="Email">Username</label>
				<?php echo $this->Form->input('username',array('label'=>false,'class'=>'form-control','placeholder'=>'Enter Id','type'=>'text','id'=>'username')) ?>
				
			</div>
			<div class="form-group">
				<label for="Password">Password</label>
				<?php echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','placeholder'=>'Password','type'=>'password','id'=>'Password')) ?>
				
			</div>
			<?php echo $this->Form->button(__('Login'),array('class'=>'btn btn-primary btn-block')); ?>
			<!--<button type="submit" class="btn btn-primary btn-block">Login</button>-->
			<!-- <div class="checkbox">
				<label>
					<input type="checkbox"> Remember my details
				</label>
			</div> -->
		<?php echo $this->Form->end() ; ?>
	</div>
</div>
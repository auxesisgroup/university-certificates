<div class="row">
		<div class="col-md-12">
			<div class="widget row widget-inverse">
				<div class="widget-head">
					<h4 class="heading">Add College</h4>
				</div>
				<div class="widget-body">
					 <?php echo $this->Form->create($college,array('class'=>'form-horizontal'));?>
					   <?php
							$this->Form->templates([
								'inputContainer' => '{{content}}'
							]);
							 echo $this->Form->input('role_id',array('type'=>'hidden','value'=>'2'));
							 $newId =$totalcollege+1;
							 $collegeId = 'C000'.$newId ;
							 $publickey ='cD0f4B8aC1079E894394448880B'.$collegeId ;
					   ?>
					   <?php echo $this->Form->input('password',array('type'=>'hidden','value'=>'123456'))?>
						<div class="form-group">
							<label  class="col-sm-3 control-label">College Id</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('username',array('label'=>false,'class'=>'form-control','placeholder'=>'College Id','value'=>$collegeId))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">College Name</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('name',array('label'=>false,'class'=>'form-control','placeholder'=>'College name' ,'required'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Public key of college</label>
							<div class="col-sm-5">
							    <?php echo $this->Form->input('public_key',array('label'=>false,'class'=>'form-control publickey','placeholder'=>'Public key of college','readonly'))?>
							</div>
							<div class="col-sm-4"><button type="button"  class="btn btn-info generatekey">Generate Unique Blockchain Key</button></div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Degree offered</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('degree_offered[]',array('label'=>false,'class'=>'form-control','placeholder'=>'Degree offered','type'=>'select','options' => $degrees,'empty' => 'Select degree','multiple'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Email</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Phone</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('phone',array('label'=>false,'class'=>'form-control','placeholder'=>'Phone'))?>
							</div>
						</div>
						
						<!--<div class="form-group">
							<label  class="col-sm-3 control-label">Password</label>
							<div class="col-sm-9">
							    <?php // echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','placeholder'=>'Password'))?>
							</div>
						</div> -->
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div
	</div>	
</div>
<script>
      $(".generatekey").click(function(){ 
	           var autoNumber = "";
				var length =6 ;
				var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				for(var i = 0; i < length; i++) {
					autoNumber += possible.charAt(Math.floor(Math.random() * possible.length));
				}
				
	           var publickey = '0x'+autoNumber+"<?php echo $publickey ; ?>";
			   $('.publickey').val(publickey)   ;
	          
	  });
</script>
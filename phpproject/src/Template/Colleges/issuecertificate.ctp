
<div class="row">
		<div class="col-md-12">
			<div class="widget row widget-inverse">
				<div class="widget-head">
					<h4 class="heading">Issue certificate</h4>
				</div>
				<div class="widget-body">
				
					 <?php echo $this->Form->create($certificate,array('class'=>'form-horizontal','type'=>'file'));?>
					   <?php
							$this->Form->templates([
								'inputContainer' => '{{content}}'
							]);
							 $newId =$certificatecount+1;
							 $certificateId = 'S000'.$newId ;
							 $publickey ='cD0f4B8aC1079E894394448880B'.$certificateId ;
					   ?>
					    <div class="form-group">
							<label  class="col-sm-3 control-label">Student Id</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('student_id',array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Student Id','value'=>$certificateId,'readonly'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Student Name</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('student_name',array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Student Name','required'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Degree</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('degree',array('label'=>false,'class'=>'form-control','type'=>'select','options' => $degrees,'empty' => 'Select degree'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Aadhaar Number</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('adhar_number',array('label'=>false,'class'=>'form-control','placeholder'=>'Enter Adhar Number'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Mobile</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('mobile',array('label'=>false,'class'=>'form-control','placeholder'=>'Enter Mobile'))?>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-3 control-label">Student Public Key</label>
							<div class="col-sm-5">
							    <?php echo $this->Form->input('student_public_key',array('label'=>false,'class'=>'form-control publickey','placeholder'=>'Enter Student Public Key','readonly'))?>
							</div>
							<div class="col-sm-4"><button type="button"  class="btn btn-info generatekey">Generate Unique Blockchain Key</button></div>
						</div>
						
						<div class="form-group">
							<label  class="col-sm-3 control-label">Certificate</label>
							<div class="col-sm-9">
							    <?php echo $this->Form->input('certificate',array('label'=>false,'class'=>'form-control','type'=>'file','required'))?>
							</div>
						</div>
						
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
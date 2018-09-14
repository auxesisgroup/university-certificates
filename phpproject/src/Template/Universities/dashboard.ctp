<div class="row">
	<div class="col-md-4 col-sm-6">
		<div class="panel-3d">
			<div class="front">
				<div class="widget text-center">
					<div class="widget-body padding-none">
						<div>
							<div class="innerAll bg-info">
								<p class="lead strong margin-none text-white"><i class="icon-note-pad"></i><br/><?php echo $totalcollege ; ?> Colleges</p>
							</div>
							<div class="innerAll">
							    <?php echo $this->Html->link('View All Colleges',array('controller'=>'universities','action'=>'collegeslist'),array('class'=>'btn btn-success'))?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-6">
		<div class="panel-3d">
			<div class="front">
				<div class="widget text-center">
					<div class="widget-body padding-none">
						<div>
							<div class="innerAll bg-info">
								<p class="lead strong margin-none text-white"><i class="icon-note-pad"></i><br/><?php echo $requestcertificate ; ?> Verification Requests</p>
							</div>
							<div class="innerAll">
							    <?php echo $this->Html->link('View Verification Requests',array('controller'=>'certificates','action'=>'unapproved'),array('class'=>'btn btn-success'))?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-4 col-sm-6">
		<div class="panel-3d">
			<div class="front">
				<div class="widget text-center">
					<div class="widget-body padding-none">
						<div>
							<div class="innerAll bg-info">
								<p class="lead strong margin-none text-white"><i class="icon-note-pad"></i><br/><?php echo $deployecertificate ; ?> Deployed Certificates</p>
							</div>
							<div class="innerAll">
							    <?php echo $this->Html->link('View Deployed Certificates',array('controller'=>'certificates','action'=>'deployed'),array('class'=>'btn btn-success')) ?>
							</div>
						</div>
					</div>
				</div>

			</div>
			
		</div>
	</div>
	
</div>
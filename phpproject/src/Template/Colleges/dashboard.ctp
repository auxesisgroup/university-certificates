<div class="row">
	
	<div class="col-md-4 col-sm-6">
		<div class="panel-3d">
			<div class="front">
				<div class="widget text-center">
					<div class="widget-body padding-none">
						<div>
							<div class="innerAll bg-info">
								<p class="lead strong margin-none text-white"><i class="icon-note-pad"></i><br/><?php echo $totalcertificate ; ?> Total Certificate</p>
							</div>
							<div class="innerAll">
							    <?php echo $this->Html->link('View All Certificate',array('controller'=>'certificates','action'=>'lists'),array('class'=>'btn btn-success'))?>
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
								<p class="lead strong margin-none text-white"><i class="icon-note-pad"></i><br/><?php echo $requestcertificate ; ?> Request Certificate</p>
							</div>
							<div class="innerAll">
							    <?php echo $this->Html->link('View Verification Request',array('controller'=>'certificates','action'=>'unapproved'),array('class'=>'btn btn-success')) ?>
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
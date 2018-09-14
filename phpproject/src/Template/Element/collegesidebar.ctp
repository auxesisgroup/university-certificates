<div id="menu" class="hidden-print hidden-xs">
	<div class="sidebar sidebar-inverse">
		<div class="sidebarMenuWrapper">
			<ul class="list-unstyled">
					<li><?php echo $this->Html->link('<i class="icon-projector-screen-line"></i><span>Dashboard</span>',array('controller'=>'colleges','action'=>'dashboard'),array('escape'=>false)) ; ?></li>
					<li><?php echo $this->Html->link('<i class="icon-projector-screen-line"></i><span>View All Certificates</span>',array('controller'=>'certificates','action'=>'lists'),array('escape'=>false)) ; ?></li>
					<li><?php echo $this->Html->link('<i class="icon-projector-screen-line"></i><span>Issue Certificate</span>',array('controller'=>'colleges','action'=>'issuecertificate'),array('escape'=>false)) ; ?></li>
					<li><?php echo $this->Html->link('<i class="icon-projector-screen-line"></i><span>Verification Request</span>',array('controller'=>'certificates','action'=>'unapproved'),array('escape'=>false)) ; ?></li>
					<li><?php echo $this->Html->link('<i class="icon-projector-screen-line"></i><span>Deployed Certificates</span>',array('controller'=>'certificates','action'=>'deployed'),array('escape'=>false)) ; ?></li>
					
			</ul>
		</div>
	</div>
</div>
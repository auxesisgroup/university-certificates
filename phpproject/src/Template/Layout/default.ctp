<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php  if(!empty(@$ogdata)){ 
			   if(!empty(@$ogdata['imgName'])){ 
	?>		   
	<meta property="og:image" content="<?php echo $ogdata['imgUrl'] ; ?>"/>	   
	<?php } } else { ?>
	<meta property="og:image" content="https://www.spoturlook.com/administrator/img/logoheader.png"/>	
	<?php } ?>
   
    <meta property="og:description" content="Get Spotted"/>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Spoturlook</title>

    <!-- Bootstrap -->
	<?php echo $this->Html->css(array('bootstrap','main')) ; ?>
	 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php echo $this->Html->script('bootstrap.min') ; ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background-image: url('../img/background1.png');">
 
   <?php echo $this->element('header');?>
   
	<?php $action = $this->request->params['action'] ; ?>
 
  <section>
    
            <?php // echo $this->Flash->render() ?>
            <?php echo $this->fetch('content') ?>
    
  </section>
    
	<?php echo $this->element('footer');?>
 
   
    
  </body>
</html>
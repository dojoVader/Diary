
  <div class="col-lg-8 col-lg-offset-2">
	<h1><?php echo $title?></h1><hr/>

					<p><bd><?php echo date("M d,Y",strtotime($date)); ?></bd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open"></i> Category</p>

					<p>
					<div>
					<?php echo $content?>
					</div>
					</p>
	<?php echo ipSlot("AddThis"); ?>				
	<!--Comment-->
	<div class="col-md-12 col-lg-12">
		<?php echo $comments;?>
	</div>					
	<!-- Form -->
	<div class="col-md-12 col-lg-12">
		<!-- Form Comment Template -->
		<?php echo $form->render();?>
	</div>
	</div>


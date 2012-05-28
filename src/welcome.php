
		<div class="info">
			<h3>WELCOME!</h3>
		 
			<p>Thank you for participating in our experiment.</p>
			
			<p>
				Over next couple of minutes you will see a number of images. 
				Each image will be acompanied with a question asking you to identify some objects - please be sure to read the question before giving any answer.
				The whole experiment should take about 15-20 minutes.
			</p>
			
			<p>You will answer by clicking on the objects, and typing short (preferably 1 word) label that clearly identifies the object.
			Please stick to the following rules:
				<ul>
					<li>Click only visible parts of the objects (do not click on the areas that are occluded by different objects)</li>
					<li>Try to click in the centre of the object</li>
					<li>Click the objects in order you believe best answer the question (e.g. if you are asked to select 3 largers objects, do so in such a way that you click biggest one first, followed by second and third largest)</li>
					<li>You have to select at least one object in the image. You may not use all the available clicks, but please do it only if you really believe that doing so is justified.</li>
				</ul>
			</p>
		 
			<p>Now please press "next" to begin...</p> 
		 
		 </div>
		<form method="POST" action="" id="startForm">
			<input type="hidden" name="step" value="1"/>
			<input type="hidden" name="hash" value="<?php echo makeHash('')?>" />
			<input type="hidden" name="screen" id="screen" value="" />
			
			<input type="button" class="button" id="beginButton" value="begin!" />
		</form>
		<script>
		 $('#beginButton').bind('click', function(event) {
		 		 $('#screen').val(screen.width + "," + screen.height + ',' + screen.availWidth + ',' + screen.availHeight);
		 		 startForm.submit();
		 }) ;
		 </script>

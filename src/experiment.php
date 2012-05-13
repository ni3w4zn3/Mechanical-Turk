<?php

$criticalQuestions = array(
	'What are 3 most eye-catching objects?',
	'What are 3 most salient objects?',
	'What are 3 most interesting objects?',	
	'What are 3 most important objects?'
);

$fillerQuestions = array(
	'What are 3 largest objects?',
	'What are 3 most colorful objects?',
	'What are 3 objects in the centre of the image?'	
);

$step = intval($_REQUEST['step']);



$data = array();
if ($step == 1) {
	$data['condition'] = rand() % count($criticalQuestions);
	$pivot = rand() % count($criticalQuestions);
	
	$f = fopen(IMAGE_LIST, 'r');
	$i = 0;
	while($line = trim(fgets($f))) {
		if ($i++ % count($criticalQuestions) == $pivot )
			$data['images'][$line] = rand();
		else 
			$data['fillers'][$line] = rand();
	}
	fclose($f);
	asort($data['images']);
	asort($data['fillers']);
	$data['images'] = array_keys(array_slice($data['images'], 0, floor(MAX_IMAGES/2)));
	$data['fillers'] = array_keys(array_slice($data['fillers'], 0,count($data['images'])));
	
	$data['trials'] = array();
	
} else {
	$data = unserialize($_REQUEST['data']);
	$data['trials'][$_REQUEST['currentImage']] = array('coords' => $_REQUEST['coords'], 'question' => $_REQUEST['question']);
}

if (!count($data['images']) && !count($data['fillers'])) {
	$f = fopen(BASE_DIR.OUT_DIR.'/' . $data['condition']. '_' .time(), 'w');
	
	foreach($data['trials'] as $img => $info) {
		fwrite($f, '['.$img.']:[' . $info['question'].']:['.$info['coords']."]\n");
	}
	fclose($f);
	
	include('src/thanks.php');
	return;
}


if (count($data['fillers']) && ((count($data['fillers']) + count($data['images'])) % 2 == 0 || !count($data['images']))) {
	$question = $fillerQuestions[rand() % count($fillerQuestions)];
	$image = array_pop($data['fillers']);
} else {
	$question = $criticalQuestions[$data['condition']];
	$image = array_pop($data['images']);
}

$dataString = serialize($data);
$hash = makeHash($dataString);

?>

		<div class="info">image <?php echo $step ?> out of <?php echo ($step + count($data['fillers']) + count($data['images'])) ?></div>
		<div class="error">
			<h2><?php echo $question?></h2><br>
			You have <span id="selectionsLeft"><?php echo MAX_SELECTIONS; ?></span> clicks left<br>
		</div>
		<div id="stimuli" width="800" height="600" style="background: url('<?php echo IMAGE_DIR.'/'.$image?>') #ccc no-repeat;width:800px;height:600px">&nbsp;</div>
		
		<form id="dataForm" action="index.php?" method="post">
			<input type="hidden" name="data" value="<?php echo base64_encode($dataString)?>">
			<input type="hidden" name="hash" value="<?php echo $hash?>">
			<input type="hidden" name="step" value="<?php echo ($step+1)?>">
			<input type="hidden" name="currentImage" value="<?php echo ($image)?>">
			<input type="hidden" name="coords" value="">
			<input type="hidden" name="question" value="<?php echo ($question)?>">
			<input type="button" name="next" value="next Image"  class="button">
		</form>
		
		<script>
			var maxSelected = <?php echo MAX_SELECTIONS?>;
			var startTIme = 0;
			
			$(document).ready(function() {
					startTime = (new Date()).getTime();
			});
			
			$('input[name=next]').bind('click', function(event) {
				if (maxSelected == 0) {
					$('#dataForm').submit();
				} else if (maxSelected == 3) {
					alert('You have to select at least 1 object!');
				} else {
					if (confirm('Are you sure you want to proceeed without selecting 3 objects?')) {
						$('#dataForm').submit();
					}
				}
			});
			
			$('#stimuli').bind('click', function( event ){
					var time = (new Date()).getTime();
					
					var d = $('#stimuli');
					
					var x = (event.pageX - d.offset().left);
					var y = (event.pageY - d.offset().top);
					
					if(maxSelected <= 0) {
						alert('You can select only <?php echo MAX_SELECTIONS?> objects!');
						return;
					}
					
					var label = prompt('Type the object\'s name');
					if (!label) return;
					
					maxSelected--;
					$('#selectionsLeft').html(maxSelected);
					
					$('input[name=next]').removeAttr('disabled');
					
					time = time - startTime;
					
					var prevCoords = $('input[name=coords]').val();
					var coords = (x+','+y+','+label + ',' + time);
					$('input[name=coords]').val(prevCoords + coords + ';');
					
					var labelBox = document.createElement('div')
					labelBox.style.left = (event.pageX-5)+'px';
					labelBox.style.top = (event.pageY-5)+'px';
					labelBox.style.position = 'absolute';
					labelBox.style.width= '100px';
					
					labelBox.innerHTML = '<div style="width:10px; height:10px;background-color:red" class="dot">&nbsp;</div>' + label;
				
					d.append(labelBox);
			});
		</script>
	

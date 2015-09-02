<html>
<head>
<title>Text Viewer</title>
</head>
<link rel="stylesheet" href="text_viewer.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="3rdparty/spinning/spin.min.js"></script>
<link type="text/css" rel="stylesheet" href="jquery-plugins/layout-default.css" />
<script type="text/javascript" src="jquery-plugins/jquery-1.9.js"></script>
<script type="text/javascript" src="jquery-plugins/jquery-ui-1.9.2.js"></script>
<script type="text/javascript" src="jquery-plugins/jquery.layout-1.4.4.js"></script>
<body>

<?php
#The target text file's content
$content = "";
$filePath = $_GET['filePath'];
?>
<style type="text/css">
	html, body {
		width:		100%;
		height:		100%;					
		padding:	0;
		margin:		0;
		overflow:	auto; /* when page gets too small */
	}
	#container {
		height:		100%;
		margin:		0 auto;
		width:		100%;
		min-width:	700px;
		_width:		700px; /* min-width for IE6 */
	}
	.pane {
		display:	none; /* will appear when layout inits */
	}
</style>
<script type="text/javascript">
var content = "";
$(document).ready(function() {
	readFile();
	$('#button-chunk').click(function() {
		call_nltk_chunk();
	});
	$('#button-highlight').click(function() {
		call_nltk_highlight();
	});
});

function call_nltk_chunk() {
	$("#text-content").html("");
	$("#result-content").html("");
	var mode = $('input[name="chunk_mode"]:checked').val();
	var target = document.getElementById('text-content')
	var spinner = new Spinner().spin(target);
	$.ajax({
		type: "POST",
		url: "/cgi-bin/nltk_helper.py",
		data: {
			helper_content: content,
			helper_mode: mode
		}
	}).done(function( result ) {
		if(result !== null) {
			var chunked_result = JSON.parse(result);
			tokens = chunked_result[0] 
			chunks = chunked_result[1]
			spinner.stop();
			for (i = 0; i < tokens.length; i++) {
				if (chunks.indexOf(tokens[i])==-1){
					$("#text-content").append('&nbsp;<span style="border: 2px solid blue; border-radius: 5px;"> ' + tokens[i] + ' </span>&nbsp;');
				}
				else 
					$("#text-content").append('&nbsp;<span style="border: 2px solid red; border-radius: 5px;"> ' + tokens[i] + ' </span>&nbsp;');
			}
		}
	});
}

function call_nltk_highlight() {
	$("#text-content").html("");
	$("#result-content").html("");
	var mode = $('input[name="chunk_mode"]:checked').val();
	var target = document.getElementById('text-content')
	var spinner = new Spinner().spin(target);
	$.ajax({
		type: "POST",
		url: "/cgi-bin/nltk_helper.py",
		data: {
			helper_content: content,
			helper_mode: mode
		}
	}).done(function( result ) {
		if(result !== null) {
			var chunked_result = JSON.parse(result);
			tokens = chunked_result[0] 
			chunks = chunked_result[1]
			spinner.stop();
			for (i = 0; i < tokens.length; i++) {
				if (chunks.indexOf(tokens[i])==-1){
					$("#text-content").append('<span> ' + tokens[i] + ' </span>');
				}
				else 
					$("#text-content").append('&nbsp;<span style="border: 2px solid red; border-radius: 5px;"> ' + tokens[i] + ' </span>&nbsp;');
			}
		}
	});
}

function readFile() {
	$('#container').layout();

	var filePath = "<?php echo $filePath; ?>";
	var txtFile = new XMLHttpRequest();
	txtFile.open("GET", filePath, true);
	txtFile.onreadystatechange = function()
	{
	  	if (txtFile.readyState === 4) {  // document is ready to parse.
	    	if (txtFile.status === 200) {  // file is found
	    		content = txtFile.responseText;
				$("#text-content").append(content);
				lines = txtFile.responseText.split("\n");
			}
		}
	}
	txtFile.send(null);
}
</script>

<div id="container">
	<div class="pane ui-layout-center" style="background-color: gainsboro; width: 100%; height: 100%; resize: vertical; line-height: 3em; overflow: auto; padding: 5px;">
			<font size="4"><p class="big" id="text-content"></p></font>
	</div>
	<div class="pane ui-layout-south">
			
		<div align="center">
			<label style="background-color: #8AC007; display: block; color: white;" for="mode-selector">Choose Any Option Below</label>
			<table id="mode-selector">
				<tr>
					<td><label class="selectableLabel" for="words"><input type="radio" name="chunk_mode" id="words" value="1" />Words</td></label>
					<td><label class="selectableLabel" for="stopwords"><input type="radio" name="chunk_mode"
							id="stopwords" value="2" />Stopwords</label></td>
					<td><label class="selectableLabel" for="entities"><input type="radio" name="chunk_mode"
							id="entities" value="3" />Entities</label></td>
					<td><label class="selectableLabel" for="noun_phrases"><input type="radio" name="chunk_mode"
							id="noun_phrases" value="4" />Noun Phrases</label></td>
					<td><label class="selectableLabel" for="verb_phrases"><input type="radio" name="chunk_mode"
							id="verb_phrases" value="5" />Verb Phrases</label></td>
					<td><label class="selectableLabel" for="sentences"><input type="radio" name="chunk_mode"
							id="sentences" value="6" />Sentences</label></td>
				</tr>
			</table>
		</div>
		<div align="center">
			<label style="background-color: #8AC007; display: block; color: white;">Click Any Button Below</label>
			<a class="button" id="button-chunk"><span>Chunks</span></a> 
			<a class="button" id="button-highlight"><span>Highlight</span></a>
		</div>
	</div>
</div>
	
</body>
</html>
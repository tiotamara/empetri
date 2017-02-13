<!DOCTYPE html>
<html charset="utf-8">
<head>
	<title>EmPeTri</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Joseph Moore">
	<!-- Date: 2011-04-08 -->
	<link rel="stylesheet" href="mp3player/player/css/styles.css" type="text/css" media="all" />
	<!-- <link rel="stylesheet" href="mp3player/player/css/blackandwhite.css" type="text/css" media="all" /> -->
	<script type="text/javascript" src="dist/js/jquery.min.js"></script> 
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<script src="mp3player/player/mp3playerplugin.js"></script> 
</head>
<style>
body{
	background-color:#ba235a;
	color:#fff;
}
</style>
<body>
<?php
	$dirs = glob('mp3player/music' . '/*' , GLOB_ONLYDIR);
	print_r( $dirs);
	$dirname = @$_POST["folder"];
	if (isset($dirname)) {
		$filename = "mp3player/music/" . $dirname . "/";

		if (!file_exists($filename)) {
			$oldmask = umask(0);
		    mkdir("mp3player/music/" . $dirname, 0777);
			umask($oldmask);
		    echo "The directory $dirname was successfully created.";
		} else {
		    echo "The directory $dirname exists.";
		}
	}
?>
<h1>Abal-abal player</h1>

<div class="content">
	<p></p>
</div>
<center>
	<select id="nama-playlist">
		<option value="mp3player/music">All</option>
		<?php foreach ($dirs as $data): ?>
			<option value="<?php echo $data; ?>"><?php echo explode('/', $data)[2]; ?></option>
		<?php endforeach ?>
	</select>
</center>
<script>
	$("#nama-playlist").change(function() {
		$("#mp3Player").attr("data-folder", $("#nama-playlist").val());
		    function reload_js(src) {
		        $('script[src="' + src + '"]').remove();
		        $('<script>').attr('src', src).appendTo('head');
		    }
		    reload_js('mp3player/player/mp3playerplugin.js');
	})
</script>
<div id="mp3Player" data-folder="mp3player/music"></div>
<center>
	<?php $dirs = glob('mp3player/music' . '/*' , GLOB_ONLYDIR); ?>
<form method="post" id="upload" action="" enctype="multipart/form-data">
	<select id="playlist" name="playlist">
		<?php foreach ($dirs as $data): ?>
			<option value="<?php echo $data; ?>"><?php echo explode('/', $data)[2]; ?></option>
		<?php endforeach ?>
	</select>
	<input type="file" name="music">
	<button type="submit">Upload</button>
</form>
	<button id="delete" type="button">Delete</button>
<form method="post" action="">
	<input type="text" name="folder" id="nama_folder" placeholder="Nama Playlist" autocomplete="off" required>
	<button type="submit">Buat</button>
</form>
</center>

</body>
<script>
	$(function() {
		$("#delete").click(function() {
				// alert($('table#mp3Player-table tr:last').index() + 1);
			var SearchFieldsTable = $("#mp3Player-table tbody");

			var trows = SearchFieldsTable.children("tr");

			$.each(trows, function (index, row) {
			    var ColumnName=$(row).attr("class");
			    if (ColumnName) {
			    	// alert($(row).attr("data-file"));
			    	$.ajax({
			    	    url: 'proses.php?delete',
			    	    type: 'POST',
			    	    data: {nama_file:$(row).attr("data-file"),path:'/'+$("#nama-playlist").val()+'/'},
			    	    success: function(x){
			    	    	location.reload();
			    	    },
			    	});
			    }
			});
		})

		$('#upload').on('submit', uploadFiles);

		// Catch the form submit and upload the files
		function uploadFiles(event)
		{
		  event.stopPropagation(); // Stop stuff happening
		    event.preventDefault(); // Totally stop stuff happening

		    // START A LOADING SPINNER HERE

		    // Create a formdata object and add the files
		    var formData = new FormData($('form')[0]);
		    $.ajax({
		        url: 'proses.php?files',
		        type: 'POST',
		        xhr: function() {  // Custom XMLHttpRequest
		        	var myXhr = $.ajaxSettings.xhr();
		            if(myXhr.upload){ // Check if upload property exists
		                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
		            }
		            return myXhr;
		        },
		        success: function(x){
		        	console.log(x);
		        },
		        data: formData,
		        cache: false,
		        dataType: 'json',
		        processData: false, // Don't process the files
		        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		        
		    });
		}
		function progressHandlingFunction(e){
			if(e.lengthComputable){
				$('progress').attr({value:e.loaded,max:e.total});
			}
		}
	})
</script>
</html>
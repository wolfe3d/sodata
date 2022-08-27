///////////////////
///Slides for Home
//////////////////
function slideAdd(slideOrder)
{
	var request = $.ajax({
		url: $("#addTo").attr('action'),
		cache: false,
		method: "POST",
		data: $("#addTo").serialize(),
		dataType: "text"
	});

	request.done(function( html ) {
		var slideID = parseInt(html);
		if(!isNaN(slideID)) //checks to see if a number is returned
		{
			$("#note").html("<div class='text-success'>Added new slide.</div>"); //add note to show modification
			$("#slideList").append("<div id='slide-"+slideID+"'></div>"); //add note to show modification
			$("#slide-"+slideID+"").append("<div id='order-"+slideID+"'>"+slideOrder+"</div>"); //add note to show modification
			$("#slide-"+slideID+"").append("<div id='image-"+slideID+"'>test</div>"); //add note to show modification
			$("#slide-"+slideID+"").append("<div id='text-"+slideID+"'></div>"); //add note to show modification

		}
		else {
			$("#note").html("<div class='text-warning'>Change Error:"+html+"</div");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div");
	});
}
function slidePreviewImage(slideID)
{
	if ($("#image-"+slideID)[0].files && $("#image-"+slideID)[0].files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#slide-image-"+slideID).attr('src', e.target.result);
		}

		reader.readAsDataURL($("#image-"+slideID)[0].files[0]);
		$('#saveSlideButton-'+slideID).show();
	}
}


function slideSave(slideID)
{
	var fd = new FormData();
	var files = $("#image-"+slideID)[0].files;
	// Check file selected or not
	if(files.length > 0 ){
		fd.append('file',files[0]);
		fd.append('slideID',slideID);

		var request = $.ajax({
			url: 'slideimageupload.php',
			cache: false,
			method: "POST",
			data: fd,
			contentType: false,
			processData: false,
		});

		request.done(function( html ) {
			if(html)
			{
				$("#slide-image-"+slideID).attr('src', html);
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div");
		});
	}

	slideSaveText();
}
var slideIDE = "" ; //the editing slide ID
function loadSummerNoteButtons()
{
	//The below code causes a bootstrap error, but is necessary for dropdowns in summernote to work.
	let buttons = $('.note-editor button[data-toggle="dropdown"]');
	buttons.each((key, value)=>{
		$(value).attr('data-bs-toggle', 'dropdown'); //TODO:  Attempt to remove this in later summernote versions
		//$(value).removeAttr('data-toggle');
	})
}
var editText = function(slideID) {
	$('#html-'+slideID).summernote({focus: true});
	loadSummerNoteButtons();
	slideIDE = slideID;
	$('#previewSlideButton-'+slideIDE).show();
};

var previewText = function() {
	var markup = $('#html-'+slideIDE).summernote('code');
	$('#slideText-'+slideIDE).html(markup);
	$('#saveSlideButton-'+slideIDE).show();
};

var saveText = function() {
	if($('#html-'+slideIDE).is(':hidden')) //original html is hidden while editing
	{
		var markup = $('#html-'+slideIDE).summernote('code');

		var request = $.ajax({
			url: 'slidetextupdate.php',
			cache: false,
			method: "POST",
			data: {slideID:slideIDE, slideText:markup},
			dataType: "text"
		});

		request.done(function( html ) {
			if(html=="1")
			{
				$('#html-'+slideIDE).summernote('destroy');
				$('#slideText-'+slideIDE).html(markup);
			}
			else {
				$('#html-'+slideIDE).html(html);
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div");
		});
	}
};

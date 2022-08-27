///////////////////
///Slides for Home
//////////////////
$().ready(function() {
	editText();
});

function loadSummerNoteButtons()
{
	//The below code causes a bootstrap error, but is necessary for dropdowns in summernote to work.
	let buttons = $('.note-editor button[data-toggle="dropdown"]');
	buttons.each((key, value)=>{
		$(value).attr('data-bs-toggle', 'dropdown');
	})
}
var editText = function() {
	$('#news').summernote({focus: true});
	loadSummerNoteButtons();
	$('#saveButton').show();
	$('#editButton').hide();
};

var saveText = function(newsID) {
		var value = $('#news').summernote('code');
		saveNews(newsID,'news','news',value,'note');
};

function saveNews(myID,table,field,value,messageID)
{
		//submit changes
		var request = $.ajax({
			url: "fieldupdate.php",
			cache: false,
			method: "POST",
			data: { myid: myID, mytable:table, myfield : field, myvalue : value },
			dataType: "text"
		});

		request.done(function( html ) {
			//$("label[for='" + field + "']").append(html);
			$(".text-success").remove(); //removes any old update notices
			if(html=="1")
			{
				$("#"+messageID).html("<span class='text-success'>*News Updated</span>"); //returns the current update
			}
			else
			{
			$("#"+messageID).html("<span class='text-warning'>"+ html +"</span>"); //returns the current update
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#"+messageID).html("<span class='text-danger'>"+ textStatus +"</span>"); //returns the current update
		});
}

///////////////////
///Slides for Home
//////////////////
$().ready(function() {
	editText();
});

var editText = function() {
	$('#news').summernote({focus: true});
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

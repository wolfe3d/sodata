//change year to add to
function eventyearPreparePage(myYear)
{
	$("#mainHeader").html("Edit a Year's Events");
	window.location.hash = '#eventyear-edit-'+ myYear;
}
function eventYearRemove(myID)
{
	if(confirm("Are you sure you want to delete the eventyear: " + $("#eventyear-"+myID+" .event").text() +"?  This removes the event permanently from this year!!!"))
	{
		rowRemove(myID,"eventyear");
	}
}

$("#year").change(function(){
	eventyearPreparePage($( "#year" ).val());
});
$("#addLeader").hide();
$("#eventID").hide();
$( "#addTo" ).submit(function( event ) {
	event.preventDefault();
	var request = $.ajax({
		url: $("#addTo").attr('action'),
		cache: false,
		method: "POST",
		data: $("#addTo").serialize(),
		dataType: "html"
	});

	request.done(function( html ) {
		$(".text-success").remove(); //removes any old update notices
		if(html>0)
		{
			//add event to list
			$("#eventsforyear").append("<li id='eventyear-"+$("#year").val()+"'><span class='event'><strong>"+$("#eventsList option:selected" ).text()+"</strong></span> <button class='btn btn-danger btn-sm' type='button' onclick='eventYearRemove("+html+")'><span class='bi bi-trash'></span> Remove</button></li>");
		}
		else
		{
			$("#addTo").append("<div class='text-danger'>"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#mainContainer").html("Add event to year error");
	});
});

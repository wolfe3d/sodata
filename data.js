$().ready(function() {
//wait for the page to load before the following is run
	checkPage();
	$(window).on('hashchange', function() {
		var splitHash = location.hash.substr(1).split("-");
		if(splitHash[1])
		{
			//alert (splitHash[1]);
			//ignore hashes with dashes that are used for edit and other single pages
			//TODO: Maybe add special functions for some edit pages later.
		}
		else
		{
				checkPage();
		}
	});
	setTimeout(function() { loadpage("user") }, 3500000); //TODO: Update this as long as user is active
});
function checkPage(){
	var myHash = location.hash.substr(1);
	$("section:not(#banner)").hide();
	if(myHash)
	{
		loadpage(myHash);
		$("#mainHeader").html(myHash);
	}
	else
	{
		loadpage("user");
		$( "#main" ).show( "slow", function() {	});
	}
}
function loadpage(myUrl)
{
	//$( "#main" ).hide( "fast", function() {	});
	if (myUrl)
	{
		var request = $.ajax({
		 url: myUrl +".php",
		 cache: false,
		 method: "POST",
		 dataType: "html"
		});
		request.done(function( html ) {
		 //$("label[for='" + field + "']").append(html);
		 $("#mainContainer").html(html);
		 switch (myUrl) {
               case 'students': prepareStudentsPage();
               break;

               case 'events': prepareEventsPage();
               break;

               case 'tournaments': prepareTournamentsPage();
               break;

               default:
            }
		 $( "#main").show( "slow", function() {
			});
		});

		request.fail(function( jqXHR, textStatus ) {
		 $("#mainContainer").html("Error");
		});
	}
}

function getList(myPage, myData)
{
	//alert(JSON.stringify(myData) );
	//myData is a json object type
	var request = $.ajax({
	 url: myPage,
	 cache: false,
	 method: "POST",
	 data: myData,
	 dataType: "html"
	});
	request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 $("#list").html(html);
	});

	request.fail(function( jqXHR, textStatus ) {
	 $("#list").html("Search Error");
	});
}


function prepareStudentsPage()
{
	$("#addTo").hide();
	$("#searchDiv").hide();
	//Load Students
	getList("studentslist.php",{active: +$("#active").is(':checked')});
		// validate signup form on keyup and submit
	$("#addTo").validate({
		rules: {
			first: "required",
			last: "required",
			yearGraduating: "required",
			email: {
				required: true,
				email: true
			},
		},
		messages: {
			first: "*Please enter the student\'s first name",
			last: "*Please enter the student\'s last name",
			yearGraduating: {
				required: "*Enter the year the student is graduating",
			},
			email: {
				required: "*Enter the student\'s email.",
			},
		},
		submitHandler: function(form) {
							form.submit();
					}
	});

	//if the active checkbox is changed, then the screen will repopulate with the entire science olympiad population.  It does not remember the last clicked search.
	$('#active').change(function() {
			getList("studentslist.php", {active: +$("#active").is(':checked')});
	});
	//when Find by Name is clicked, this initiates the search
	$("#findStudent").on( "submit", function( event ) {
		event.preventDefault();
		getList("studentslist.php", $( this ).serialize() );
	});
	//when Find by Event is clicked, this initiates the search
	$("#findByEvent").on( "submit", function( event ) {
		event.preventDefault();
		getList("studentslist.php", {eventsList: $("#eventsList").val()});
	});
	//when Find by Course is clicked, this initiates the search
	$("#findByCourse").on( "submit", function( event ) {
		event.preventDefault();
		getList("studentslist.php", {courseList: $("#courseList").val()});
	});
}

function studentRemove(myStudentID)
{
	//alert(JSON.stringify(myData) );
	//myData is a json object type

	var request = $.ajax({
	 url: "studentremove.php",
	 cache: false,
	 method: "POST",
	 data: {studentID:myStudentID},
	 dataType: "html"
	});
	request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 $("#list").html(html);
	});

	request.fail(function( jqXHR, textStatus ) {
	 $("#list").html("Removal Error");
	});
}



///////////////////
///Student Edit functions
//////////////////
function studentEdit(myStudentID)
{
	var request = $.ajax({
	 url: "studentedit.php",
	 cache: false,
	 method: "POST",
	 data: {studentID:myStudentID},
	 dataType: "html"
	});
	request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 window.location.hash = '#student-edit-'+ myStudentID;
	 $("#mainContainer").html(html);
	 $("#eventAndPriority").hide();
	 $("#courseListDiv").hide();
	});

	request.fail(function( jqXHR, textStatus ) {
	 $("#mainContainer").html("Removal Error");
	});
}
function loadEventsList()
{
	//maybe load events asynchronous
}
function addEventChoice(student)
{
	//adds the event choice selection
 $("#eventAndPriority").clone().appendTo("#addEventsDiv").show();
 $("#addEventsDiv").append("<a id='addThisEvent' href='javascript:addEvent("+student+",this.id,this.value);'>Add</a>");
}
function removeEvent(value)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studenteventremove.php",
	 cache: false,
	 method: "POST",
	 data: { eventsChoiceID: value},
	 dataType: "text"
 });

 request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 $(".modified").remove(); //removes any old update notices
	 if (html=="1")
	 {
		 //returns the current update
		 $("#eventChoice-"+value).remove();
		 $("#events").append("<span class='modified' style='color:blue'>Event removed.</span>");
	 }
	 else
	 {
		 $("#events").append("<span class='modified' style='color:red'>Error while attempting to remove an event. Please, report details to site admin.</span>");
	 }
 });

 request.fail(function( jqXHR, textStatus ) {
	 alert( "Request failed: " + textStatus );
 });
}
function addEvent(student, field, value)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studenteventadd.php",
	 cache: false,
	 method: "POST",
	 data: { studentID: student, eventID : $("#eventsList").val(), priority : $("#priorityList").val() }, //TODO: must add priority
	 dataType: "text"
 });

 request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 $(".modified").remove(); //removes any old update notices
	 var eventID = parseInt(html);
	 if (eventID>0)
	 {
		 //returns the current update
		 $("#events").append("<div id='eventChoice-" + eventID + "'>"+ $("#eventsList option:selected").text() + " <a href='javacript:removeEvent(" + eventID + ");'>Remove</a> <span class='modified' style='color:blue'>Event added.</span></div>");
	 }
	 else
	 {
		 $("#events").append("<span class='modified' style='color:red'>Error while attempting to add an event. Please, report details to site admin.</span>");
	 }
 });

 request.fail(function( jqXHR, textStatus ) {
	 alert( "Request failed: " + textStatus );
 });
}
function courseAddChoice(student, table)
{
	//adds the course list selection
 $("#courseListDiv").appendTo("#add"+table+"Div").show();
 $(".addCourseBtn").show();
 $("#add"+table).hide();
 $("#addThisCourse").remove();
 $("#add"+table+"Div").append("<a id='addThisCourse' href=\"javascript:courseAdd('"+student+"','"+table+"')\">Add</a>");
}
function courseRemove(value, table)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studentcourseremove.php",
	 cache: false,
	 method: "POST",
	 data: { tableName : table, myID : value},
	 dataType: "text"
 });

 request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 $(".modified").remove(); //removes any old update notices
	 if (html=="1")
	 {
		 //returns the current update
		 $("#"+table+"-"+value).remove();
		 $("#"+table).append("<span class='modified' style='color:blue'>Course removed.</span>");
	 }
	 else
	 {
		 $("#"+table).append("<span class='modified' style='color:red'>Error while attempting to remove a course. Please, report details to site admin.</span>");
	 }
 });

 request.fail(function( jqXHR, textStatus ) {
	 alert( "Request failed: " + textStatus );
 });
}
function courseAdd(student, table)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studentcourseadd.php",
	 cache: false,
	 method: "POST",
	 data: { studentID: student, tableName : table, courseID : $("#courseList").val() }, //TODO: must add priority
	 dataType: "text"
 });

 request.done(function( html ) {
	 $(".modified").remove(); //removes any old update notices
	 var myCourseID = parseInt(html);
	 if (myCourseID>0)
	 {
		 //returns the current update
		 $("#" + table).append("<div id='" + table + "-" + myCourseID + "'>"+ $("#courseList option:selected").text() + " <a href=\"javascript:courseCompleted('" + myCourseID + "','" + $("#courseList option:selected").text() +"')\">Completed</a>  <a href=\"javascript:courseRemove('" + myCourseID + "','"+table+"')\">Remove</a> <span class='modified' style='color:blue'>Course added.</span></div>");
	 }
	 else
	 {
		 $("#"+ table).append("<span class='modified' style='color:red'>Error while attempting to add a course. Please, report details to site admin.</span>");
	 }
 });

 request.fail(function( jqXHR, textStatus ) {
	 alert( "Request failed: " + textStatus );
 });
}


function courseCompleted(value, courseName)
{
	//todo
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studentcoursecompleted.php",
	 cache: false,
	 method: "POST",
	 data: { myID: value}, //TODO: must add priority
	 dataType: "text"
 });

 request.done(function( text ) {
	 $(".modified").remove(); //removes any old update notices
	 var myCourseID = parseInt(text);
	 if (myCourseID>0)
	 {
		 //returns the current update
		 var table = "coursecompleted";
		 var table2 = "courseenrolled";
		 $("#" + table).append("<div id='" + table + "-" + myCourseID + "'>"+ courseName + " <a href=\"javascript:courseRemove('" + myCourseID + "','"+table+"')\">Remove</a> <span class='modified' style='color:blue'>Course added.</span></div>");
		 $("#"+table2+"-"+value).remove();
		 $("#"+table2).append("<span class='modified' style='color:blue'>Course removed.</span>");
	  }
	 else
	 {
		 var table = "courseenrolled";
		 $("#"+ table).append("<span class='modified' style='color:red'>Error while attempting to move a course. Please, report details to site admin.</span>");
	 }
 });

 request.fail(function( jqXHR, textStatus ) {
	 alert( "Request failed: " + textStatus );
 });
}

function studentUpdate(myID,table,field,value)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studentupdate.php",
	 cache: false,
	 method: "POST",
	 data: { myid: myID, mytable:table, myfield : field, myvalue : value },
	 dataType: "text"
 });

 request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 $(".modified").remove(); //removes any old update notices
	 $("#"+field).parent().append("<span class='modified' style='color:blue'>"+ html +"</span>"); //returns the current update
 });

 request.fail(function( jqXHR, textStatus ) {
	 alert( "Request failed: " + textStatus );
 });
}
function userPrivilege(myUser, field,value)
{
	//alert(JSON.stringify(myData) );
	//myData is a json object type

	var request = $.ajax({
	 url: "userprivilege.php",
	 cache: false,
	 method: "POST",
	 data: {userID: myUser, privilege: value},
	 dataType: "text"
	});
	request.done(function( html ) {
	 $(".modified").remove(); //removes any old update notices
	 $("#"+field).parent().append("<span class='modified' style='color:blue'>"+ html +"</span>"); //returns the current update
	});

	request.fail(function( jqXHR, textStatus ) {
	 alert( "Request failed: " + textStatus );
	});
}


///////////////////
///Student Edit functions
//////////////////
function coachEdit(myID)
{
	var request = $.ajax({
	 url: "coachedit.php",
	 cache: false,
	 method: "POST",
	 data: {coachID:myID},
	 dataType: "html"
	});
	request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 window.location.hash = '#coach-edit-'+ myID;
	 $("#mainContainer").html(html);
	});

	request.fail(function( jqXHR, textStatus ) {
	 $("#mainContainer").html("Removal Error");
	});
}

///////////////////
///Event functions
//////////////////
function toggleSearch()
{
	$('#searchDiv').toggle();
}
function toggleAdd()
{
	$('#addTo').toggle();
}

function prepareEventsPage()
{
	$("#addTo").hide();
	$("#searchDiv").hide();

	getList("eventslist.php",{});
		// validate signup form on keyup and submit
	$("#addTo").validate({
		rules: {
			event_name: "required",
			type: "required",
		},
		messages: {
			event_name: "*Please enter the name event",
			type: "*Please enter the event type",
		},
		submitHandler: function(form) {
							form.submit();
					}
	});

	//when Find by Name is clicked, this initiates the search
	$("#findEvent").on( "submit", function( event ) {
		event.preventDefault();
		getList("eventslist.php", $( this ).serialize() );
	});
}

///////////////////
///Tournament functions
//////////////////

function prepareTournamentsPage()
{
		$("#addTo").hide();
		$("#searchDiv").hide();
		//Load Students
		getList("tournamentslist.php",{});
			// validate signup form on keyup and submit
		$("#addTo").validate({
			rules: {
				first: "required",
				last: "required",
				yearGraduating: "required",
				email: {
					required: true,
					email: true
				},
			},
			messages: {
				first: "*Please enter the student\'s first name",
				last: "*Please enter the student\'s last name",
				yearGraduating: {
					required: "*Enter the year the student is graduating",
				},
				email: {
					required: "*Enter the student\'s email.",
				},
			},
			submitHandler: function(form) {
                form.submit();
            }
		});

		//when Find by Name is clicked, this initiates the search
		$("#findTournament").on( "submit", function( event ) {
  		event.preventDefault();
  		getList("tournamentslist.php", $( this ).serialize() );
		});
			//Allow person to pick year
			for (i = new Date().getFullYear()+1; i > 1973; i--)
			{
			    $('#tournamentYear').append($('<option />').val(i).html(i));
			}
}

//TODO: Add blocker to keep users from clicking multiple times while an AJAX event is being completed.
var mobile = 0;
loadSummerNoteButtons();

if ( $( window ).width() <= 650 )
{
	mobile = 1;
}
$(document).ready(function() {
	//wait for the page to load before the following is run
	checkPage();
	$(window).on('hashchange', function() {
		checkPage();
	});
	//setTimeout(function() { loadpage("user") }, 3500000); //I don't think this does anything see php/checksession.php for updating token
});

//recognize if the window is too small for tables
$( window ).resize(function()
{
	if(mobile && $( window ).width()>650)
	{
		mobile = 0;
		resizePage();
	}
	else if(!mobile && $( window ).width()<=650)
	{
		mobile = 1;
		resizePage();
	}
});


//at this point in time, it only causes a reload of a page on resize if it is the teamassign page
function resizePage(){
	var splitHash = location.hash.substr(1).split("-");
	var page = splitHash[1];
	if(page == "teamassign")
	{
		loadpage(splitHash); //example: splitHash[0] = 'event' (page), splitHash[1] = 'edit' (type), splitHash[2] = '6' (myID)
		$("#mainHeader").html(splitHash[0]);
	}
}

function queryStringToJSON(queryString,myID) {
	//var pairs = location.search.slice(1).split('&');
	//separate query string, note to self: I am not using a question mark because it does not follow standard url formatting
	var result = {};
	if (queryString)
	{
		var pairs = queryString.split("+"); //get query string
		if (pairs)
		{
			pairs.forEach(function(pair) {
				pair = pair.split('=');
				result[pair[0]] = decodeURIComponent(pair[1] || '');
			});
		}
	}
	result["myID"] = myID;
	result["mobile"] = mobile;
	return JSON.parse(JSON.stringify(result));
}

function checkPage(){
	var splitHash = location.hash.substr(1).split("-");
	$("section:not(.navbar)").hide();
	if(splitHash[0])
	{
		loadpage(splitHash); //example: splitHash[0] = 'event' (page), splitHash[1] = 'edit' (type), splitHash[2] = '6' (myID)
		$("#mainHeader").html(splitHash[0]);
	}
	else
	{
		loadpage(["home"]);
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
		$("#list").html(html);
		$("#searchDbBtn").prop('disabled', false);
		$('#modalWait').modal('hide')
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#list").html("Search Error" + textStatus);
	});
}

function loadpage(myPage){
	//myPage[0]= page address, myPagemyPage[1]= type of page, pamyPagege[2] = id
	var typepage = "";
	var page = myPage[0];
	var myID = myPage[2];
	if(myPage[1])
	{
		typepage = myPage[1];
	}
	var dataJSON = queryStringToJSON(myPage[3],myID);
	var request = $.ajax({
		url: page+typepage+".php", //only adds page type if it exists, ex. #tournament-edit-6 ---> tournamentedit.php
		cache: false,
		method: "POST",
		data: dataJSON, //myID passed to page here
		dataType: "html"
	});

	request.done(function( html ) {
		//$("label[for='" + field + "']").append(html);
		$(".text-success").remove(); //removes any old update notices

		if(html)
		{
			//window.location.hash = '#tournament-view-'+ myID;
			$("#mainContainer").html(html);
			$.when( $("#myTitle") ).done(function( x ) {
				//changes title after page loads
				$("#myTitle").hide();
				$("#mainHeader").html($("#myTitle").html());
			});

			switch (page) {
				case 'students':
				studentPreparePage();
				break;

				case 'student':
				if(typepage=="edit"){
					studentEditPrepare(myID);
				}
				else if(typepage=="award"){
					studentAwardPrepare(myID);
				}
				break;

				case 'score':
				if(typepage=="edit"){
					scoreCalculate(myID);
				}
				break;

				case 'events':eventsPreparePage();
				break;

				case 'event':
				if(typepage=="emails"){
					//eventEmail(myID);
				}
				else{
					eventEdit(myID);
				}
				break;

				case 'attendance':
				if(typepage=="edit"){
					attendanceEdit(myID);
				}

				case 'eventyear':
				if(typepage=="edit"){
					//eventyearPrepare(myID);
				}
				else if (typepage=="leader"){
					eventyearLeader(myID);
				}
				break;

				case 'leaders':
				$.when( $("#year") ).done(function( x ) {
					$("#year").change(function(){
						window.location.hash = '#leaders--'+ $("#year option:selected").text();
					});
				});
				break;

				case 'tournaments':
				if(!typepage){
					tournamentsPreparePage();
				}
				break;

				case 'tournament':
				if(typepage=="teamassign"){
					tournamentAssignCheckErrors();
					touramentCarouselToggle();
				}
				else if(typepage=="teamedit"){
					tournamentTeamEditCheckErrors();
				}
				$.when( $(":submit") ).done(function( x ) {
					if(typepage=="edit"){
						tournamentEdit(myID);
					}
					else if(typepage=="times"){
						tournamentTimeAdd(myID);
					}
					else if(typepage=="events"){
						tournamentEventAdd(myID);
					}
					if(typepage=="eventnote"){
						tournamentEventNote(myID);
					}
					if(typepage=="eventtimechange"){
						tournamentEventTimeChange(myID);
					}
					else if(typepage=="eventtime"){
						tournamentTimesCheckErrors(myID);
					}
					else if(typepage=="score"){
						tournamentScore();
					}
					else{
					}
				});
				break;

				default:
			}
			$( "#main").show("fast");
		}
		else
		{
			$("#mainContainer").append("<div class='text-danger' class='error'>"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#mainContainer").append("<div class='text-danger' class='error'>Line 225: "+textStatus+"</div>");
	});
}

function studentPreparePage()
{
	$("#addTo").hide();
	$("#searchDiv").hide();
	//Load Students
	getList("studentslist.php",{active:$("#active").is(':checked')?"1":"0"});
	// validate signup form on keyup and submit

	//if the active checkbox is changed, then the screen will repopulate with the entire science olympiad population.  It does not remember the last clicked search.
	$('#active').change(function() {
		$('#modalWait').modal('show')
		$("#searchDbBtn").prop('disabled', true);
		getList("studentslist.php", $("#searchDb").serialize() );
	});
	//when Find is clicked, this initiates the search
	$("#searchDb").on( "submit", function( event ) {
		event.preventDefault();
		$('#modalWait').modal('show')
		$("#searchDbBtn").prop('disabled', true);
		getList("studentslist.php", $( this ).serialize() );
	});


	$( "#addTo").submit(function( event ) {
		event.preventDefault();
		var request = $.ajax({
			url: "studentadd.php",
			cache: false,
			method: "POST",
			data: $("#addTo").serialize(),
			dataType: "text"
		});

		request.done(function( html ) {
			$(".text-success").remove(); //removes any old update notices
			if(html>0)
			{
				window.location.hash = '#student-edit-'+html;
			}
			else
			{
				$("#addTo").append("<div class='text-danger' class='error'>"+html+"</div>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#mainContainer").html("Student Adding Error");
		});
	});
}

function studentRemove(myID, studentName)
{
	if(confirm("Are you sure you want to delete the user named: " + studentName +"?  This removes all of their data and it is permanent!!! It is much safer to mark them INACTIVE without deleting them."))
	{
		var request = $.ajax({
			url: "studentremove.php",
			cache: false,
			method: "POST",
			data: {myID:myID},
			dataType: "html"
		});
		request.done(function( html ) {
			$(".text-success").remove(); //remove any old text-success notes
			if(html=="1")
			{
				$("#student-" + myID).before("<div class='text-success'>"+studentName+" removed permanently.</div>"); //add note to show modification
				$("#student-" + myID).remove(); //remove element
			}
			else {
				$("#student-" + myID).before("<div class='text-danger'>Removal Error: "+html+"</div>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$(".text-success").remove();
			$("#student-" + myID).before("<div class='text-danger'>Removal Error: "+textStatus+"</div>");
		});
	}
}

///////////////////
///Student Edit functions
//////////////////
function studentEditPrepare(myID)
{
	$("#eventAndPriority").hide();
	$("#courseListDiv").hide();
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
			if (this.id == 'active'){
				fieldUpdate(myID, 'student', this.id, $(this).is(":checked")?1:0,this.id,this.id)
			}
			else if(this.id == 'privilege'){ //user privilege is defined in the user table
				fieldUpdate(myID, 'user', this.id, this.value,this.id,this.id);
			}
			else if(this.id == 'courseList'){ //user privilege is defined in the user table
				//do nothing wait for add button
			}
			else{
				fieldUpdate(myID,'student',this.id,this.value,this.id,this.id);
			}
		});
	});
}
function studentAwardPrepare(myID)
{
	if(myID)
	{
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
				fieldUpdate(myID,'award',this.id,this.value,this.id,this.id);
		});
	});
	}
}
function studentAwardRemove(myID)
{
	if(confirm("Are you sure you want to delete the award, called " + $("#award"+"-"+myID +" .award").text() +"?"))
	{
		rowRemove(myID,"award");
	}
}
function studentEventAddChoice(studentID)
{
	//adds the event choice selection
	$("#studentEventAdd").hide();
	$("#eventAndPriority").clone().appendTo("#studentEventAddDiv").show();
	$("#studentEventAddDiv").append("<a id='addThisEvent' href='javascript:studentEventAdd("+studentID+",this.id,this.value);'>Add</a>");
}
function studentEventRemove(myID)
{
	if(confirm("Are you sure you want to delete the event, called " + $("#eventchoice"+"-"+myID +" .event").text() +", from the user?"))
	{
		rowRemove(myID,"eventchoice");
	}
}
function studentEventAdd(student, field, value)
{
	// validate signup form on keyup and submit
	//alert("got"+$("#eventsList").val());
	var request = $.ajax({
		url: "studenteventadd.php",
		cache: false,
		method: "POST",
		data: { studentID: student, eventyearID : $("#eventsList").val(), priority : $("#priorityList").val() },
		dataType: "text"
	});

	request.done(function( html ) {
		//$("label[for='" + field + "']").append(html);
		$(".text-success").remove(); //removes any old update notices
		var eventchoiceID = parseInt(html);
		if(!isNaN(eventchoiceID)) //checks to see if a number is returned
		{
			//returns the current update
			var eventSplit = $("#eventsList option:selected").text().split(" ");
			var eventName = eventSplit[0]+"-"+$("#priorityList option:selected").text()+" "+eventSplit.slice(1).join(" ")
			$("#events").append("<div id='eventchoice-" + eventchoiceID + "'><span class='event'>"+ eventName + "</span> <a href=\"javascript:studentEventRemove('" + eventchoiceID + "')\">Remove</a> <span class='text-success'>Event added.</span></div>");
		}
		else
		{
			$("#events").append("<span class='text-success' class='error'>Error while attempting to add an event. Please, report details to site admin. "+html+"</span>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
}
function studentCourseAddChoice(student, table)
{
	//adds the course list selection
	$("#courseListDiv").appendTo("#add"+table+"Div").show();
	$(".addCourseBtn").show();
	$("#add"+table).hide();
	$("#addThisCourse").remove();
	$("#add"+table+"Div").append("<a id='addThisCourse' href=\"javascript:studentCourseAdd('"+student+"','"+table+"')\">Add</a>");
}
function studentCourseRemove(myID,table)
{
	if(confirm("Are you sure you want to delete the course, called " + $("#"+table+"-"+myID +" .course").text() +", from the user?"))
	{
		rowRemove(myID,table);
	}
}

function studentCourseAdd(student, table)
{
	// validate signup form on keyup and submit
	var request = $.ajax({
		url: "studentcourseadd.php",
		cache: false,
		method: "POST",
		data: { studentID: student, tableName : table, courseID : $("#courseList").val() },
		dataType: "text"
	});

	request.done(function( html ) {
		$(".text-success").remove(); //removes any old update notices
		var myCourseID = parseInt(html);
		if(!isNaN(myCourseID))//checks to see if a number is returned
		{
			//returns the current update
			$("#" + table).append("<div id='" + table + "-" + myCourseID + "'><span class='course'>"+ $("#courseList option:selected").text() + "</span></div>");
			if(table=="courseenrolled"){
				$("#"+ table + "-" + myCourseID).append(" <a href=\"javascript:studentCourseCompleted('" + myCourseID + "','" + $("#courseList option:selected").text() +"')\">Completed</a>");
			}
			$("#"+ table + "-" + myCourseID).append(" <a href=\"javascript:studentCourseRemove('" + myCourseID + "','"+table+"')\">Remove</a> <span class='text-success'>Course added.</span>");
		}
		else
		{
			$("#"+ table).append("<span class='text-danger'>Error while attempting to add a course. Please, report details to site admin.</span>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
}


function studentCourseCompleted(value, courseName)
{
	// validate signup form on keyup and submit
	var request = $.ajax({
		url: "studentcoursecompleted.php",
		cache: false,
		method: "POST",
		data: { myID: value},
		dataType: "text"
	});

	request.done(function( text ) {
		$(".text-success").remove(); //removes any old update notices
		var myCourseID = parseInt(text);
		if(!isNaN(myCourseID))//checks to see if a number is returned
		{
			//returns the current update
			var table = "coursecompleted";
			var table2 = "courseenrolled";
			$("#" + table).append("<div id='" + table + "-" + myCourseID + "'><span class='course'>"+ courseName + " </span><a href=\"javascript:studentCourseRemove('" + myCourseID + "','"+table+"')\">Remove</a> <span class='text-success'>Course added.</span></div>");
			$("#"+table2+"-"+value).remove();
			$("#"+table2).append("<span class='text-success'>Course removed.</span>");
		}
		else
		{
			var table = "courseenrolled";
			$("#"+ table).append("<span class='text-danger'>Error while attempting to move a course. Please, report details to site admin."+text+"</span>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
}

/*
function studentAwardAdd(student)
{
	// validate signup form on keyup and submit
	var request = $.ajax({
		url: "studentawardadd.php",
		cache: false,
		method: "POST",
		data: { studentID: student},
		dataType: "text"
	});

	request.done(function( html ) {
		$(".text-success").remove(); //removes any old update notices
		var myAwardID = parseInt(html);
		if(!isNaN(myAwardID))//checks to see if a number is returned
		{
			//returns the current update
			$("#awards").append("<div id='award-" + myAwardID + "'><input id='awardName-"+myAwardID+"' name='awardName' class='form-control' type='text' value=''></div>");
			$("#award-" + myAwardID).append(" <a href=\"javascript:studentCourseRemove('" + myAwardID + "')\">Remove</a> <span class='text-success'>Award added.</span>");
		}
		else
		{
			$("#awards").append("<span class='text-danger'>Error while attempting to add a award. Please, report details to site admin.</span>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
}
*/
function fieldUpdate(myID,table,field,value,domID,messageID)
{
	// validate field before submitting
	if($("#"+domID)[0].checkValidity())
	{
		fieldUpdateValid(myID,table,field,value,messageID);
	}
	else {
		$("#"+domID)[0].reportValidity();
	}
}

function fieldUpdateValid(myID,table,field,value,messageID)
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
			$("#"+messageID).parent().append("<span class='text-success'>*Record Updated</span>"); //returns the current update
		}
		else
		{
			$("#"+messageID).parent().append("<span class='text-success'>"+ html +"</span>"); //returns the warning
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#"+messageID).parent().append("<span class='text-danger'>"+ textStatus +"</span>"); //returns the error
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
		$(".text-success").remove(); //removes any old update notices
		$("#"+field).parent().append("<span class='text-success'>"+ html +"</span>"); //returns the current update
	});

	request.fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
}

///////////////////
///Coach Edit
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
///Attendance functions
//////////////////
function attendanceEdit(myID)
{
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
			//console.log("Input changed: ", $(this).attr('id'), " with value: ", this.value);
			fieldUpdate(myID,'meeting',this.id,this.value,this.id,this.id);
		});
	});

	// Meeting description and homework
	$('[data-summernote]').summernote({
		callbacks: { onBlur: function() {
			const content = $(this).summernote('code');
			//console.log($(this).attr('id') + " content changed: ", content);
			fieldUpdate(myID, 'meeting', $(this).attr('id'), content, $(this).attr('id'), $(this).attr('id'));
		}}
	});

	$('#attendanceContainer').on('change', ':input', function() {
        console.log("Radio button changed: ", $(this).attr('id'), " with value: ", this.value);
        if ((this.id).startsWith('attendance') || (this.id).startsWith('engagement') || (this.id).startsWith('homework')) {
            var studentID = ((this.id).split('-'))[1];
			var table = ((this.id).split('-'))[0];
            //console.log("Updating attendance for student ID: ", studentID);
            fieldUpdate(myID, table, studentID, this.value, $(this).attr('id'), $(this).attr('id'));
        }
    });
}
// Load a single student and their attendance data onto the page
function attendanceAddStudent(studentID, last, first, info, attendance=1, engagement=2, homework=0) {
	var formattedName = first + ' ' + last;
	 // adding student to the attendance form, or loading the student for attendance edit
	if(info == "load") { // for event attendance edit - TODO add additional checks for other types of attendance?
		var meetingType = 1;
	} else { // adding student to attendance form
		formattedName += info!=null?" - " + info:"";
		var meetingType = $("#meetingType option:selected").val();
	}
	if(studentID.length === 0)
	{
		//ignore this student - this may be called as part of adding everyone
		return 0;
	}

	//check if the new student was already added before
	if(document.getElementsByName('attendance-'+studentID).length > 0) 
	{
		//ignore this student - this may be called as part of adding a team
		return 0;
	}
	else
	{
		//create a new div with student information
		//if(confirm("Add student: " + formattedName + "?"))
		//{
			var newStudent = `<div>
					<h3>${formattedName} </h3>
					<p>Attendance: P = Present, AU = Absent Unexcused, AE = Absent Excused (Contacted you with a reason before meeting / Absent from school)</p>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-P" value="1" ${attendance==1?'checked':''}>
					<label class="form-check-label" for="attendance-${studentID}-P">P</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-AU" value="-1" ${attendance==-1?'checked':''}>
					<label class="form-check-label" for="attendance-${studentID}-AU">AU</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-AE" value="0" ${attendance==0?'checked':''}>
					<label class="form-check-label" for="attendance-${studentID}-AE">AE</label>
				</div>`;
				
				if(meetingType == 1)//meetingType 1 = event meeting //TODO change here for adding engagement to other meeting types
				{
					newStudent +=`<p>Engagement: 0 for not engaged, 1 for partially engaged, 2 for fully participated</p>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-0" value="0" ${engagement==0?'checked':''}>
					<label class="form-check-label" for="engagement-${studentID}-0">0</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-1" value="1" ${engagement==1?'checked':''}>
					<label class="form-check-label" for="engagement-${studentID}-1">1</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-2" value="2" ${engagement==2?'checked':''}>
					<label class="form-check-label" for="engagement-${studentID}-2">2</label>
				</div>`;
				}
				if(meetingType == 1)//meetingType 1 = event meeting //TODO change here for adding homework to other meeting types
				{
					newStudent +=`<p>Homework: 0 for Not Submitted or No Homework, 1 for partially incomplete, 2 for fully complete</p>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-0" value="0" ${homework==0?'checked':''}>
					<label class="form-check-label" for="homework-${studentID}-0">0</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-1" value="1" ${homework==1?'checked':''}>
					<label class="form-check-label" for="homework-${studentID}-1">1</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-2" value="2" ${homework==2?'checked':''}>
					<label class="form-check-label" for="homework-${studentID}-2">2</label>
				</div>`;
				}
				newStudent += "<hr>";
			//document.getElementById("studentID").insertAdjacentHTML('beforebegin', newStudent);
			$("#attendanceContainer").append(newStudent);
			return 1;
		//}
	}
}

function tournamentSort(tableName, byAttr, isNumber=0)
{
	var $table=$('#'+tableName);
	var sortBy = $table.attr('sortby');
	var rows = $table.find('tbody>tr').get();
	rows.sort(function(a, b) {
		if(sortBy == byAttr)
		{
			//switch the ordering to descending
			var keyB = $(a).attr(byAttr);
			var keyA = $(b).attr(byAttr);
		}
		else {
			//normal ordering (ascending)
			var keyA = $(a).attr(byAttr);
			var keyB = $(b).attr(byAttr);
		}

		if(isNumber)
		{
			if (Number(keyA) > Number(keyB)) return 1;
			if (Number(keyA) < Number(keyB)) return -1;
		}
		else {
			if (keyA > keyB) return 1;
			if (keyA < keyB) return -1;
		}
		return 0;
	});
	$.each(rows, function(index, row) {
		$table.children('tbody').append(row);
	});
	//set the table attribute
	if(sortBy == byAttr)
	{
		$table.attr('sortby',byAttr+"d"); //names the sorting as descending
	}
	else
	{
		$table.attr('sortby',byAttr);//names the sort (ascending)
	}
}

///////////////////
///Event functions
//////////////////
function toggleSearch()
{
	$('#searchDiv').toggle();
}

function eventsPreparePage()
{
	$("#searchDiv").hide();
	$("#addTo").hide();

	getList("eventslist.php",{});
	// validate signup form on keyup and submit

	//when Find by Name is clicked, this initiates the search
	$("#searchDb").on( "submit", function( event ) {
		event.preventDefault();
		$('#modalWait').modal('show')
		$("#searchDbBtn").prop('disabled', true);
		getList("eventslist.php", $( this ).serialize() );
	});

	$( "#addTo").submit(function( event ) {
		event.preventDefault();
		var request = $.ajax({
			url: "eventadd.php",
			cache: false,
			method: "POST",
			data: $("#addTo").serialize(),
			dataType: "text"
		});

		request.done(function( html ) {
			//$("label[for='" + field + "']").append(html);
			if(html>0)
			{
				window.location.hash = '#events--'+html;
			}
			else
			{
				$("#addTo").append("<div class='text-danger'>"+html+"</div>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#mainContainer").html("Removal Error");
		});
	});
}

function eventEdit(myID)
{
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
			fieldUpdate(myID,'event',this.id,this.value,this.id,this.id);
		});
	});
}

///////////////////
///Team functions
//////////////////
// Copy the teammates in a team into a new team competition
function teamCopy(thisTeamID)
{
	var copiedTeamID = $("#team option:selected").val();
	//get student list on team
	var request = $.ajax({
			url: "teamcopylist.php",
			cache: false,
			method: "POST",
			data: {myID:copiedTeamID},
			dataType: "json"
		});

		request.done(function( data ) {
			$(".text-success").remove(); //removes any old update notices
			$.each( data, function( key, val ) {
				inputBtn = $("#teammate-"+ thisTeamID + "-" + val["studentID"]);
				if (!inputBtn.is(":checked"))
				{
					inputBtn.trigger( "click" );
				}
			});
			$("#mainContainer").append("<div class='text-success'>Added Students</div>");
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#mainContainer").append("Removal Error");
		});
}
// Copy the events in a team into a new team competition
function teamCopyAssignments(thisTeamID)
{
	var copiedTeamID = $("#team option:selected").val();
	//get student list on team
	var request = $.ajax({
			url: "teamcopyassignments.php",
			cache: false,
			method: "POST",
			data: {myID:copiedTeamID},
			dataType: "json"
		});

		request.done(function( data ) {
			alert(data);
			$(".text-success").remove(); //removes any old update notices
			$.each( data, function( key, val ) {
				inputBtn = $(".teammateStudent-"+ val[0] + ".event-" + val[1] + " input");
				if (inputBtn && !inputBtn.is(":checked"))
				{
					inputBtn.trigger( "click" );
				}
			});
			$("#mainContainer").append("<div class='text-success'>Added Students</div>");
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#mainContainer").append("<div class='text-danger'>Removal Error:"+html+"</div>");
		});

}
// Copies selected text to user's clipboard
function copyToClipboard(text) {
	var input = document.createElement('textarea');
	input.value = text;
	document.body.appendChild(input);
	input.select();
	document.execCommand('copy');
	document.body.removeChild(input);
	alert("Copied to clipboard!");
} 

///////////////////
///Tournament functions
//////////////////
// On before slide change
function touramentCarouselToggle()
{
	$(document).ready(function() {

		if($('input[name="btnradio"]:checked').val()=='time')
		{
			$('#studentCarousel').hide();
			$('#eventCarousel').hide();
			$('#timeCarousel').show();
		}
		else if($('input[name="btnradio"]:checked').val()=='event')
		{
			$('#studentCarousel').hide();
			$('#timeCarousel').hide();
			$('#eventCarousel').show();
		}
		else {
			$('#timeCarousel').hide();
			$('#eventCarousel').hide();
			$('#studentCarousel').show();
		}
	});
}

function tournamentsPreparePage()
{
	//note update
	$('[data-summernote]').summernote({
		callbacks: { onBlur: function() {
			let content = $(this).summernote('code');
			fieldUpdate(myID, 'tournament', $(this).attr('id'), content, $(this).attr('id'), $(this).attr('id'));
		}}
	});
	$("#searchDiv").hide();
	$("#addTo").hide();
	//Load Students
	getList("tournamentslist.php",{});
	// validate signup form on keyup and submit

	//when Find by Name is clicked, this initiates the search
	$("#searchDb").on( "submit", function( event ) {
		event.preventDefault();
		$('#modalWait').modal('show')
		$("#searchDbBtn").prop('disabled', true);
		getList("tournamentslist.php", $( this ).serialize() );
	});
	/*	//Allow person to pick year
	for (i = new Date().getFullYear()+1; i > 1973; i--)
	{
	$('#tournamentYear').append($('<option />').val(i).html(i));
}
*/

$( "#addTo" ).submit(function( event ) {
	event.preventDefault();
	var request = $.ajax({
		url: "tournamentadd.php",
		cache: false,
		method: "POST",
		data: $("#addTo").serialize(),
		dataType: "text"
	});

	request.done(function( html ) {
		$(".text-success").remove(); //removes any old update notices
		if(html>0)
		{
			window.location.hash = '#tournament-view-'+html;
		}
		else
		{
			$("#addTo").append("<div class='text-success'>"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#mainContainer").html("<div class='text-danger'>Removal Error:"+html+"</div>");
	});
});
}


function studentCourse(tableName, byAttr, isNumber=0)
{
	var $table=$('#'+tableName);
	var sortBy = $table.attr('sortby');
	var rows = $table.find('tbody>tr').get();
	rows.sort(function(a, b) {
		if(sortBy == byAttr)
		{
			//switch the ordering to descending
			var keyB = $(a).attr(byAttr);
			var keyA = $(b).attr(byAttr);
		}
		else {
			//normal ordering (ascending)
			var keyA = $(a).attr(byAttr);
			var keyB = $(b).attr(byAttr);
		}

		if(isNumber)
		{
			if (Number(keyA) > Number(keyB)) return 1;
			if (Number(keyA) < Number(keyB)) return -1;
		}
		else {
			if (keyA > keyB) return 1;
			if (keyA < keyB) return -1;
		}
		return 0;
	});
	$.each(rows, function(index, row) {
		$table.children('tbody').append(row);
	});
	//set the table attribute
	if(sortBy == byAttr)
	{
		$table.attr('sortby',byAttr+"d"); //names the sorting as descending
	}
	else
	{
		$table.attr('sortby',byAttr);//names the sort (ascending)
	}
}

function tournamentEdit(myID)
{
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
			if (this.id == 'notCompetition'){
				fieldUpdate(myID, 'tournament', this.id, $(this).is(":checked")?1:0,this.id,this.id);
			}
			else {
				fieldUpdate(myID,'tournament',this.id,this.value,this.id,this.id);
			}
		});
	});
	//note update
	$('[data-summernote]').summernote({
		callbacks: { onBlur: function() {
			let content = $(this).summernote('code');
			fieldUpdate(myID, 'tournament', $(this).attr('id'), content, $(this).attr('id'), $(this).attr('id'));
		}}
	});
}

function tournamentRemove(myID, tournamentName)
{
	if(confirm("Are you sure you want to delete the tournament named: " + tournamentName +"?  This removes all of their data and it is permanent!!!  This will only work if all assignments and times have been removed."))
	{
		var request = $.ajax({
			url: "tournamentremove.php",
			cache: false,
			method: "POST",
			data: {myID:myID},
			dataType: "html"
		});
		request.done(function( html ) {
			$(".text-success").remove(); //remove any old text-success notes
			if(html=="1")
			{
				$("#tournament-" + myID).before("<div class='text-success'>"+tournamentName+" removed permanently.</div>"); //add note to show modification
				$("#tournament-" + myID).remove(); //remove element
			}
			else {
				$("#tournament-" + myID).before("<div class='text-danger'>Removal Error:"+html+"</div>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$(".text-success").remove();
			$("#tournament-" + myID).before("<div class='text-danger'>Removal Error:"+textStatus+"</div>");
		});
	}
}

function tournamentPublish(myID)
{
	if(confirm("Are you sure you want to publish the team assignments to students?"))
	{
		tournamentPublishToggle(myID);
	}
}
function tournamentUnPublish(myID)
{
	if(confirm("Are you sure you want to hide the team assignments to students?"))
	{
		tournamentPublishToggle(myID);
	}
}

//Toggles publication of tournament assignments to students and toggles button
function tournamentPublishToggle(myID)
{
		var request = $.ajax({
			url: "tournamentpublish.php",
			cache: false,
			method: "POST",
			data: {myID:myID},
			dataType: "html"
		});
		request.done(function( html ) {
			$(".text-success").remove(); //remove any old text-success notes
			if(html=="1")
			{
				//published
				$("#publishBtn").replaceWith("<a id='publishBtn' class='btn btn-secondary' role='button' href='javascript:tournamentUnPublish("+myID+")'><span class='bi bi-cup-hot'></span> Unpublish</a>"); 
			}
			else if(html=="2")
			{
				//unpublished
				$("#publishBtn").replaceWith("<a id='publishBtn' class='btn btn-primary' role='button' href='javascript:tournamentPublish("+myID+")'><span class='bi bi-cup'></span> Publish</a>"); 
			}
			else
			{
				$("#publishBtn").before("<div class='text-danger'>Publication Error:"+html+"</div>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$(".text-success").remove();
			$("#tournament-" + myID).before("<div class='text-danger'>Removal Error:"+textStatus+"</div>");
		});
}

//formats javascript time to have leading zeroes.  For instance 9:00AM is formatted as 09:00AM.
function appendLeadingZeroes(n){
	if(n <= 9){
		return "0" + n;
	}
	return n
}


function tournamentTimeAdd(myID)
{
	$( "#addTo").submit(function( event ) {
		event.preventDefault();
		var request = $.ajax({
			url: $("#addTo").attr('action'),
			cache: false,
			method: "POST",
			data: $("#addTo").serialize() + '&myID=' + myID,
			dataType: "text"
		});

		request.done(function( html ) {
			$(".text-success").remove(); //removes any old update notices
			if(html>0)
			{
				//convert time to standard format
				let timeStart = new Date($("#timeStart").val());
				let timeStartFormatted = timeStart.getFullYear() + "-" + appendLeadingZeroes(timeStart.getMonth() + 1) + "-" + appendLeadingZeroes(timeStart.getDate()) + " " + appendLeadingZeroes(timeStart.getHours()) + ":" + appendLeadingZeroes(timeStart.getMinutes()) + ":" + appendLeadingZeroes(timeStart.getSeconds());

				let timeEnd = new Date($("#timeEnd").val());
				let timeEndFormatted = timeEnd.getFullYear() + "-" + appendLeadingZeroes(timeEnd.getMonth() + 1) + "-" + appendLeadingZeroes(timeEnd.getDate()) + " " + appendLeadingZeroes(timeEnd.getHours()) + ":" + appendLeadingZeroes(timeEnd.getMinutes()) + ":" + appendLeadingZeroes(timeEnd.getSeconds());
				//clear empty timeblock output
				if($("#timeblocks").html()=="None Added")
				{
					$("#timeblocks").empty();
				}
				$("#timeblocks").append("<li id='timeblock-"+html+"'><a href='#tournament-eventtimechange-"+html+"'>"+timeStartFormatted+" - "+timeEndFormatted+"</a> <a href='javascript:tournamentTimeblockRemove("+html+")'><span class='bi bi-trash'></span> Remove</a></li>");
			}
			else
			{
				$("#addTo").append("<div class='text-success' class='error'>"+html+"</div>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#mainContainer").html("Removal Error");
		});
	});
}

function tournamentEventAdd(myID)
{
	$( "#addTo").submit(function( event ) {
		event.preventDefault();
		var request = $.ajax({
			url: $("#addTo").attr('action'),
			cache: false,
			method: "POST",
			data: $("#addTo").serialize() + '&myID=' + myID,
			dataType: "text"
		});

		request.done(function( html ) {
			$(".text-success .text-warning .text-danger").remove(); //removes any old update notices
			if(html>0)
			{
				loadpage(['tournament','events',myID]); //refresh page to show added event
			}
			else
			{
				$("#addTo").append("<div class='text-warning'>"+html+"</div>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#mainContainer").html("Removal Error");
		});
	});
}

function tournamentEventNote(myID)
{
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
			fieldUpdate(myID,'tournamentevent',this.id,this.value,this.id,this.id);
		});
	});
}

const format = (num, decimals) => num.toLocaleString('en-US', {
	minimumFractionDigits: 2,
	maximumFractionDigits: 2,
});

function tournamentRankReset()
{
	var $table=$('#tournamentTable');
	$table.attr('sortby',"score"); //setting this attribute, makes the table sort descending
	tournamentSort(`tournamentTable`,'score', 1);
	var rows = $table.find('tbody>tr').get();
	$.each(rows, function(index, row) {
		$(row).attr('rank',index+1);  //change rank
		var splitStudent = $(row).children('.student').attr('id').split("-");
		$("#rank-"+splitStudent[1]).text(index+1);
	});
}

function calculateScore(eventPlace, eventWeight, tournamentWeight, teamsAttended)
{
	//formula for scoring here
	if (eventPlace)
	{
		//return eventWeight/((eventPlace)^0.5)*(tournamentWeight/100); //old calcuation
		//var score = (-(eventPlace^2)/100+100)*(tournamentWeight/100)*(eventWeight/100);
		//$score = ($tournamentWeight-(($eventPlace-1)*($tournamentWeight/$teamsAttended)))*$eventWeight/100;
		var score = (tournamentWeight-((eventPlace-1)*(tournamentWeight/(teamsAttended/4))))*eventWeight/100;
		if (score <=1)
		{
			return 1;
		}
		return score;
	}
	return 0;
}

function tournamentScoreCalculate(studentID)
{
	//calculate student with this event
	var tournamentWeight = $("#tournamentWeight").val();
  var teamsAttended = $("#teamsAttended").val();
	var scoreTotal = 0;
	$(".student-"+studentID).each(function(){
		var score = 0;
		if($(this).text()!="")
		{
			var splitStudentCalcID = this.id.split("-");
			var place = $(this).attr('placement');
			var eventWeight = $("#eventweight-"+splitStudentCalcID[2]).val();
			score = calculateScore(place, eventWeight, tournamentWeight, teamsAttended);
			if (place)
			{
				$(this).text(place + "("+format(score)+")");
			}
			scoreTotal += score;
		}
	});

	//score = score * tournamentWeight/100;
	$("#score-"+studentID).text(format(scoreTotal));
	$("#teammate-"+studentID).parent().attr('score',format(scoreTotal));
}

function tournamentScore()
{
	//toggle the points
	$('#showPoints').change(function() {
		if(this.checked) {
			$('.score').show('slow');
		}
		else {
			$('.score').hide();
		}
	});
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
			$(this).addClass('changed');
			var splitID = this.id.split("-");
			if (this.id == "tournamentWeight")
			{
				//if tournament weight has changed, then all scores must be recalculated.
				$('[id^=teammate-]').each(function (){
					var splitStudentID = this.id.split("-");
					tournamentScoreCalculate(splitStudentID[1]);
				});
			}
			else
			{
				//if the event weight has changed, then only change scores for students affected.
				//find students with this eventTotal
				$(".event-"+splitID[1]).each(function(){
					if($(this).text()!="")
					{
						//console.log("got me"+ $(this).text());
						var splitStudentID = this.id.split("-");
						tournamentScoreCalculate(splitStudentID[1]);
					}
				});
			}
			tournamentRankReset();
		});
	});
}

//save scores to table, save weights that have been changed
function tournamentScoresSave(tournamentID)
{
	//fieldUpdate(myID,table,field,value,domID)
	//Update Weights
	$(".changed").each(function(){
		if (this.id == "tournamentWeight")
		{
			fieldUpdate(tournamentID,'tournament','weight',this.value,this.id,this.id);
		}
		else
		{
			var splitID = this.id.split("-");
			fieldUpdate(splitID[1],'tournamentevent','weight',this.value,this.id,"tournamentWeight");
		}
		$(this).removeClass( "changed" )
	});
	var request = $.ajax({
		url: "tournamentscoresave.php",
		cache: false,
		method: "POST",
		data: {myID: tournamentID},
		dataType: "html"
	});

	request.done(function( html ) {
		//$("label[for='" + field + "']").append(html);
		$(".text-success").remove(); //removes any old update notices

		if(html=="1")
		{
			$("#mainContainer").append("<div class='text-success'>Saved Scores</div>");
		}
		else
		{
			$("#mainContainer").append("<div class='text-success' class='error'>"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#mainContainer").append("<div class='text-success' class='error'>"+textStatus+"</div>");
	});
}

//calculate overall score
function scoreCalculate(year)
{
	//placeholder
	//TODO: use when sending different year
}

function tournamentEventTimeChange(myID)
{
	$('#addTo :input,select').each(function() {
		$(this).change(function(){
			fieldUpdate(myID,'timeblock',this.id,this.value,this.id,this.id);
		});
	});
}


function tournamentTeamLock(myID)
{
	if(confirm("Lock team assignments?"))
	{
		tournamentTeamLockToggle(myID);
	}
}
function tournamentTeamUnLock(myID)
{
	if(confirm("Unlock team assignments?"))
	{
		tournamentTeamLockToggle(myID);
	}
}

function tournamentTeamLockToggle(myID) {
	var request = $.ajax({
		url: "tournamentteamlock.php",
		cache: false,
		method: "POST",
		data: {myID:myID},
		dataType: "html"
	});
	request.done(function( html ) {
		$(".text-success").remove();
		if(html=="1")
		{
			//locked
			$("#lockBtn").replaceWith("<a id='lockBtn' class='btn btn-secondary' role='button' href='javascript:tournamentTeamUnLock("+myID+")'><span class='bi bi-unlock'></span> Unlock</a>"); 
		}
		else if(html=="2")
		{
			//unlocked
			$("#lockBtn").replaceWith("<a id='lockBtn' class='btn btn-secondary' role='button' href='javascript:tournamentTeamLock("+myID+")'><span class='bi bi-lock'></span> Lock</a>"); 
		}
		else
		{
			$("#lockBtn").before("<div class='text-danger'>Error:"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$(".text-success").remove();
		$("#tournament-teamassign-" + myID).before("<div class='text-danger'>Removal Error:"+textStatus+"</div>");
	});
}

function toggleAdd()
{
	$('#addTo').toggle();
}

function tournamentEventsAddAll(myID, year)
{
	var request = $.ajax({
		url: "tournamenteventsaddall.php",
		cache: false,
		method: "POST",
		data: {tournamentID: myID, year: year},
		dataType: "html"
	});

	request.done(function( html ) {
		//$("label[for='" + field + "']").append(html);
		$(".text-success").remove(); //removes any old update notices

		if(html=="1")
		{
			window.location.hash = window.location.hash + "-updated";
		}
		else
		{
			$("#mainContainer").append("<div class='text-success' class='error'>"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#mainContainer").append("<div class='text-success'>"+textStatus+"</div>");
	});
}

function tournamentEventsAdd(tournamentID)
{
	var eventID = $("#eventsList option:selected").val();
	var request = $.ajax({
		url: "tournamenteventsadd.php",
		cache: false,
		method: "POST",
		data: {tournament: tournamentID, event: eventID},
		dataType: "html"
	});

	request.done(function( html ) {
		//$("label[for='" + field + "']").append(html);
		$(".text-success").remove(); //removes any old update notices
		if(!isNaN(parseInt(html)))//checks to see if a number is returned
		{
			window.location.hash = window.location.hash + "-updated";
		}
		else
		{
			$("#mainContainer").append("<div class='text-warning'>"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#mainContainer").append("<div class='text-warning'>"+textStatus+"</div>");
	});
}

function addToSubmit(page)
{
	var request = $.ajax({
		url: page,
		cache: false,
		method: "POST",
		data: $("#addTo").serialize(),
		dataType: "html"
	});

	request.done(function( html ) {
		$(".text-success").remove(); //removes any old update notices
		if(html>0)
		{
			history.back();
		}
		else
		{
			$("#mainContainer").append("<div class='text-success' class='error'>"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#mainContainer").html("Error.  Check file named " + $("#addTo").attr('action') + " exists.");
	});
}

function tournamentTimeblockRemove(myID)
{
	if(confirm("Are you sure you want to delete the time block " + myID +"?  This removes the time block permanently!!!"))
	{
		rowRemove(myID,"timeblock");
	}
}
function rowRemove(myID,table)
{
	// check to see if the event has anyone signed up for this tournament
	var request = $.ajax({
		url: "rowremove.php",
		cache: false,
		method: "POST",
		data: { myid: myID, mytable:table},
		dataType: "text"
	});

	request.done(function( html ) {
		if(html==1) 	 {
			$(".text-success").remove(); //removes any old update notices
			$("#" + table + "-" + myID + " button").remove();  //remove buttons in list
			$("#" + table + "-" + myID + " a").remove(); //remove links in list
			$("#" + table + "-" + myID).before("<div class='text-success'>"+$("#" + table + "-" + myID).text()+" removed permanently.</div>"); //add note to show modification
			$("#" + table + "-" + myID).remove(); //remove element
		}
		else {
			$(".text-success").remove();
			$("#" + table + "-" + myID).before("<div class='text-warning'>Removal Error:"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$(".text-success").remove();
		$("#" + table + "-" + myID).before("<div class='text-danger'>Request failed:"+textStatus+"</div>");
	});
}
function tournamentEventRemove(myID,myName)
{
	// validate signup form on keyup and submit
	var request = $.ajax({
		url: "tournamenteventempty.php",
		cache: false,
		method: "POST",
		data: { tournamentevent: myID},
		dataType: "text"
	});

	request.done(function( html ) {
		if(parseInt(html)==1) 	 {
			if(confirm("Are you sure you want to delete "+myName+" from this tournament " + myID +"?  This removes the event permanently!!!"))
			{
				var request2 = $.ajax({
					url: "tournamenteventtimeempty.php",
					cache: false,
					method: "POST",
					data: { tournamentevent: myID},
					dataType: "text"
				});
				request2.done(function( html ) {
					if(parseInt(html)==1) 	 {
						rowRemove(myID,"tournamentevent");
					}
					else {
						$("#note").html("<div class='text-danger'>Change Error:"+html+"</div>");
						inputBtn.prop('checked', checked?0:1);
					}
				});

				request2.fail(function( jqXHR, textStatus ) {
					$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div>");
				});

			}
		}
		else {
			alert (html);
			return 0;
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		return 0;
	});


}

//This changes the tournamenttimeavailable and tournamenttimechosen
function tournamentEventTimeSet(inputBtn)
{
	//alert(inputBtn.attr('name'));
	var objectName = inputBtn.attr('name');
	var splitName = objectName.split("-");
	var checked = inputBtn.is(":checked")?1:0;
	$("#tournamenteventwarning-"+splitName[1]).text("");
	var checkTime = tournamentTimesCheckError(splitName[1], splitName[2], $("label[for='"+inputBtn.attr('id')+"']").text());
	if(checkTime){
		inputBtn.prop("checked", false);
		return 0;
	}

	var request = $.ajax({
		url: "tournamenteventtimeadjust.php",
		cache: false,
		method: "POST",
		data: { table: splitName[0], tournamentevent: splitName[1], timeblockID: splitName[3], teamID: splitName[2], checked: checked },
		dataType: "text"
	});

	request.done(function( html ) {
		if(html=='1') 	 {
			var modified = checked?"added":"removed";
			$("#note").html("<div class='text-success'>Time "+modified+" for "+$("#tournamenteventname-"+splitName[1]).text()+" " +$("#timeblock-"+splitName[2]).text()+"</div>"); //add note to show modification
		}
		else {
			$("#note").html("<div class='text-danger'>Change Error:"+html+"</div>");
			inputBtn.prop('checked', checked?0:1);
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div>");
	});
}

//Check all timeblocks for duplicate selections
//TODO: Warn if no time is chosen.
function tournamentTimesCheckErrors()
{
	//myID = tournamenteventID
	var error = 0;
	$.when( $("#teamIDs") ).done(function( x ) {
		//Get Team data stored in php file as a JSON array, so that they can be checked one by one here
		var teams = JSON.parse($("#teams").text());
		//Go through each row and check the teams do not have more than one timeblock chosen per team
		$( "tr[id*='tournamentevent-']" ).each(function( index ) {
			var tournamenteventID = this.id.split("-")[1];
			$("#tournamenteventwarning-"+tournamenteventID).text("");
			for (let i = 0; i < teams.length; i++) {
				//console.log(teamIDs[i]);
				var timeCheck = tournamentTimesCheckError(tournamenteventID, teams[i]['teamID'], teams[i]['teamName']);
				error = timeCheck?1:error;
			}
			//console.log( index + ": " + $( this ).text() );
		});
	});
	return error;
}

//Check only the button that you checked for duplicate times
function tournamentTimesCheckError(tournamenteventID, teamID, teamName)
{
	var numTimes = $('[id*="tournamenttimechosen-' + tournamenteventID + '-' + teamID + '"]:checked').length;
	if(numTimes>1)
	{
		$("#tournamenteventwarning-"+ tournamenteventID).append(" Only one time may be chosen for the same team ("+teamName+").");
		return 1; //There is an error
	}
	else if(numTimes==0)
	{
		$("#tournamenteventwarning-"+ tournamenteventID).append(" Choose one time for team "+teamName+".");
	}
	else {
		return 0;
	}
}

//Sets teammates for a team
function tournamentTeammate(inputBtn)
{
	//alert(inputBtn.attr('name'));
	//alert(inputBtn.is(":checked"));
	var objectName = inputBtn.attr('name');
	var splitName = objectName.split("-");
	var checked = inputBtn.is(":checked")?1:0;

	//check number of students assigned that are in 12 grade and total number of students, warn user but do not prevent.

	var request = $.ajax({
		url: "tournamentteammateadjust.php",
		cache: false,
		method: "POST",
		data: { table: splitName[0], teamID: splitName[1], studentID: splitName[2], checked: checked },
		dataType: "text"
	});

	request.done(function( html ) {
		if(html=='1') 	 {
			var modified = checked?"added":"removed";
			$("#note").html("<div class='text-success'>"+$("label[for='"+ inputBtn.attr('id') +"']").text()+" "+modified+"</div>"); //add note to show modification
		}
		else if(html=='3'){
			alert("Remove failed: student still has events.");
			inputBtn.prop('checked', true);
		}
		else if(html=='2'){
			alert("Add failed: student is on a different team.")
			inputBtn.prop('checked', false);
		}
		else {
			$("#note").html("<div class='text-danger'>Change Error:"+html+"</div>");
		}
		tournamentTeamEditCheckErrors();
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#note").html("<div class='text-success' class='error'>Change Error:"+textStatus+"</div>");
	});
}

//Check student count and senior count
function tournamentTeamEditCheckErrors()
{
	//console.log("tournamentTeamEditCheckErrors");
	var countSenior = 0;
	//find total students - check to make sure it is less than 15
	var countStudent = $('input:checkbox:checked').length;
	//find total seniors
	$('input:checkbox:checked').each(function (i,v)
	{
		var studentGrade = $(v).data( "studentgrade" );//check number of students allowed in stored data
		if(studentGrade>11)
		{
			countSenior +=1;
		}
	});
	var error = "";
	if (countSenior >7)
	{
		error += "<span class='error'>Too many seniors on the team!</span>";
		$('#seniors').html("<span class='error'>"+countSenior+"</span>");
	}
	else
	{
		$('#seniors').text(countSenior);
	}

	if (countStudent >15)
	{
		error += " <span class='text-warning'> More than 15 students assigned on the team!</span>";
		$('#students').html("<span class='error'>"+countStudent+"</span>");
	}
	else if (countStudent <15)
	{
		error += " <span class='text-danger'>Less than 15 students on the team!</span>";
		$('#students').html("<span class='text-warning'>"+countStudent+"</span>");
	}
	else
	{
		$('#students').text(countStudent);
	}

	$("#note").html("<span class='text-success'>"+error+"</span");
}

//sets  teammate in an event
function tournamentEventTeammate(inputBtn)
{
	//alert(inputBtn.attr('name'));
	//alert(inputBtn.is(":checked"));
	var objectName = inputBtn.attr('name');
	var splitName = objectName.split("-");
	var checked = inputBtn.is(":checked")?1:0;
	var place = $("#placement-"+splitName[1]+"--"+splitName[3]).length ? $("#placement-"+splitName[1]+"--"+splitName[3]).val():0;

	//Be aware: same event with same timeblock causes problem; however,there should not be same event with two different times
	//Do not allow 2 or more timeblocks for the same event!
	var request = $.ajax({
		url: "tournamenteventteammateadjust.php",
		cache: false,
		method: "POST",
		data: { tournamenteventID: splitName[1], studentID: splitName[2], teamID: splitName[3], checked: checked, place: place},
		dataType: "text"
	});

	request.done(function( html ) {
		if(html=='1') 	 {
			var checkChanged = checked?"added":"removed";
			if(splitName[2])
			{
				$("#note").html("<div class='text-success'>"+$("#event-"+splitName[1]).text() +" " +checkChanged+" for "+$("#teammate-"+splitName[2]).text()+"</div>"); //add note to show modification
				//recalculate and check for errors
				tournamentCalculateEvent(splitName[1]);
				tournamentCalculateStudent(splitName[2]);
				tournamentCalculateTimeblock(splitName[2]);
			}
			else {
				$("#note").html("<div class='text-success'>"+$("#event-"+splitName[1]).text() +" placed "+place+"</div>"); //add note to show modification
			}
		}
		else if(html=='2'){
			$("#note").html("<div class='text-danger'>Add failed: student is not on the team.</div>");
			inputBtn.prop('checked', false);
		}
		else if(html=='3'){
			$("#note").html("<div class='text-warning'>Add warning: another user has already added this student to this event.</div>");
			//keep button checked
		}
		else {
			$("#note").html("<div class='text-danger'>Change Error:"+html+"</div>");
			inputBtn.prop('checked', false);
		}
		//recalculate total score
		var totalPoints = 0;
		$('.placement').each(function(i,n){
			var eventPoints = parseInt($(n).val());
			if(Number.isInteger(eventPoints))
			{
				totalPoints += eventPoints;
			}
		});
		$("#teamScore").html(totalPoints);
	});

	request.fail(function( jqXHR, textStatus ) {
		$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div>");
	});
}

//Calculate the number of students in an event during one time block
function tournamentCalculateEvent(tournamenteventID)
{
	$("#eventtotal-"+tournamenteventID+" .text-success").remove(); //remove old warning
	var eventAssigned = 0; //count number of students assigned
	if ($(".teammateEvent-"+tournamenteventID+" > input").length)
	{
		//Editors
		var eventAssigned = $(".teammateEvent-"+tournamenteventID+" :checkbox:checked").length; //count number of students assigned to event
	}
	else {
		//Student view without edit priviledge
		var eventAssigned = $(".teammateEvent-"+tournamenteventID+" > .bi-check").length; //count number of students assigned to event
	}
	$("#eventtotal-"+tournamenteventID).text(eventAssigned); //print number of students assigned
	var eventMax = $("#eventtotal-"+tournamenteventID).data( "eventmax" );//check number of students allowed in stored data
	//compare amount assigned to maximum students allowed
	if(eventAssigned==eventMax){
		return;
	}
	else if(eventAssigned>eventMax){
		var errorText = "Too MANY!";
		var errorClass ="text-danger";
	}
	else if(eventAssigned<eventMax){
		var errorText = "Too FEW!";
		var errorClass ="text-warning";
	}
	//print errors
	$("#eventtotal-"+tournamenteventID).append("<div class='"+errorClass+"'>"+errorText+"</div>");
}

//Calculate the number of events for a student
function tournamentCalculateStudent(studentID)
{
	if ($(".teammateStudent-"+studentID+" > input").length)
	{
		//Editors
		$("#studenttotal-"+studentID).text($(".teammateStudent-"+studentID+" input:checkbox:checked").length);
	}
	else {
		//Student view without edit priviledge
		$("#studenttotal-"+studentID).text($(".teammateStudent-"+studentID+" > .bi-check").length);
	}
}

//check to make sure student is not signed up for two events in the same time block
function tournamentCalculateTimeblock(studentID)
{
	//Reminder: jquery selector (*may be omitted) =  $('*[data-timeblock="22"]')
	$("#teammate-"+studentID+" .text-success").remove(); //remove old warning
	//console.log("studentID-"+studentID);
	$('[id^=timeblock-]').each(function (i,v)
	{
		//$('this').attr('id') contains the timeblockID
		var timeblockID = $(v).attr('id'); //includes "timeblockID-#"
		//console.log(timeblockID);
		var studentAssigned =0;
		if ($("."+$(v).attr('id')+".teammateStudent-"+studentID+" > input").length)
		{
			//Editors
			studentAssigned = $("."+$(v).attr('id')+".teammateStudent-"+studentID+" :checkbox:checked").length; //count number of students assigned
		}
		else {
			//Student view without edit priviledge
			studentAssigned = $("."+$(v).attr('id')+".teammateStudent-"+studentID+" > .bi-check").length; //count number of students assigned		}
		}
		if (studentAssigned >1){
			//print errors
			$("#teammate-"+studentID).append("<span class='text-danger'>***</span>");
			$("#teammate-"+studentID+" .text-danger")	.hover(
				function() {
					$( this ).append( $( "<div class='text-danger'> More than one event in timeBlock: "+$("#"+timeblockID).text()+"</div>" ) );
				}, function() {
					$( this ).find( "div" ).last().remove();
				}
			);
		}
	});
}

//Check student count, overassigned students to a timeblock, and events over assigned
function tournamentAssignCheckErrors()
{
	//TODO: tournamentTableMakeList('#tournamentTable','#changeme');  //change table into list on mobile

	//console.log("tournamentAssignCheckErrors");
	$('[id^=teammate-]').each(function (i,v)
	{
		var studentID = $(v).attr('id').split("-")[1]; //includes "timeblockID-#"
		tournamentCalculateTimeblock(studentID);
		tournamentCalculateStudent(studentID);
	});
	$('[id^=event-]').each(function (i,v)
	{
		var eventID = $(v).attr('id').split("-")[1]; //includes "timeblockID-#"
		tournamentCalculateEvent(eventID);
	});
}

///////////////////
///Officer and Event Leader functions
//////////////////
function officerRemove(myID, myName)
{
	if(confirm("Are you sure you want to remove " + myName + "(" + myID +") from their officer position?"))
	{
		leaderRemoveRow(myID,"officer", myName);
	}
}
function leaderRemove(myID, myName)
{
	if(confirm("Are you sure you want to remove " + myName + "(" + myID +") from their event leader position?"))
	{
		leaderRemoveRow(myID,"eventleader", myName);
	}
}

function leaderRemoveRow(myID,table, myName)
{
	// validate signup form on keyup and submit
	var request = $.ajax({
		url: "rowremove.php",
		cache: false,
		method: "POST",
		data: { myid: myID, mytable:table},
		dataType: "text"
	});

	request.done(function( html )
	{
		$(".text-success").remove(); //removes any old update notices
		if(html==1)
		{
			$("#" + table + "-" + myID).before("<div class='text-success'>"+myName+" removed permanently.</div>"); //add note to show modification
			$("#" + table + "-" + myID).remove();  //remove buttons in list
		}
		else {
			$("#" + table + "-" + myID).before("<div class='text-danger'>Removal Error:"+html+"</div>");
		}
	});

	request.fail(function( jqXHR, textStatus ) {
		$(".text-success").remove();
		$("#" + table + "-" + myID).before("<div class='text-danger' class='error'>Request failed:"+textStatus+"</div>");
	});
}


/* Load Summer Note summernote */
function loadSummerNoteButtons()
{
	//The below code causes a bootstrap error, but is necessary for dropdowns in summernote to work.
	let buttons = $('.note-editor button[data-toggle="dropdown"]');
	buttons.each((key, value)=>{
		$(value).attr('data-bs-toggle', 'dropdown');
	})
}
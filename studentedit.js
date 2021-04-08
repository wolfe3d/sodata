$().ready(function() {
	$("#eventAndPriority").hide();
	$("#coursesListDiv").hide();
});
function addEventChoice(student)
{
	//adds the event choice selection
 $("#eventAndPriority").clone().appendTo("#addEventsDiv").show();
 $("#addEventsDiv").append("<a id='addThisEvent' onclick='addEvent("+student+",this.id,this.value); return false;' href=''>Add</a>");
}
function removeEvent(value)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studenteventremove.php",
	 cache: false,
	 method: "POST",
	 data: { eventsChoiceID: value},
	 dataType: "html"
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
function addEvent(student, field,value)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studenteventadd.php",
	 cache: false,
	 method: "POST",
	 data: { studentID: student, eventID : $("#eventsList").val(), priority : $("#priorityList").val() }, //TODO: must add priority
	 dataType: "html"
 });

 request.done(function( html ) {
	 //$("label[for='" + field + "']").append(html);
	 $(".modified").remove(); //removes any old update notices
	 var eventID = parseInt(html);
	 if (eventID>0)
	 {
		 //returns the current update
		 $("#events").append("<div id='eventChoice-" + eventID + "'>"+ $("#eventsList option:selected").text() + " <a href='' onclick=\"removeEvent('" + eventID + "');return false;\">Remove</a> <span class='modified' style='color:blue'>Event added.</span></div>");
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
function addCoursesChoice(student, table)
{
	//adds the courses list selection
 $("#coursesListDiv").appendTo("#add"+table+"Div").show();
 $("#addThisCourse").remove();
 $(".addCoursesBtn").show();
 $("#add"+table+"Div").append("<a id='addThisCourse' onclick=\"addCourse('"+student+"','"+table+"'); return false;\" href=''>Add</a>");
}
function removeCourse(value, table)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studentcourseremove.php",
	 cache: false,
	 method: "POST",
	 data: { tableName : table, myCourseID : value},
	 dataType: "html"
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
function addCourse(student, table)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studentcourseadd.php",
	 cache: false,
	 method: "POST",
	 data: { studentID: student, tableName : table, courseID : $("#coursesList").val() }, //TODO: must add priority
	 dataType: "html"
 });

 request.done(function( html ) {
	 $(".modified").remove(); //removes any old update notices
	 var myCourseID = parseInt(html);
	 if (myCourseID>0)
	 {
		 //returns the current update
		 $("#" + table).append("<div id='" + table + "-" + myCourseID + "'>"+ $("#coursesList option:selected").text() + " <a href='' onclick=\"removeCourse('" + myCourseID + "','"+table+"');return false;\">Remove</a> <span class='modified' style='color:blue'>Course added.</span></div>");
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
function updateStudent(student, field,value)
{
 // validate signup form on keyup and submit
 var request = $.ajax({
	 url: "studentupdate.php",
	 cache: false,
	 method: "POST",
	 data: { studentID: student, myfield : field, myvalue : value },
	 dataType: "html"
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

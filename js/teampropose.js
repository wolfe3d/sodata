
function myTimeBlock(timeblock) {
	console.log( timeblock);
}

//use highest scores from students
function proposeByScore(team)
{
	//Be aware: same event with same timeblock causes problem; however,there should not be same event with two different times
	//Do not allow 2 or more timeblocks for the same event!
	var thisYearOnly = document.getElementById('thisYear').checked;
	var request = $.ajax({
		url: "tournamentteamproposefunctionstimeblocks.php",
		cache: false,
		method: "POST",
		data: { myID: $("#tournamentID").html()},
		dataType: "text"
	});

	request.done(function( html ) {
		console.log("timeblocksAll");
		var timeblocks = JSON.parse(html);
		if(!Array.isArray(timeblocks))
		{
			console.error("timeblocks is not an array");
		}
		var requestScore = $.ajax({
			url: "tournamentteamproposefunctionstopscore.php",
			cache: false,
			method: "POST",
			data: { myID: team, thisYear: thisYearOnly},
			dataType: "text"
		});

		requestScore.done(function( html ) {
			const teammateScores = JSON.parse(html);
			var resultsTable = [];
			var timeblockTable = [];
			var studentTable = [];
			if(!Array.isArray(timeblocks))
			{
				console.error("timeblocks is not an array");
			}
			calculateByTopScore(teammateScores, timeblocks, studentTable, timeblockTable, resultsTable)
			//studentsTable = getUniqueStudents(resultsTable);
			console.log(resultsTable);
			console.log(timeblockTable);
			console.log(studentTable);
			printTable("topScore", "topScoreTable", studentTable, timeblockTable, resultsTable)
			//TODO: Print table
		});
	});
}


//use highest scores from students and rotates from highest to lowest and finds the highest combination
function proposeByBruteForce(team)
{
	//Be aware: same event with same timeblock causes problem; however,there should not be same event with two different times
	//Do not allow 2 or more timeblocks for the same event!
	var thisYearOnly = document.getElementById('thisYear').checked;

	var request = $.ajax({
		url: "tournamentteamproposefunctionstimeblocks.php",
		cache: false,
		method: "POST",
		data: { myID: $("#tournamentID").html()},
		dataType: "text"
	});

	request.done(function( html ) {
		console.log("timeblocksAll");
		var timeblocks = JSON.parse(html);
		if(!Array.isArray(timeblocks))
		{
			console.error("timeblocks is not an array");
		}
		var requestScore = $.ajax({
			url: "tournamentteamproposefunctionstopscore.php",
			cache: false,
			method: "POST",
			data: { myID: team, thisYear: thisYearOnly},
			dataType: "text"
		});

		requestScore.done(function( html ) {
			const teammateScores = JSON.parse(html);
			var resultsTable = [];
			var timeblockTable = [];
			var studentTable = [];
			if(!Array.isArray(timeblocks))
			{
				console.error("timeblocks is not an array");
			}
			calculateByBruteForce(teammateScores, timeblocks, studentTable, timeblockTable, resultsTable)
		});


		requestScore.fail(function( jqXHR, textStatus ) {
			$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div");
		});
	});
}

//use students looks for events from lowest timeblock to highest timeblock, then finds highest scoring team with all events
function proposeByAllForce(team)
{
	//Be aware: same event with same timeblock causes problem; however,there should not be same event with two different times
	//Do not allow 2 or more timeblocks for the same event!
	var thisYearOnly = document.getElementById('thisYear').checked;

	var request = $.ajax({
		url: "tournamentteamproposefunctionstimeblocks.php",
		cache: false,
		method: "POST",
		data: { myID: $("#tournamentID").html()},
		dataType: "text"
	});

	request.done(function( html ) {
		console.log("timeblocksAll");
		var timeblocks = JSON.parse(html);
		if(!Array.isArray(timeblocks))
		{
			console.error("timeblocks is not an array");
		}
		var requestScore = $.ajax({
			url: "tournamentteamproposefunctionstopscore.php",
			cache: false,
			method: "POST",
			data: { myID: team, thisYear: thisYearOnly},
			dataType: "text"
		});

		requestScore.done(function( html ) {
			const teammateScores = JSON.parse(html);
			var resultsTable = [];
			var timeblockTable = [];
			var studentTable = [];
			if(!Array.isArray(timeblocks))
			{
				console.error("timeblocks is not an array");
			}
			calculateByAllForce(teammateScores, timeblocks, studentTable, timeblockTable, resultsTable)
		});


		requestScore.fail(function( jqXHR, textStatus ) {
			$("#note").html("<div class='text-danger'>Change Error:"+textStatus+"</div");
		});
	});
}

//copy events from the bruteforce array into the field below
function assign(s)
{
	resultsTables[s].forEach((row)=>{
		inputBtn = $(".teammateStudent-"+ row['studentID'] + ".event-" + row['eventID'] + " input");
		if (inputBtn && !inputBtn.is(":checked"))
		{
			inputBtn.trigger( "click" );
		}
	});
}

function findStudents(arrayList)
{
	let students = [];
	if(Array.isArray(arrayList))
	{
		arrayList.forEach((teammate)=>{
			let studentFound = 0;
			students.forEach((student)=>{
				if(student.studentID==teammate.studentID)
				{
					studentFound = 1;
				}
			});
			if(!studentFound)
			{
				students.push(teammate);
			}
		});
	}
	return students;
}

function findStudentEvents(studentList,teammateID)
{
	let events = 0;
	if(Array.isArray(studentList))
	{
		studentList.forEach((teammate)=>{
			if(teammate.studentID==teammateID)
			{
				events +=1;
			}
		});
	}
	return events;
}

//get Current Science Olympiad year
function getCurrentSOYear(monthGraduating=6)
{
	const d = new Date();
	if (d.getMonth()>monthGraduating)
	{
		return d.getFullYear()+1;
	}
	return d.getFullYear();
}

//finds the unique student number in the list
function countStudentTotal(studentList)
{
	return getUniqueStudents(studentList).length;
}

//get Unique students in list
function getUniqueStudents(studentList)
{
	let students =[];
	if(Array.isArray(studentList))
	{
		studentList.forEach((teammate)=>{
			studentAdded = 0;
			students.forEach((student)=>{
				if(teammate.studentID==student.studentID)
				{
					studentAdded = 1; //if id alrady added
				}
			});
			if(!studentAdded)
			{
				//unique id
				students.push(teammate);
			}
		});
	}
	return students;
}

//get student's grade from the their graduation years
//TODO: Should be updated with database year
function getStudentGrade(yearGraduating, monthGraduating=6)
{
	const d = new Date();
	if (d.getMonth()>monthGraduating)
	{
		return 12-(yearGraduating-d.getFullYear()-1);
	}
	else
	{
		return 12-(yearGraduating-d.getFullYear());
	}
}

//finds the unique number of seniors in the list
function countSeniorTotal(studentList, $schoolID)
{
	let studentIDs =[];
	let thisYear = "";
	if(Array.isArray(studentList))
	{
		studentList.forEach((teammate)=>{
			if (teammate.yearGraduating==getCurrentSOYear())
			{
				studentIDadded = 0;
				studentIDs.forEach((id)=>{
					if(teammate.studentID==id)
					{
						studentIDadded = 1; //if id alrady added
					}
				});
				if(!studentIDadded)
				{
					//unique id
					studentIDs.push(teammate.studentID);
				}
			}
		});
	}
	return studentIDs.length;
}


//counts the number of students assigned in the event
function countStudentsInEvent(studentList, eventID)
{
	return findStudentsInEvent(studentList, eventID).length;
}

//return students in the event
function findStudentsInEvent(studentList, eventID)
{
	let students =[];
	if(Array.isArray(studentList))
	{
		studentList.forEach((teammate)=>{
			if (teammate.eventID==eventID)
			{
				students.push(teammate);
			}
		});
	}
	return students;
}

//checks to see if the student is already assigned to the event
function checkStudentInEvent(arrayTable, studentID, eventID)
{
	let found = 0;
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((teammate)=>{
			if (teammate.eventID==eventID && teammate.studentID ==studentID)
			{
				found = 1;
			}
		});
	}
	return found;
}

//adds to array if id does not exist
function addToArrayUnique(arrayTable, idName, rowAdd)
{
	let idFound = 0;
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			if (row[idName]==rowAdd[idName])
			{
				idFound = 1;
			}
		});
	}
	if(!idFound)
	{
		//push rowAdd into arraytable
		arrayTable.push(rowAdd);
	}
}

//finds the unique student number in the list
function countUniqueWithID(arrayTable,idName,id)
{
	return getUniqueWithID(arrayTable,idName,id).length;
}

//get Unique students in list
function getUniqueWithID(arrayTable,idName,id)
{
	let rowsReturn =[];
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			if(row[idName]==id)
			{
				rowsReturn.push(row);
			}
		});
	}
	return rowsReturn;
}

//see if something has both ids
function countWithTwoIDs(arrayTable,IDOne,IDTwo)
{
	return foundWithTwoIDs(arrayTable,IDOne,IDTwo).length;
}

//return rows if row has both ids
function foundWithTwoIDs(arrayTable,IDOne,IDTwo)
{
	var rowsFound = [];
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			if(row[IDOne[0]]==IDOne[1] && row[IDTwo[0]]==IDTwo[1])
			{
				rowsFound.push(row);
			}
		});
	}
	return rowsFound;
}

//find timeblock that was already assigned to an event
function findTimeBlockByEvent(arrayTable,eventID)
{
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			if(row["eventID"]==eventID)
			{
				return row['timeblockID'];
			}
		});
	}
	return 0;
}


//change student's event to a different timeblock
function modifyTimeblock(arrayTable,tournamenteventID, timeblockID, eventID, studentID)
{
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			if(row["eventID"]==eventID && row["studentID"]==studentID)
			{
				row["timeblockID"]=timeblockID;
				row["tournamenteventID"]=tournamenteventID;
			}
		});
	}
}

//reaassign student to different timeBlock
function reassignStudent(eventID, timeblocks, studentTable, timeblockTable, resultsTable)
{
	let assigned = 0;
	//check all available timeblocks for the eventID
	let eventTimeblocks =[];
	if(Array.isArray(timeblocks))
	{
		timeblocks.forEach((timeblock)=>{
			if(eventID==timeblock['eventID'])
			{
				eventTimeblocks.push(timeblock);
			}
		});
	}

	//if there is only one timeblock, do not change and return FALSE
	if(eventTimeblocks.length == 1)
	{
		//console.log("this event has only one timeslot");
		return 0;
	}

	//find other students already assigned to this eventID
	let teammates = findStudentsInEvent(resultsTable,eventID);
	if(Array.isArray(teammates))
	{
		teammates.forEach((teammate,n)=>{
			narrowedTimeblocks = [];
			//console.log("Attempting to reassign "+teammate['first']);

			//if there is another timeblock, then attempt to reassign the timeblock
			assignedTimeblock = findTimeBlockByEvent(resultsTable,teammate['eventID']);

			//find available timeblock and then add to output
			foundslot = 0;

			//New set of timeblocks for everyone already assinged
			let neweventTimeblocks = [];
			if(Array.isArray(eventTimeblocks))
			{
				eventTimeblocks.forEach((timeblock)=>{
					if(timeblock['timeblockID']!=assignedTimeblock)
					{
						foundslot = 1;
						//console.log("Timeblock start:"+timeblock['timeStart']+".");
						//if there is more than one teammate, make sure the second one is also assigned to the same timeblock
						//Check to see if student is already assigned to this timeblock
						if(!countWithTwoIDs(resultsTable,["timeblockID",timeblock['timeblockID']],["studentID",teammate['studentID']]))
						{
							narrowedTimeblocks.push(timeblock);
						}
						else {
							//console.log("Student already assigned to this timeblock ("+timeblock['timeblockID']+") Keep checking...");
						}
					}
					else
					{
						//console.log(" old timeblock...continue checking"); //will not ever be printed because the timeblocks that are available do not include this one
					}
				});
			}
			if(!foundslot)
			{
				//console.warn("No other free slots found for this student-failed");
				return 0;
			}
			eventTimeblocks = narrowedTimeblocks;
		});
	}
	assigned = 0;

	if(Array.isArray(teammates))
	{
		teammates.every((teammate,n)=>{
			if(Array.isArray(eventTimeblocks))
			{
				eventTimeblocks.every((timeblock)=>{
					if(!n || countWithTwoIDs(resultsTable,["eventID",timeblock['eventID']],["timeblockID",timeblock['timeblockID']]))
					{
						//console.log("-added from reassignTeammate");
						//modify these functions
						modifyTimeblock(resultsTable,timeblock['tournamenteventID'], timeblock['timeblockID'], eventID, teammate['studentID']);
						addToArrayUnique(timeblockTable,"timeblockID",{timeblockID:timeblock['timeblockID'], timeStart:timeblock['timeStart'], timeEnd:timeblock['timeEnd']});
						return false;
					}
					return true;
				});
			}
			return true;
		});
	}
	return assigned;
}

//assign student to timeBlock
function assignStudent(teammate, timeblocks, studentTable, timeblockTable, resultsTable)
{
	//console.log( " continuing to assign: (" + teammate['studentID'] +") " + teammate['last'] + ", " + teammate['first'] );
	//find available timeblock and then add to output
	//get number of students assigned to this event
	let countAssigned = countUniqueWithID(resultsTable,"eventID",teammate['eventID']);
	//if (for some reason) this event is already assigned the maximum number of people exit
	if (countAssigned >= teammate.numberStudents)
	{
		return 0;
	}
	//check to see if event is in this tournament
	if(!countUniqueWithID(timeblocks,"eventID",teammate['eventID']))
	{
		//console.warn(teammate['event'] + "(" + teammate['eventID'] + ") is not in this tournament");
		return 0;
	}
	//check to see if student is already assigned this event for this tournament
	if(countWithTwoIDs(resultsTable,["eventID",teammate['eventID']],["studentID",teammate['studentID']]))
	{
		//console.warn("This teammate is already assigned to this tournament in " + teammate['event']);
		return 0;
	}
	let availableTimeblocksForStudent = []; //keep track of available timeblocks for this teammate
	let availableTimeblocksForStudentofEvent = []; //keep track of available timeblocks for this teammate
	let assigned = false;
	if(Array.isArray(timeblocks))
	{
		timeblocks.every((timeblock)=>{
			//console.log("checking " + timeblock['event'] + " timeblock:"+timeblock['timeblockID']+" for student:"+teammate['studentID']);
			//check to see if the student is already assigned to this timeblock
			if(!countWithTwoIDs(resultsTable,["timeblockID",timeblock['timeblockID']],["studentID",teammate['studentID']]))
			{
				availableTimeblocksForStudent.push(timeblock);
				if(teammate['eventID']==timeblock['eventID'])
				{
					availableTimeblocksForStudentofEvent.push(timeblock);
					//console.log( " Timeblock ("+timeblock['timeblockID']+") start:"+timeblock['timeStart']+".");
					//Check to see if student is already assigned to this timeblock
					//check to see if this event has already been assigned a timeBlock, if it has been assigned is it this timeblock
					if(!countAssigned || countWithTwoIDs(resultsTable,["eventID",timeblock['eventID']],["timeblockID",timeblock['timeblockID']]))
					{
						//console.log( "- added from Assign");
						addToArrayUnique(studentTable, "studentID",{studentID:teammate['studentID'],last:teammate['last'],first:teammate['first'],yearGraduating:teammate['yearGraduating']});
						addToArrayUnique(timeblockTable,"timeblockID",{timeblockID:timeblock['timeblockID'], timeStart:timeblock['timeStart'], timeEnd:timeblock['timeEnd']});
						//addToArrayUnique(resultsTable,"tournmenteventID",{tournamenteventID:timeblock['tournamenteventID'],timeblockID: timeblock['timeblockID'], eventID:timeblock['eventID'], studentID:teammate['studentID'], note:teammate['note']});
						resultsTable.push({tournamenteventID:timeblock['tournamenteventID'],timeblockID: timeblock['timeblockID'], eventID:timeblock['eventID'], event:teammate['event'], studentID:teammate['studentID'], note:teammate['note']});
						assigned = true;
						return false;
					}
					else {
						//console.log( " This event is assigned to another timeblock. Keep checking...");
					}
				}
				else {
					//console.log(" This timeblock ("+timeblock['timeblockID']+") does not contain the event:"+teammate['event']+" Keep checking...");
				}
			}
			else
			{
				if(teammate['eventID']==timeblock['eventID'])
				{
					//console.log(" Student already assigned to this timeblock ("+timeblock['timeblockID']+") start:"+timeblock['timeStart']+" Keep checking...");
				}
				else {
					//Another event in this timeblock
				}
			}
			return true;
		});
	}
	else {
		console.error("timeblocks is not array");
		return 0;
	}

	if(assigned){
		return 1;
	}

	//console.warn("Could not find available match between "+teammate['event']+" and "+teammate['last']+", "+teammate['first']+"-failed");
	//console.warn(availableTimeblocksForStudentofEvent);
	//console.warn(availableTimeblocksForStudent);
	//TODO: If student is not available for event in the current available timeblocks, try moving another event with multiple timeblocks...
	//Go back and reassign partner to different timeslot if there is more than one time slot for the other event
	if(availableTimeblocksForStudentofEvent.length) //!reassigned = only attempt one reassignment
	{
		//console.warn("attempting to reassign student to a different timeblock");
		//Reassigning should work for multiple teammates
		let reassigned = reassignStudent(teammate['eventID'], availableTimeblocksForStudentofEvent, studentTable, timeblockTable, resultsTable);
		if(reassigned)
		{
			assignStudent(teammate, timeblocks, studentTable, timeblockTable, resultsTable);
		}
	}
}

//Calculate students in times and then fill in table to be read
function calculateByTopScore(studentList, timeblocks, studentTable, timeblockTable, resultsTable)
{
	//echo "<button class='btn btn-primary' type='button' onclick='javascript:$(\"#$resultsTableName\").toggle();'><span class='bi bi-journal-code'></span> Verbose</button></p><div id='$resultsTableName' style='display:none;'>";
	console.log("totalStudentsInOriginalList="+countStudentTotal(studentList));
	console.log("totalSeniorsInOriginalList="+countSeniorTotal(studentList));

	studentList.forEach(function (teammate, i) {
		myTeammateByTopScore(teammate, i, timeblocks, studentTable, timeblockTable, resultsTable)
	});
}
function myTeammateByTopScore(teammate, i, timeblocks, studentTable, timeblockTable, resultsTable) {
	//console.log(teammate);
	let studentAssigned = findStudentEvents(studentTable,teammate.studentID);
	let totalStudents = countStudentTotal(studentTable);
	let totalSeniors = countSeniorTotal(studentTable);
	console.log("totalStudentsAddedSoFar="+totalStudents+" totalSeniorsAddedSoFar="+totalSeniors);
	if(!Array.isArray(timeblocks))
	{
		console.error("timeblocks is not an array");
	}
	//Check to see that there is no more than 15 students assigned OR that this student has already been assigned
	//And check to see that there are no more than 7 seniors assigned
	let isSenior = teammate['yearGraduating']==getCurrentSOYear()?1:0;
	//console.log("note:"+teammate['note']+ ",first: " +teammate['first'] +",total:"+ totalStudents+",assigned:"+studentAssigned+",seniors:"+totalSeniors+",isSenior:"+isSenior);

	if((totalStudents < 15 && (!isSenior || totalSeniors < 7 )) || studentAssigned)
	{
		console.log("...attempting to add event: " + teammate.event + ".");//+ getEventName($teammate['eventID']) + ".");
		//get number of students assigned to this event
		let countAssigned = countStudentsInEvent(resultsTable,teammate.eventID);
		//check to see if this person has already been assigned to this event

		if(!checkStudentInEvent(resultsTable, teammate.eventID,teammate.studentID))
		{
			if(countAssigned<teammate.numberStudents) //numberStudents is the maximum amount the event can have
			{
				assignStudent(teammate, timeblocks, studentTable, timeblockTable, resultsTable);
			}
			else {
				console.log("Event full ("+countAssigned+" Students)-failed");
			}

		}
		else
		{
			teammate=increaseScore(resultsTable,teammate);
			console.log("Student already assigned to this event!-failed");
		}

	}
	else {
		if(totalStudents == 15)
		{
			console.log("15 students already assigned to team!-failed");
		}
		else if(totalSeniors == 7)
		{
			console.log("7 seniors already assigned to team!-failed");
		}
	}
	//TODO: Add algorithm to add students to fill spots.
}


//calculate the score of a results table using the note in the array
function calculateScore(arrayTable)
{
	var score = 0;
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			score += parseFloat(row['note']);
		});
	}
	return score;
}
//increase score in note of teammate if it is larger
function increaseScore(arrayTable,teammate)
{
	var score = 0;
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			if(teammate['studentID']==row['studentID'] && teammate['eventID']==row['eventID'])
			{
				if (parseFloat(teammate['note'])> parseFloat(row['note']))
				{
					row['note'] == teammate['note'];
				}
			}
		});
	}
	return 1;
}
//Brute Force methods

function permutation(array) {
	function p(array, temp) {
		var i, x;
		if (!array.length) {
			result.push(temp);
		}
		for (i = 0; i < array.length; i++) {
			x = array.splice(i, 1)[0];
			p(array, temp.concat(x));
			array.splice(i, 0, x);
		}
	}

	var result = [];
	p(array, []);
	return result;
}



//Calculate students in times and then fill in table to be read
var studentTables = [];
var timeblockTables = [];
var resultsTables = [];
function calculateByBruteForce(studentList, timeblocks, studentTable, timeblockTable, resultsTable)
{
	//echo "<button class='btn btn-primary' type='button' onclick='javascript:$(\"#$resultsTableName\").toggle();'><span class='bi bi-journal-code'></span> Verbose</button></p><div id='$resultsTableName' style='display:none;'>";
	console.log("totalStudentsInOriginalList="+countStudentTotal(studentList));
	console.log("totalSeniorsInOriginalList="+countSeniorTotal(studentList));
	//let myStudents = findStudents(studentList);
	var maxScore = [0,0,0];
	var maxEvents = [0,0,0]

	//console.time('t1');

	//var studentPerm = permutation([0,1,2,3,4,5]);
	//var studentPerm = permutation(myStudents); //the number of permutations for 14 students is crazy large
	//maybe take block of studentlist and split it into 5 blocks and permutate the five blocks
	//Or switch top 5 students order.
	//console.timeEnd('t1');
	//console.log(studentPerm);

	for (let i = 0; i < studentList.length; i++) {
		studentTable = [];
		timeblockTable = [];
		resultsTable = [];
		myTeammateByBruteForce(studentList, i, i, studentList[i], timeblocks, studentTable, timeblockTable, resultsTable)
		score = calculateScore(resultsTable);
		events = countEvents(resultsTable);
	/*for (let s = 0; s < maxScore.length; s++) {
			if(events > maxEvents[s] || (events == maxEvents[s] && score > maxScore[s]) || (events == maxEvents[s] && score== maxScore[s] && resultsTable.length > resultsTables[s].length))
			{
				maxScore[s]=score;
				maxEvents[s]=events;
				studentTables[s]=studentTable;
				timeblockTables[s]=timeblockTable;
				resultsTables[s]=resultsTable;
				$("#bruteForce").append("<p>New Max Score["+s+"]="+score+"</p>");
				break;
			}
		}*/
		if(events > maxEvents[0] || (events == maxEvents[0] && score > maxScore[0]) || (events == maxEvents[0] && score== maxScore[0] && resultsTable.length > resultsTables[0].length))
		{
			maxScore[0]=score;
			maxEvents[0]=events;
			studentTables[0]=studentTable;
			timeblockTables[0]=timeblockTable;
			resultsTables[0]=resultsTable;
			$("#bruteForce").append("<p>Best Team New Max Score["+0+"]="+score+"</p>");
		}
		else if(events > maxEvents[1] || (events == maxEvents[1] && score > maxScore[1]))
		{
			maxScore[1]=score;
			maxEvents[1]=events;
			studentTables[1]=studentTable;
			timeblockTables[1]=timeblockTable;
			resultsTables[1]=resultsTable;
			$("#bruteForce").append("<p>Filled Event New Max Score["+1+"]="+score+"</p>");
		}
		else if(score > maxScore[2])
		{
			maxScore[2]=score;
			maxEvents[2]=events;
			studentTables[2]=studentTable;
			timeblockTables[2]=timeblockTable;
			resultsTables[2]=resultsTable;
			$("#bruteForce").append("<p>Best Scoring (missing events) New Max Score["+2+"]="+score+"</p>");
		}
		else {
			$("#bruteForce").append("<p>Team ignored="+score+"</p>");

		}
	}
	for (let s = 0; s < maxScore.length; s++) {
		console.log(resultsTables[s]);
		console.log(timeblockTables[s]);
		console.log(studentTables[s]);
		printTable("bruteForce", "bruteTable"+s, studentTables[s], timeblockTables[s], resultsTables[s])
		$("#bruteForce").append("<p><a class='btn btn-info' role='button' href='javascript:assign("+s+")'><span class='bi bi-arrow-down-circle'></span> Enter Assignments from this table</a></p>");
	}

}

function myTeammateByBruteForce(studentList, i, r, teammate, timeblocks, studentTable, timeblockTable, resultsTable)
{
	//console.log(teammate);
	let studentAssigned = findStudentEvents(studentTable,teammate.studentID);
	let totalStudents = countStudentTotal(studentTable);
	let totalSeniors = countSeniorTotal(studentTable);
	//console.log("totalStudentsAddedSoFar="+totalStudents+" totalSeniorsAddedSoFar="+totalSeniors);
	if(!Array.isArray(timeblocks))
	{
		console.error("timeblocks is not an array");
	}
	//Check to see that there is no more than 15 students assigned OR that this student has already been assigned
	//And check to see that there are no more than 7 seniors assigned
	let isSenior = teammate['yearGraduating']==getCurrentSOYear()?1:0;
	//console.log("note:"+teammate['note']+ ",first: " +teammate['first'] +",total:"+ totalStudents+",assigned:"+studentAssigned+",seniors:"+totalSeniors+",isSenior:"+isSenior);

	if((totalStudents < 15 && (!isSenior || totalSeniors < 7 )) || studentAssigned)
	{
		//console.log("...attempting to add event: " + teammate.event + ".");//+ getEventName($teammate['eventID']) + ".");
		//get number of students assigned to this event
		let countAssigned = countStudentsInEvent(resultsTable,teammate.eventID);
		//check to see if this person has already been assigned to this event

		if(!checkStudentInEvent(resultsTable,teammate.eventID,teammate.studentID))
		{
			if(countAssigned<teammate.numberStudents) //numberStudents is the maximum amount the event can have
			{
				assignStudent(teammate, timeblocks, studentTable, timeblockTable, resultsTable);
			}
			else {
				//console.log("Event full ("+countAssigned+" Students)-failed");
			}

		}
		else
		{
			increaseScore(resultsTable,teammate);
			//console.log("Student already assigned to this event!-failed");
		}

	}
	else {
		if(totalStudents == 15)
		{
			//console.log("15 students already assigned to team!-failed");
		}
		else if(totalSeniors == 7)
		{
			//console.log("7 seniors already assigned to team!-failed");
		}
	}
	//TODO: Add algorithm to add students to fill spots.
	i+=1;
	if (i<studentList.length)
	{
		myTeammateByBruteForce(studentList, i, r,studentList[i], timeblocks, studentTable, timeblockTable, resultsTable)
	}
	else if (i<(studentList.length+r)) {
		let n = i-studentList.length
		myTeammateByBruteForce(studentList,i, r, studentList[n], timeblocks, studentTable, timeblockTable, resultsTable)
	}
	else {
		//end loop
	}
}
//finds the number of events that were assigned
function countEvents(arrayTable)
{
	let events =[];
	if(Array.isArray(arrayTable))
	{
		arrayTable.forEach((row)=>{
			let eventAdded = 0;
			events.forEach((event)=>{
				if(event['eventID']==row['eventID'])
				{
					eventAdded = 1; //if id alrady added
				}
			});
			if(!eventAdded)
			{
				//unique id
				events.push(row);
			}
		});
	}
	return events.length;
}
//finds the number of timeblocks that were assigned
function countTimeBlock(results, timeblockID)
{
	let rows =[];
	if(Array.isArray(results))
	{
		results.forEach((result)=>{
			if (result.timeblockID==timeblockID)
			{
				rowAdded = 0;
				rows.forEach((id)=>{
					if(result.timeblockID==id)
					{
						studentIDadded = 1; //if id alrady added
					}
				});
				if(!studentIDadded)
				{
					//unique id
					rows.push(result.timeblockID);
				}
			}
		});
	}
	return rows.length;
}
//finds the number of timeblocks that were assigned
function countEventsinTimeBlock(results, timeblockID)
{
	return findEventsinTimeBlock(results, timeblockID).length;
}

//finds the events of timeblocks that were assigned
function findEventsinTimeBlock(tableArray, timeblockID)
{
	let rows =[];
	if(Array.isArray(tableArray))
	{
		tableArray.forEach((event)=>{
			if (event.timeblockID==timeblockID)
			{
				let rowduplicate = 0;
				rows.forEach((row)=>{
					if(row.eventID==event.eventID)
					{
						rowduplicate =1;
					}
				});
				if(!rowduplicate)
				{
					rows.push(event);
				}
			}
		});
	}
	return rows;
}

function rainbow(i) {
	//var colorIncrement = Math.floor(360/colors);
	let light = "60%"; //lightnes of HSL
	let sat = "100%";
	let t=0.3;
	//hue of color
	let n = i*42;  //pick a number that does not divide evenly into 360, so that the colors don't repeat.
	let hue = n-Math.floor(n/360)*360; //360 is the highest color, so after 360 the number returns to around zero
	return 'hsla('+hue+','+sat+','+light+','+t+')';
}

//Remove text with parenthesis.  This is used to remove names in parenthesis for alphabetizing in tournaments.
function removeParenthesisText(string)
{
	return string.replace("/\([^)]+\)/","");
}

function printTable(divID, tableName, studentTable, timeblockTable, resultsTable)
{
	let notescore = 0;
	//Run through times and figure out the number of different dates and print columns with colspan of times for that date
	let output ="<table id='"+tableName+"' class='tournament table table-hover'><thead><tr><th rowspan='2' style='vertical-align:bottom;'><div>Students</div></th><th rowspan='3' style='vertical-align:bottom;'>Grade</th>";
	let border = "";
	//sort timeblocksdata.sort(function (a, b) {
	timeblockTable = timeblockTable.sort(function(a, b){
		let aDate = new Date(a['timeStart']);
		let bDate = new Date(b['timeStart']);
		return aDate.getTime() - bDate.getTime();
	})

	//sort studentTable
	studentTable.sort(function (a, b) {
		if (a.last < b.last) {
			return -1;
		}
		if (a.last > b.last) {
			return 1;
		}
		if (a.last == b. last)
		{
			if (a.first < b.first) {
				return -1;
			}
			if (a.first > b.first) {
				return 1;
			}
		}
		return 0;
	});



	//sort resultsTable
	resultsTable.sort(function (a, b) {
		if (a.event < b.event) {
			return -1;
		}
		if (a.event > b.event) {
			return 1;
		}
		return 0;
	});

	//$("#"+divID).html("<table id='tournamentTable"+divID+"' class='tournament table table-hover'><tbody></tbody><table>");
	//let thead = "<th rowspan='2' style='vertical-align:bottom;'><div>Students</div></th><th rowspan='3' style='vertical-align:bottom;'>Grade</th>";
	var dateCheck = "";
	let dateColSpan = 0;
	let dateCount = 0;
	let timeblocks =  [];
	console.log(resultsTable);
	if(Array.isArray(timeblockTable))
	{
		timeblockTable.forEach(function (timeblock, index) {
			timeblock['eventNumber'] = countEventsinTimeBlock(resultsTable,timeblock['timeblockID']);
			console.log("eventNumber"+timeblock['eventNumber'] );
			//timeblocks.push(timeblock);
			console.log(timeblock);
			if(dateCheck==""){
				const dateTemp = new Date(timeblock["timeStart"]);
				dateCheck=dateTemp.toDateString(); //date("F j, Y",strtotime(timeblock["timeStart"]));
				dateColSpan = timeblock['eventNumber'];
			}
			else {
				const dateTemp = new Date(timeblock["timeStart"]);
				if(dateCheck!=dateTemp.toDateString()){
					output += "<th colspan='"+dateColSpan+"' style='border-right:2px solid black;text-align:center;'>" +dateCheck +"</th>";
					dateCheck=dateTemp.toDateString(); //date("F j, Y",strtotime(timeblock["timeStart"]));
					dateColSpan = timeblock['eventNumber'];
					dateCount +=1;
					//timeblock['border'] = "border-left:2px solid black; "; //adds border at beginning of new date
				}
				else {
					dateColSpan += timeblock['eventNumber'];
				}
			}
		});
	}
	//("#tournamentTable" + divID).append("<thead><tr>"+thead+"</tr></thead>");


	output += "<th colspan='"+dateColSpan+"' style='text-align:center;'>" + dateCheck + "</th>";
	border = "border-left:2px solid black; ";
	output +="<th rowspan='2' style='"+border+" vertical-align:bottom;'>Total Events</th></tr>";

	//print the time for each event and date
	output +="<tr>";
	border = "border-left:2px solid black; ";
	if(Array.isArray(timeblocks))
	{
		timeblockTable.forEach(function (timeblock, i) {
			let eventNumber = timeblock['eventNumber'];
			const timeStart = new Date(timeblock["timeStart"]);
			const timeEnd = new Date(timeblock["timeEnd"]);

			output += "<th id='timeblock-"+timeblock['timeblockID']+"' colspan='"+eventNumber+"' style='"+border+" background-color:"+rainbow(i)+"'>" + timeStart.toLocaleTimeString() +" - " +  timeEnd.toLocaleTimeString()  +"</th>";
		});
	}
	output +="</tr>";

	//print the event under each time
	output +="<tr>";
	//put sorting for last and first name in this row
	output +="<th><a href='javascript:tournamentSort(`"+tableName+"`,`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`"+tableName+"`,`studentFirst`)'>First</a></th>";

	//Get events
	let totalEvents =0;
	timeblockTable.forEach(function (timeblock, i) {
		let timeEvents= timeblock['eventNumber'];
		findEventsinTimeBlock(resultsTable,timeblock['timeblockID']).forEach(function (row, n) {
			totalEvents += 1;
			border = "";
			if(timeEvents == timeblock['eventNumber'])
			{
				border = "border-left:2px solid black; ";
			}
			output += "<th id='event-"+row['tournamenteventID']+"' style='"+border+"background-color:"+rainbow(i)+"'><span>"+row['event']+"</span></th>";
			timeEvents -=1;
		});
	});
	border = "border-left:2px solid black; ";
	output +="<td id='studenttotal-empty' style='"+border+"'>"+totalEvents+"</td></tr></thead><tbody>";

	//Get students
	let totalSeniors = 0;
	studentTable.forEach(function (student, s) {
		//$studentTotal = 0;  //this is done in the javascript TODO: remove this line
		output +="<tr studentLast="+removeParenthesisText(student['last'])+"  studentFirst="+removeParenthesisText(student['first'])+">";

		//find student Grade
		let studentGrade = getStudentGrade(student['yearGraduating']);
		totalSeniors += studentGrade==12 ? 1:0;
		//output student column
		output +="<td class='student' id='teammate-"+student['studentID']+"'><a target='_blank' href='#student-details-"+student['studentID']+"'>"+student['last']+", " + student['first'] +"</a></td><td>"+studentGrade+"</td>";
		//Get events of students
		timeblockTable.forEach(function (timeblock, i) {
			let timeEvents= timeblock['eventNumber'];
			findEventsinTimeBlock(resultsTable,timeblock['timeblockID']).forEach(function (row, n) {
				border = "";
				if(timeEvents == timeblock['eventNumber'])
				{
					border = "border-left:2px solid black; ";
				}
				timeEvents -=1;
				//find result row that has this student (if the student is proposed to be on this event"
				let results = foundWithTwoIDs(resultsTable,["eventID",row['eventID']],["studentID",student["studentID"]]);
				if(results.length)
				{
					let myResult = results[0]; //uses only the first row.  There should only be one row returned.
					let checkbox = "teammateplace-"+myResult['tournamenteventID']+"-"+myResult['studentID'];
					let checkboxEvent = "timeblock-"+myResult['timeblockID']+" teammateEvent-"+myResult['tournamenteventID']+" teammateStudent-"+myResult['studentID'];
					output +="<td style='"+border+" background-color:"+rainbow(i)+"' class='"+checkboxEvent+"' data-timeblock='"+myResult['timeblockID']+"'>";
					output +="<div class='bi bi-clipboard-pulse'> ("+parseInt(myResult['note'])+")</div>";
					notescore += parseFloat(myResult['note']);
					output +="</td>";
				}
				else {
					output += "<td style='"+border+" background-color:"+rainbow(i)+"'></td>";
				}
			});
		});

		border = "border-left:2px solid black; ";
		output +="<td style='"+border+"' id='studenttotal-"+student['studentID']+"'>"+findStudentEvents(resultsTable,student['studentID'])+"</td></tr>";
	});
	/*TODO::
	else {
	exit("Make sure to add students to this team before this step!");
}
*/
//print the total signed up for each event
let errorSeniors = totalSeniors > 7 ? "<span class='error'>Too many</span>":"";
output +="</tbody><tfoot><tr><td><strong>"+studentTable.length+"</strong> Total Teammates</td><td><strong>"+totalSeniors+"</strong> Seniors "+errorSeniors+"</td>";
output +="</tr>";

output +="</tfoot></table></form>";

output +="<div>Score = "+notescore+"  (This can only be compared to the same type of table (not to different tables), i.e Average Score cannot be compared to Average Place.</div><br>";
$("#"+divID).append(output);

}

//get Unique timeblocks in list
function getUniqueTimeblocks(tableArray)
{
	let timeblocks =[];
	if(Array.isArray(tableArray))
	{
		tableArray.forEach((tournamentevent)=>{
			timeblockAdded = 0;
			timeblocks.forEach((timeblock)=>{
				if(timeblock.timeblockID==tournamentevent.timeblockID)
				{
					timeblockAdded = 1; //if id alrady added
				}
			});
			if(!timeblockAdded)
			{
				//unique id
				timeblockUnique = {timeblockID: tournamentevent.timeblockID, timeStart: tournamentevent.timeStart, timeEnd: tournamentevent.timeEnd};
				timeblocks.push(timeblockUnique);
			}1
		});
	}
	return timeblocks;
}



function findStudentIdsInEvent(studentList, eventID)
{
	let students =[];
	if(Array.isArray(studentList))
	{
		studentList.forEach((teammate)=>{
			if (teammate.eventID==eventID)
			{
				students.push(teammate.studentID);
			}
		});
	}
	return students;
}


/**
 * Generate all combinations of an array.
 * @param {Array} sourceArray - Array of input elements.
 * @param {number} comboLength - Desired length of combinations.
 * @return {Array} Array of combination arrays.
 */
 function generateCombinations(sourceArray, comboLength) {
const sourceLength = sourceArray.length;
if (comboLength > sourceLength) return [];

const combos = []; // Stores valid combinations as they are generated.

// Accepts a partial combination, an index into sourceArray, 
// and the number of elements required to be added to create a full-length combination.
// Called recursively to build combinations, adding subsequent elements at each call depth.
const makeNextCombos = (workingCombo, currentIndex, remainingCount) => {
  const oneAwayFromComboLength = remainingCount == 1;

  // For each element that remaines to be added to the working combination.
  for (let sourceIndex = currentIndex; sourceIndex < sourceLength; sourceIndex++) {
	// Get next (possibly partial) combination.
	const next = [ ...workingCombo, sourceArray[sourceIndex] ];

	if (oneAwayFromComboLength) {
	  // Combo of right length found, save it.
	  combos.push(next);
	}
	else {
	  // Otherwise go deeper to add more elements to the current partial combination.
	  makeNextCombos(next, sourceIndex + 1, remainingCount - 1);
	}
	  }
}

makeNextCombos([], 0, comboLength);
return combos;
}
function generateCombinationsWithLess(sourceArray, comboLength)
{
	var combi = [];
	//This will make all combos with the combolength and less
	for (let i = 1; i < comboLength+1; i++)
	{
		combi= combi.concat(generateCombinations(sourceArray,i));
	}
	return combi;
}
//Calculate students in each timeslot
function calculateByAllForce(teammateScores, tournamentEvents, studentTable, timeblockTable, resultsTable)
{
	var studentList = getUniqueStudents(teammateScores);
	console.log("totalStudentsInOriginalList="+studentList.length);
	console.log("totalSeniorsInOriginalList="+countSeniorTotal(studentList));
	//let myStudents = findStudents(studentList);
	var maxScore = [0,0,0];
	var maxEvents = [0,0,0]

	//get list of timeblocks

	//for timeblocks
	////for events  //also don't assign any students 
	//////for students  //make sure they have not been assigned to another event in the same timeblock
	////////for number of teammates

	//combine timeblocks
	////calculate score for each
	var results = [];
	var r = 0;
	var timeblocks = getUniqueTimeblocks(tournamentEvents);
	for (let t = 0; t < timeblocks.length; t++) {
		var timeblockevents = findEventsinTimeBlock(tournamentEvents,timeblocks[t].timeblockID);
		//console.log(timeblockevents);
		for (let e = 0; e < timeblockevents.length; e++) {
			var students = findStudentIdsInEvent(studentList, timeblockevents[e].eventID);
			var studentCombos = generateCombinationsWithLess(students,timeblockevents[e].numberStudents);
			studentCombos.push([0]);
			//console.log(studentCombos);
			//console.log(studentcombinations);
			for (let c = 0; c < studentCombos.length; c++) {
				//add all students in combination to the possible results
				//make sure to add one student per timeblock per result
				for (let s = 0; s < studentCombos.length; s++){
					results[t][e][c].push();//student with score
				}
			}
		}
	}
	score = calculateScore(resultsTable);
	events = countEvents(resultsTable);
}
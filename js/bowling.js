// Runs when <body> loads to reset the inputRowCells to hidden
function setHiddenElements() {
	let inputList = document.getElementsByClassName("inputRowCell");
	
	for(var i = 0; i < inputList.length; i++)
		inputList[i].style.visibility = "hidden";
}

// Sort by column - send desc for descending sort or nothing for ascending sort
function sortCurrentField(u,v,w) {
    document.location.href = "index.php?mn=" + u + "&cn=" + v + "&sort=" + w;
}

/* EDIT BUTTON */
function editBtnClicked(j) {
	var currentRow = document.getElementById("rowTracker").innerHTML;
	
	// If another edit button was clicked reset the previously selected row's buttons
//	if(currentRow != j) {
//		// Hide the delete button
//		document.getElementById("deleteBtn" + currentRow).style.visibility = "visible";
//		// Edit the span class to change icons on the edit button
//		var editBtn = document.getElementById("editBtn" + row);
//		var editChildren = editBtn.childNodes;
//		editChildren[1].classList.replace("glyphicon-edit", "glyphicon-pencil");
//	}
	
	
	// Store the row number in hidden element to be used by other functions
	document.getElementById("rowTracker").innerHTML = j;
	
	// Grab the children of editBtn, truly just want the span element with the icon class
	var editBtn = document.getElementById("editBtn" + j);
	var editChildren = editBtn.childNodes;
	
	// Perform operations only if this editBtn isn't currently in 'edit' state already
	//  - - checked by the bootstrap icon used in the class
	if(editChildren[1].className == "glyphicon glyphicon-pencil") {
		var inputList = document.getElementsByClassName("inputRowCell");
		
		// Grab table elements to transfer current values for row to be edited
		mybody = document.getElementsByTagName("body")[0];
		mytable = mybody.getElementsByTagName("table")[1];
		myrow = mytable.getElementsByTagName("tr")[j + 1];
		editDeleteCell = myrow.getElementsByTagName("td")[inputList.length];
		
		// Show the input boxes with current values for user wanting to edit a row
		for(var i = 0; i < inputList.length; i++) {
			inputList[i].style.visibility = "visible";
			inputList[i].value = myrow.getElementsByTagName("td")[i].innerHTML;
		}
		
		// Hide the delete button
		document.getElementById("deleteBtn" + j).style.visibility = "hidden";
		 
		// Edit the span class to change icons on the edit button
		var editBtn = document.getElementById("editBtn" + j);
		var editChildren = editBtn.childNodes;
		editChildren[1].classList.replace("glyphicon-pencil", "glyphicon-edit");
		
		// Change actionBtn text
		document.getElementById("actionBtn").innerHTML = "Update";
	}
}

/* DELETE BUTTON */
function deleteBtnClicked(j) {
	let row = j;
	let table = document.getElementById("tableTracker").innerHTML;
    
	var tableRow = document.getElementById("contentRow" + row);
	var tableRowChildren = tableRow.childNodes;
    
    // Here we set 3 'keys' and trackers for the column names for those keys
    let keyTracker1 = document.getElementById("keyTracker1").innerHTML;
    let keyTracker2 = document.getElementById("keyTracker2").innerHTML;
    let keyTracker3 = document.getElementById("keyTracker3").innerHTML;
    let key1 = tableRowChildren[1].innerHTML;
	let key2 = tableRowChildren[3].innerHTML;
	let key3 = tableRowChildren[5].innerHTML;
	
    let tableRef;
    
    // The switch statement only utilizes the true number of keys needed
    // based on the table that is having a row deleted and sets the
    // correct reference number to redirect back to that table view
    switch(table) {
        case "bowler":
            tableRef = 0;
            window.location.href = "deleteRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&tableRef=" + tableRef;
            break;
        case "competed_in":
            tableRef = 1;
            window.location.href = "deleteRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + "&tableRef=" + tableRef;
            break;
        case "competition":
            tableRef = 2;
           window.location.href = "deleteRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + "&primaryKey3=" + keyTracker3 + "&entry3=" + key3 + "&tableRef=" + tableRef;
            break;
        case "member_of":
            tableRef = 3;
            window.location.href = "deleteRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + "&primaryKey3=" + keyTracker3 + "&entry3=" + key3 + "&tableRef=" + tableRef;
            break;
        case "scores":
            tableRef = 4;
            window.location.href = "deleteRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + "&tableRef=" + tableRef;
            break;
        case "team":
            tableRef = 5;
            window.location.href = "deleteRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + "&tableRef=" + tableRef;
            break;
    }
}

/* ACTION BUTTON - holds state through innerHTML on which function to choose when clicked */
function actionBtnClicked() {
	let btnText = document.getElementById("actionBtn").innerHTML;
	 
	if(btnText == "New Row") {			// If user is wanting to add a New Row
		newRowBtnClicked();
	} else if(btnText == "Enter") {		// If user wants to send request to add the New Row
		enterBtnClicked();
	} else if(btnText == "Update") {	// If user edited a row and wants to save changes
		updateBtnClicked();
	} else {							// Else display an error message
		alert("Unexpected error...");
	}
	
}

/**** Action Button Functions depending on state ****/
/* NEW ROW BUTTON - for entering info for a new row */
function newRowBtnClicked() {
	var inputList = document.getElementsByClassName("inputRowCell");
	
	document.getElementById("actionBtn").innerHTML = "Enter";
	
	for(var i = 0; i < inputList.length; i++)
		inputList[i].style.visibility = "visible";
	
}

/* ENTER BUTTON - for submitting a new row */
function enterBtnClicked() {
	// Grab hidden tracking value
	let table = document.getElementById("tableTracker").innerHTML;
    let tableRef;
    
    // Store variable to display same table upon completion of task
    switch(table) {
        case "bowler":
            tableRef = 0;
            break;
        case "competed_in":
            tableRef = 1;
            break;
        case "competition":
            tableRef = 2;
            break;
        case "member_of":
            tableRef = 3;
            break;
        case "scores":
            tableRef = 4;
            break;
        case "team":
            tableRef = 5;
            break;
    }

	// Assign row an arbitrary value in order to retrieve numFields
	let row = 1;
	
	var tableRow = document.getElementById("contentRow" + row);
	var tableRowChildren = tableRow.childNodes;
	
	// Calculate # of fields to grab inputRowCells properly
	var numFields = (tableRowChildren.length - 3) / 2;
	
	// Push input cell values on array using ID
	for(var i = 0; i < numFields; i++) {
		window["val" + i] = document.getElementById("inputRowCell" + i).value;
	}
	
	var paramStr = "";	// String to insert in sql 
	
	// Build string to send in url as parameters
	for(var i = 0; i < numFields; i++) {
		paramStr += "&val" + i + "=" + window["val" + i];
	}
	
	// Build url here to send values in input fields to update.php
	document.location.href = "enterNewRow.php?table=" + table + paramStr + "&tableRef=" + tableRef;

}

/* UPDATE BUTTON - for after editing a row */
function updateBtnClicked() {
	// Grab all hidden tracking values
	let row = document.getElementById("rowTracker").innerHTML;
	let table = document.getElementById("tableTracker").innerHTML;
    
    var tableRow = document.getElementById("contentRow" + row);
	var tableRowChildren = tableRow.childNodes;
    
    let keyTracker1 = document.getElementById("keyTracker1").innerHTML;
    let keyTracker2 = document.getElementById("keyTracker2").innerHTML;
    let keyTracker3 = document.getElementById("keyTracker3").innerHTML;
    let key1 = tableRowChildren[1].innerHTML;
	let key2 = tableRowChildren[3].innerHTML;
	let key3 = tableRowChildren[5].innerHTML;
    
    let tableRef;
	
	// Calculate # of fields to grab inputRowCells properly
	var numFields = (tableRowChildren.length - 3) / 2;
	
	// Push input cell values on array using ID
	for(var i = 0; i < numFields; i++) {
		window["val" + i] = document.getElementById("inputRowCell" + i).value;
	}
	
	var paramStr = "";
	
	// Build string to send in url as parameters
	for(var i = 0; i < numFields; i++) {
		paramStr += "&val" + i + "=" + window["val" + i];
	}
    
    // The switch statement only utilizes the true number of keys needed
    // based on the table that is having a row deleted
    switch(table) {
        case "bowler":
            tableRef = 0;
            window.location.href = "updateRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + paramStr + "&tableRef=" + tableRef;
            break;
        case "competed_in":
            tableRef = 1;
            window.location.href = "updateRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + paramStr + "&tableRef=" + tableRef;
            break;
        case "competition":
            tableRef = 2;
           window.location.href = "updateRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + "&primaryKey3=" + keyTracker3 + "&entry3=" + key3 + paramStr + "&tableRef=" + tableRef;
            break;
        case "member_of":
            tableRef = 3;
            window.location.href = "updateRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + "&primaryKey3=" + keyTracker3 + "&entry3=" + key3 + paramStr + "&tableRef=" + tableRef;
            break;
        case "scores":
            tableRef = 4;
            window.location.href = "updateRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + paramStr + "&tableRef=" + tableRef;
            break;
        case "team":
            tableRef = 5;
            window.location.href = "updateRow.php?table=" + table + "&primaryKey1=" + keyTracker1 + "&entry1=" + key1 + "&primaryKey2=" + keyTracker2 + "&entry2=" + key2 + paramStr + "&tableRef=" + tableRef;
            break;
    }
}

function generatorBtnClicked() {
    genBtn = document.querySelector("#generatorBtn");
    genFields = document.querySelector(".generator_fields");
    
    // Toggle hidden class to reveal or hide the input fields
    genFields.classList.toggle("hidden");
    
    // Change button text
    if(genFields.classList.contains("hidden")) {
        genBtn.textContent = "Generator";
    } else {
        genBtn.textContent = "(Click me to cancel generating)";
    }
}

function confirmGenerateBtnClicked() {
    let table = document.getElementById("tableInput").value;
    let numOfRows = document.getElementById("rowInput").value;
    
    console.log(table);
    console.log(numOfRows);
    
    
}

function getErrorMessage() {
    const queryString = window.location.search;
    
    const urlParams = new URLSearchParams(queryString);
    
    const errorMsg = urlParams.get('error')
    
    document.querySelector(".error_message").textContent = errorMsg;
}











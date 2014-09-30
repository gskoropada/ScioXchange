/*
 * USER ADMINISTRATION
 * Provides client side functionality for the user administration panel.
 * Uses AJAX to communicate with server. 
 */

var firstRecord = 1;
var rows = 0;
var userCount = 0;
var selection = [];
var usersShown = [];
var selectAll = false;

var params = {offset:0,
			records:0};

$(function() {
	
	r = getCookie("rows");
		
	$("#numOfRows option[value='"+r+"']").prop("selected", true);
	
	rows = parseInt($("#numOfRows").val());
	
	params.offset = firstRecord-1;
	params.records = rows;
		
	$.ajax({url:'fetch_user_list.php',
		type: 'GET',
		data: params,
		dataType: 'json',
		success:  function(data) {

			updateUserList(data);
		}
		});
	
	$.ajax({url:'fetch_user_list.php',
		type: 'GET',
		data: {
			"function": "count_users"
		},
		success:  function(data) {

			userCount = data;
			
			$("#total_users").html(data);
		}
		});
	
	$("#numOfRows").change(function() { //Update user list when number of rows are changed.
		
		rows = parseInt($("#numOfRows").val());
		
		setCookie("rows", rows, 30);
		
		params.records = rows;
		params.offset = firstRecord-1;
		
		$.ajax({url:'fetch_user_list.php',
			type: 'GET',
			data: params,
			dataType: 'json',
			success:  function(data) {

				updateUserList(data);
			}
			});
	});
	
	$("#next").click(function() { //Display the next records
		changePage(1);
	});
	
	$("#previous").click(function() { //Display the previous records
		changePage(-1);
	});
	
	$("#select_all").click(function(){ //Selects all displayed users
		
		if($("#select_all").prop("checked")) {
		
			for(usr in usersShown) {
				if(selection.indexOf(usersShown[usr])==-1) {
					selection.push(usersShown[usr]);
				}
				$("#usr_"+usersShown[usr]).prop("checked", true);
				
			}

		} else {
			for(usr in usersShown) {
				if(selection.indexOf(usersShown[usr])!=-1) {
					selection.splice(selection.indexOf(usersShown[usr]),1);
				}
				$("#usr_"+usersShown[usr]).prop("checked", false);
				
			}
		}
	});
	
	$("#btnBulkGo").click(function() { //Executes the selected bulk action.
		bulkActions();
	});
	
});

/* Generates the user list dynamicaly from the data received from the server in a JSON
 * object. 
 */
function updateUserList(list) {

	var i;
	usersShown = [];
	
	selectAll = true;
	
	var userList = $("#user_list");
	
	userList.html("");
	
	for(i=0;i<list.length;i++) {
		
		usersShown.push(parseInt(list[i].UserID));
		
		if(selection.indexOf(parseInt(list[i].UserID))==-1) {
			selectAll = false;
		}
		
		var tr = $("<tr></tr>").prop("id","row_" + list[i].UserID);
		
		userList.append(tr);

		tr.append("<td><input type='checkbox' id='usr_"+ list[i].UserID +"' onclick='toggleSelection("+ list[i].UserID +")' " +
				chkSelected(list[i].UserID) + "></td>"); 
		tr.append("<td class='colUID'>" + list[i].UserID + "</td>");
		tr.append("<td class='colEmail'><a href='mailto:" + list[i].email + "' target='_blank'>" + list[i].email + "</a></td>");
		tr.append("<td class='colScreenName'>" + list[i].screenName + "</td>");
		tr.append("<td class='colRole'>" + getRole(parseInt(list[i].role)) + "</td>");
		tr.append("<td class='colReputation'>" + list[i].reputation + "</td>");
		tr.append("<td class='colActive'><input type='checkbox' " + chkBoxStatus(list[i].active) + " onclick='this.checked= !this.checked;' ></td>");
		tr.append("<td class='colModerated'><input type='checkbox' " + chkBoxStatus(list[i].moderated) + " onclick='this.checked= !this.checked;' ></td>");
		
		var tdOptions = $("<td></td>").addClass("colOptions");
		
		tr.append(tdOptions);
		
		tdOptions.append("<span class='click_option' onclick='deleteUsr(" + list[i].UserID + ");'><img src='images/del_usr_icon.png' class='icon' title='Delete user' /></span>");
		tdOptions.append("<span class='click_option' onclick='changeRole(" + list[i].UserID + "," + list[i].role + ");'><img src='images/change_role_icon.png' class='icon' title='Change role' /></span>");
		if (list[i].active!=1) {
			tdOptions.append("<span class='click_option' onclick='activateUsr(" + list[i].UserID + ");'><img src='images/act_usr_icon.png' class='icon' title='Activate user' /></span>");
		} else {
			tdOptions.append("<span class='click_option' onclick='deactivateUsr(" + list[i].UserID + ");'><img src='images/deact_usr_icon.png' class='icon' title='Deactivate user'/></span>");
		}
		if (list[i].moderated!=1){
			tdOptions.append("<span class ='click_option' onclick='moderateUser(" + list[i].UserID + ");'><img src='images/mod_usr_icon.png' class='icon' title='Moderate user' /></span>");
		} else {
			tdOptions.append("<span class ='click_option' onclick='removeModeration(" + list[i].UserID + ");'><img src='images/unmod_usr_icon.png' class='icon' title='Remove moderation' /></span>");
		}
		
		}
	
	$("#select_all").prop("checked", selectAll);

}

//Returns html valid code for checked checkboxes
function chkBoxStatus(s){
	
	if(s==1) {
		return "checked";
	} else {
		return "";
	}
	
}

//Toggles the selection status of an user.
function toggleSelection(userID) {
	
	var index = selection.indexOf(userID);
	
	if(index==-1) {
		selection.push(userID);
	} else {
		selection.splice(index,1);
		selectAll = false;
		$("#select_all").prop("checked", selectAll);
		
	}

}

//Check if an user is selected
function chkSelected(userID) {
	
	if(selection.indexOf(parseInt(userID))==-1) {
		return "";
	} else {
		return "checked";
	}
}

//This function displats either the next or previouse set of records.
function changePage(dir) {
	firstRecord = firstRecord + rows*dir;
	
	if(firstRecord<1) {
		firstRecord = 1;
	} else if(firstRecord > userCount) {
		firstRecord = firstRecord - rows;
	}
	
	$("#first_record").val(firstRecord);
			
	params.offset = firstRecord-1;
	
	$.ajax({url:'fetch_user_list.php',
		type: 'GET',
		data: params,
		dataType: 'json',
		success:  function(data) {
			
			updateUserList(data);
		}
		});

}

//Checks the role code for a user and returns a verbose description
function getRole(role) {
	
	var r;
	
	switch(role) {
	case 0:
		r = "User";
		break;
	case 1:
		r = "Moderator";
		break;
	case 2:
		r = "Administrator";
		break;
	default:
		r = "";
		break;
	}
	
	return r;
}

//Delete user function
function deleteUsr(id) {
	
	var user = $("#row_" + id + " > .colScreenName").text();
	
	if(confirm("You are about to delete user "+ user + "\nDo you confirm?")) {
		
		var options = {
				action:"du",
				id:id
		};
		
		params.offset = firstRecord-1;
		params.records = rows;
		
		$.ajax({url:'admin_tools.php',
			type: 'GET',
			data: options,
			success:  function(data) {
				console.info(data);
				$.ajax({url:'fetch_user_list.php',
					type: 'GET',
					data: params,
					dataType: 'json',
					success:  function(data) {
												
						updateUserList(data);
						
						$.ajax({url:'fetch_user_list.php',
							type: 'GET',
							data: {
								"function": "count_users"
							},
							success:  function(data) {

								userCount = data;
								
								$("#total_users").html(data);
							}
							});
					}
					});
			}
			});
	}
}

//Activate user function
function activateUsr(id) {
	
	var user = $("#row_" + id + " > .colScreenName").text();
	
	if (confirm("Do you want to activate user " + user + "?")) {
		var options = {
				action:"au",
				id:id
		};
		
		params.offset = firstRecord-1;
		params.records = rows;
		
		$.ajax({url:'admin_tools.php',
			type: 'GET',
			data: options,
			success:  function(data) {
				$.ajax({url:'fetch_user_list.php',
					type: 'GET',
					data: params,
					dataType: 'json',
					success:  function(data) {
						console.info(data);
						updateUserList(data);
					}
					});
			}
			});
	}
}

//Deactivate user function
function deactivateUsr(id) {
	
	var user = $("#row_" + id + " > .colScreenName").text();
	
	if (confirm("Do you want to deactivate user "+ user + "?")) {
		var options = { 
				action:"dau",
				id:id
		};
		
		params.offset = firstRecord-1;
		params.records = rows;
		
		$.ajax({url:'admin_tools.php',
			type: 'GET',
			data: options,
			success:  function(data) {
				$.ajax({url:'fetch_user_list.php',
					type: 'GET',
					data: params,
					dataType: 'json',
					success:  function(data) {
						console.info(data);
						updateUserList(data);
					}
					});
			}
			});
	}
}

//Moderate user function
function moderateUser(id) {
	
	var user = $("#row_" + id + " > .colScreenName").text();
	
	if (confirm("Do you want to moderate user "+ user + "?")) {
		var options = { 
				action:"mu",
				id:id
		};
		
		params.offset = firstRecord-1;
		params.records = rows;
		
		$.ajax({url:'admin_tools.php',
			type: 'GET',
			data: options,
			success:  function(data) {
				$.ajax({url:'fetch_user_list.php',
					type: 'GET',
					data: params,
					dataType: 'json',
					success:  function(data) {
						updateUserList(data);
					}
					});
			}
			});
	}
}

//Remove moderation function
function removeModeration(id) {
	
	var user = $("#row_" + id + " > .colScreenName").text();
	
	if (confirm("Do you want to remove moderation for user "+ user + "?")) {
		var options = { 
				action:"rmu",
				id:id
		};
		
		params.offset = firstRecord-1;
		params.records = rows;
		
		$.ajax({url:'admin_tools.php',
			type: 'GET',
			data: options,
			success:  function(data) {
				$.ajax({url:'fetch_user_list.php',
					type: 'GET',
					data: params,
					dataType: 'json',
					success:  function(data) {
						updateUserList(data);
					}
					});
			}
			});
	}
}

//Change role function. Displays a dynamic dialog for the user to chose the new role.
function changeRole(id, role) {

	var user = $("#row_" + id + " > .colScreenName").text();
	
	$("body").append("<div id='overlay' class='overlay'></div>");
	
	var chgRoleDialog = $("<div id='chgRoleDialog' class='dialog' ></div>");
	chgRoleDialog.css("width","450px");
	$("#overlay").append(chgRoleDialog);
	chgRoleDialog.append("<p>Change role for user <strong>" + user + "</strong></p>");
	chgRoleDialog.append("<p>Current role : " + getRole(role) + "</p>");
	chgRoleDialog.append("<label>Select new role: </label>");
	
	var i;
	
	var sel = $("<select id='newRole'></select>");
	
	for(i=0;i<=2;i++) {
		if(i!=role) {
			sel.append("<option value='" + i + "'>" + getRole(i) + "</option>");
		}
	}
	
	chgRoleDialog.append(sel);
	
	var options = $("<p></p>");
	
	options.append("<span class='click_option' id='setRole'>Set new role</span> | ");
	options.append("<span class='click_option' onclick=\"$('#overlay').remove()\">Cancel</span>");
	
	chgRoleDialog.append(options);
	
	$("#setRole").click(function() {
		var options = { 
				action: "cr",
				id:id,
				role: $("#newRole").val()
		};
		
		params.offset = firstRecord-1;
		params.records = rows;
		
		console.info(options);
		
		$.ajax({url:'admin_tools.php',
			type: 'GET',
			data: options,
			success:  function(data) {
				
				$("#overlay").remove();
				console.info(data);
				$.ajax({url:'fetch_user_list.php',
					type: 'GET',
					data: params,
					dataType: 'json',
					success:  function(data) {
						updateUserList(data);
					}
					});
			}
			});
	});
	
}

//Execute the different bulk actions.
function bulkActions() {
	var action = $("#bulk_action").val();
	var msg;
	
	switch(action) {
	case "bd":
		msg = "Do you want to delete the selected users?\nThis action CANNOT be undone!";
		break;
	case "ba":
		msg = "Do you want to activate the selected users?";
		break;
	case "bda":
		msg = "Do you want to deactivate the selected users?";
		break;
	case "bm":
		msg = "Do you want to moderate the selected users?";
		break;
	case "bum":
		msg = "Do you want to remove moderation from the selected users?";
		break;
	default:
		return false;
		break;
	}
	
	if (confirm(msg)) {
		var options = { 
				action: action,
				sel:selection
		};
		
		params.offset = firstRecord-1;
		params.records = rows;
		
		$.ajax({url:'admin_tools.php',
			type: 'GET',
			data: options,
			success:  function(data) {
				console.info(data);
				$.ajax({url:'fetch_user_list.php',
					type: 'GET',
					data: params,
					dataType: 'json',
					success:  function(data) {
						selection = [];
						updateUserList(data);
					}
					});
			}
			});
	}
	
}

//Displays a dialog for the user to set the filter on a specific field.
//Needs further refinement.
function filter(field) {
	var fieldName;
	
	$("body").append("<div id='overlay' class='overlay'></div>");
	var filtDialog = $("<div id='chgRoleDialog' class='dialog' ></div>");
	
	filtDialog.css("width","450px");
	$("#overlay").append(filtDialog);
	
	switch(field) {
	case "ID":
		fieldName = "UserID";
		break;
	case "EML":
		fieldName = "email";
		break;
	case "SCN":
		fieldName = "screenName";
		break;
	case "ROLE":
		fieldName = "role";
		break;
	case "ACT":
		fieldName = "activated";
		break;
	case "MOD":
		fieldName = "moderated";
		break;
	default:
		fieldName = "";
	}
	
	filtDialog.append("<p>Filter list on <strong>" + fieldName + "</strong></p>");
	filtDialog.append("<form id='filtForm'><label>Criteria : </label><input type='text' name='inCrit'><br></form>");
		
		var options = $("<p></p>");
	
	options.append("<span class='click_option' id='setFilter'>Filter</span> | ");
	options.append("<span class='click_option' onclick=\"$('#overlay').remove()\">Cancel</span>");
	
	filtDialog.append(options);
	
	$("#setFilter").click(function() {
		
		var crit = $(":input[name='inCrit']").val();
		
		$("#filtStatus").html("Filtered on " + fieldName + " (click to remove)");
		$("#filtStatus").addClass("click_option");
		$("#filtStatus").click(function() {
			$.ajax({url:'fetch_user_list.php',
				type: 'GET',
				data: {offset: firstRecord-1,
					records: rows},
				dataType: 'json',
				success:  function(data) {
					$("#filtStatus").html("");
					$("#filtStatus").removeClass();
					$("#filtStatus").unbind('click');
					updateUserList(data);
			}
			});
		});
		
		var options = { 
				filter: field,
				criteria: crit,
				offset: firstRecord-1,
				records: rows
		};
		
		$("#overlay").remove();
		
		$.ajax({url:'fetch_user_list.php',
			type: 'GET',
			data: options,
			dataType: 'json',
			success:  function(data) {
		
				updateUserList(data);
		}
		});
	});
}

//Toggles the sorting of the user list on the selected field.
function toggleSort(field) {

	var direction = "";
	var status = "";
	
	switch(field) {
	case "ID":
		status = $("#chUserID > #sort").text();
		$("th > #sort").text("");
		switch(status) {
		case "+":
			$("#chUserID > #sort").text("-");
			direction = "DESC";
			break;
		case "-":
			$("#chUserID > #sort").text("");
			direction = "";
			break;
		default:
			$("#chUserID > #sort").text("+");
			direction = "ASC";
			break;
		}
		break;
	case "EML":
		status = $("#chEmail > #sort").text();
		$("th > #sort").text("");
		switch(status) {
		case "+":
			$("#chEmail > #sort").text("-");
			direction = "DESC";
			break;
		case "-":
			$("#chEmail > #sort").text("");
			direction = "";
			break;
		default:
			$("#chEmail > #sort").text("+");
			direction = "ASC";
			break;
		}
		break;
	case "SCN":
		status = $("#chScreenName > #sort").text();
		$("th > #sort").text("");
		switch(status) {
		case "+":
			$("#chScreenName > #sort").text("-");
			direction = "DESC";
			break;
		case "-":
			$("#chScreenName > #sort").text("");
			direction = "";
			break;
		default:
			$("#chScreenName > #sort").text("+");
			direction = "ASC";
			break;
		}
		break;
	case "ROLE":
		status = $("#chRole > #sort").text();
		$("th > #sort").text("");
		switch(status) {
		case "+":
			$("#chRole > #sort").text("-");
			direction = "DESC";
			break;
		case "-":
			$("#chRole > #sort").text("");
			direction = "";
			break;
		default:
			$("#chRole > #sort").text("+");
			direction = "ASC";
			break;
		}
		break;
	case "REP":
		status = $("#chReputation > #sort").text();
		$("th > #sort").text("");
		switch(status) {
		case "+":
			$("#chReputation > #sort").text("-");
			direction = "DESC";
			break;
		case "-":
			$("#chReputation > #sort").text("");
			direction = "";
			break;
		default:
			$("#chReputation > #sort").text("+");
			direction = "ASC";
			break;
		}
		break;
	case "ACT":
		status = $("#chActive > #sort").text();
		$("th > #sort").text("");
		switch(status) {
		case "+":
			$("#chActive > #sort").text("-");
			direction = "DESC";
			break;
		case "-":
			$("#chActive > #sort").text("");
			direction = "";
			break;
		default:
			$("#chActive > #sort").text("+");
			direction = "ASC";
			break;
		}
		break;
	case "MOD":
		status = $("#chMod > #sort").text();
		$("th > #sort").text("");
		switch(status) {
		case "+":
			$("#chMod > #sort").text("-");
			direction = "DESC";
			break;
		case "-":
			$("#chMod > #sort").text("");
			direction = "";
			break;
		default:
			$("#chMod > #sort").text("+");
			direction = "ASC";
			break;
		}
		break;
	default:
		direction = "";
	}
	
	$.ajax({url:'fetch_user_list.php',
		type: 'GET',
		data: { 
			order: field,
			dir: direction,
			offset: firstRecord-1,
			records: rows
		},
		dataType: 'json',
		success:  function(data) {
			console.info(data);
			updateUserList(data);
	}
	});
	
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}
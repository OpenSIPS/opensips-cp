<script language="JavaScript">
//
// $Id: cdrviewer.main.js 40 2009-04-13 14:59:22Z iulia_bublea $
//

function changeState(id)
{
	if (document.getElementById(id+"_day").disabled==true) newState=false;
	else newState=true;
	document.getElementById(id+"_day").disabled=newState;
	document.getElementById(id+"_month").disabled=newState;
	document.getElementById(id+"_year").disabled=newState;
	document.getElementById(id+"_hour").disabled=newState;
	document.getElementById(id+"_minute").disabled=newState;
	document.getElementById(id+"_second").disabled=newState;


}


function changeState_cdr_field(){

	if (document.getElementById("search_regexp").disabled==true) newState=false;
	else newState=true;

	document.getElementById("search_regexp").disabled=newState ;

	if (document.getElementById("select_cdr_field").disabled==true) newState=false;
	else newState=true;

	document.getElementById("select_cdr_field").disabled=newState ;



}



function validate_cdr_export() {

	if (document.getElementById("search_regexp").value == "") {

		alert("Cannot export CDRs using these values!");

		return false ;

	}


	return true;

}


function select_dot(){

	// rows - how many modules ?
	var rows = parent.main_menu.document.getElementById("tbl_menu").rows.length ;

	var i ;

	for (i=0;i<rows;i++){

		// which one is siptrace ?
		if (parent.main_menu.document.getElementById("tbl_menu").rows[i] == parent.main_menu.document.getElementById("siptrace/"))

		break ;

	}

	parent.main_menu.select(i,rows);

}

</script>
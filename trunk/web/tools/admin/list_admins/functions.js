<script language="JavaScript">
//
//// $Id: list_admins.main.js 28 2009-04-01 15:27:03Z iulia_bublea $
////
//

function showhide(id){
        if (document.getElementById){
                obj = document.getElementById(id);
                if (obj.style.display == "none"){
                        obj.style.display = "";
                } else {
                        obj.style.display = "none";
                }
        }
}

    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }

function toggle(chkbox, group) { 
    var visSetting = (chkbox.checked) ? "visible" : "hidden"; 
    document.getElementById(group).style.visibility = visSetting; 
} 
</script>

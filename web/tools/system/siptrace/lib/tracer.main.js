<script language="JavaScript">
  
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

 if (newState == true){
		 document.getElementById(id+"_day").style.background="#d6d2d0";
		 document.getElementById(id+"_month").style.background="#d6d2d0";
		 document.getElementById(id+"_year").style.background="#d6d2d0";
		 document.getElementById(id+"_hour").style.background="#d6d2d0";
		 document.getElementById(id+"_minute").style.background="#d6d2d0";
		 document.getElementById(id+"_second").style.background="#d6d2d0";    
 	
 }
 else {
		 document.getElementById(id+"_day").style.background="#fff";
		 document.getElementById(id+"_month").style.background="#fff";
		 document.getElementById(id+"_year").style.background="#fff";
		 document.getElementById(id+"_hour").style.background="#fff";
		 document.getElementById(id+"_minute").style.background="#fff";
		 document.getElementById(id+"_second").style.background="#fff";    
 }
}


function confirmDelete_2nd()
{
 var agree=confirm("You are trying to delete ALL the traces. Is this okay ?");
 if (agree)	return true;
  else return false;
}


function confirmDelete()
{
 var agree=confirm("Are you sure you want to delete this/these Trace/Traces?");

 if (agree)	 {

 	if ((document.getElementById("search_regexp").value=="") && 
 		(document.getElementById("search_callid").value=="") &&
		(document.getElementById("search_traced_user").value=="") &&
		(document.getElementById("set_start").value=="") &&
		(document.getElementById("set_end").value=="")
		) {
 				
 			
		
			 var agree2=confirm("You are trying to delete ALL the traces. Is this okay ?");
			 if (agree2)	return true;
			  else return false;				
		
			}	
 	
 	return true ; 
 	
 }
   	
 else {

 	return false;
 
 }


}

// tooltip


// Free for any type of use so long as original notice remains unchanged.
// Report errors to feedback@ashishware.com
//Copyrights 2006, Ashish Patil , ashishware.com
//////////////////////////////////////////////////////////////////////////

function ToolTip(id,isAnimated,aniSpeed)
{ var isInit = -1;
  var div,divWidth,divHeight;
  var xincr=8,yincr=8;
  var animateToolTip =false;
  var html;
  
  function Init(id)
  {
   div = document.getElementById(id);
   if(div==null) return;
   
   if((div.style.width=="" || div.style.height==""))
   {alert("Both width and height must be set");
   return;}
   
   divWidth = parseInt(div.style.width);
   divHeight= parseInt(div.style.height);
   if(div.style.overflow!="hidden")div.style.overflow="hidden";
   if(div.style.display!="none")div.style.display="none";
   if(div.style.position!="absolute")div.style.position="absolute";
   
   if(isAnimated && aniSpeed>0)
   {xincr = parseInt(divWidth/aniSpeed);
    yincr = parseInt(divHeight/aniSpeed);
    animateToolTip = true;
    }
        
   isInit++; 
   
  }
  
    
  this.Show =  function(e,strHTML)
  {
    if(isInit<0) return;
    
    var newPosx,newPosy,height,width;
    if(typeof( document.documentElement.clientWidth ) == 'number' ){
    width = document.body.clientWidth;
    height = document.body.clientHeight;}
    else
    {
    width = parseInt(window.innerWidth);
    height = parseInt(window.innerHeight);
    
    }
    var curPosx = (e.x)?parseInt(e.x):parseInt(e.clientX);
    var curPosy = (e.y)?parseInt(e.y):parseInt(e.clientY);
    
    if(strHTML!=null)
    {html = strHTML;
     div.innerHTML=html;}
    
    if((curPosx+divWidth+8)< width)
    newPosx= curPosx+8;
    else
    newPosx = curPosx-divWidth;

    if((curPosy+divHeight)< height)
    newPosy= curPosy;
    else
    newPosy = curPosy-divHeight-8;

   if(window.pageYOffset)
   { newPosy= newPosy+ window.pageYOffset;
     newPosx = newPosx + window.pageXOffset;}
   else
   { newPosy= newPosy+ document.body.scrollTop;
     newPosx = newPosx + document.body.scrollLeft;}

    div.style.display='block';
    //debugger;
    //alert(document.body.scrollTop);
    div.style.top= newPosy + "px";
    div.style.left= newPosx+ "px";

    div.focus();
    if(animateToolTip){
    div.style.height= "0px";
    div.style.width= "0px";
    ToolTip.animate(div.id,divHeight,divWidth);}
      
    
    }

    

   this.Hide= function(e)
    {div.style.display='none';
    if(!animateToolTip)return;
    div.style.height= "0px";
    div.style.width= "0px";}
    
   this.SetHTML = function(strHTML)
   {html = strHTML;
    div.innerHTML=html;} 
    
    ToolTip.animate = function(a,iHeight,iWidth)
  { a = document.getElementById(a);
         
   var i = parseInt(a.style.width)+xincr ;
   var j = parseInt(a.style.height)+yincr;  
   
   if(i <= iWidth)
   {a.style.width = i+"px";}
   else
   {a.style.width = iWidth+"px";}
   
   if(j <= iHeight)
   {a.style.height = j+"px";}
   else
   {a.style.height = iHeight+"px";}
   
   if(!((i > iWidth) && (j > iHeight)))      
   setTimeout( "ToolTip.animate('"+a.id+"',"+iHeight+","+iWidth+")",1);
    }
    
   Init(id);
}
var t1=null;
function init_tooltip()
{
 t1 = new ToolTip("tooltip",false);
}

</script>

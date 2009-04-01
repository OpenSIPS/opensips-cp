<script language="JavaScript">
//
// $Id$
//
  
var numberOfClicks = 0;

function myButton_onclick()
{
   numberOfClicks++;
   window.document.form1.myButton.value = 'Button clicked ' + numberOfClicks + 
   ' times';
}

</script>

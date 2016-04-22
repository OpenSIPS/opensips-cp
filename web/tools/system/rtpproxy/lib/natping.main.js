<script language="JavaScript">
//
// $Id: natping.main.js 28 2009-04-01 15:27:03Z iulia_bublea $
//
  
var numberOfClicks = 0;

function myButton_onclick()
{
   numberOfClicks++;
   window.document.form1.myButton.value = 'Button clicked ' + numberOfClicks + 
   ' times';
}

</script>

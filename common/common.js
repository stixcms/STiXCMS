 function check4quotes(obj) {
 	if(obj) {
 		str=obj.value;
 		if(str.indexOf("'")>=0 || str.indexOf("\"")>=0) {
 			alert('Quotes not allowed!');
 			obj.value=obj.value.replace(/'/gi,"*");
 			obj.value=obj.value.replace(/"/gi,"*");
 		}
 	}
 }
 
 function checkdate(obj) {
 	if(obj) {
 		if(obj.value.length==0) return true;
 		if(obj.value.charAt(4)!='-' ||	obj.value.charAt(7)!='-') alert('Wrong date format! Must be YYYY-MM-DD');
 		if(obj.value.length>10) if(obj.value.charAt(13)!=':' || obj.value.length!=16) alert('Wrong date format! Must be YYYY-MM-DD HH:MM');
 	}	
 }
 
  function openPermWindow(frm) {
 	ouid=document.forms[frm].ouid.value;
 	ogid=document.forms[frm].ogid.value;
 	rp=document.forms[frm].rperms.value;
	window.open('security.php?formname='+frm+'&ouid='+ouid+'&ogid='+ogid+'&rp='+rp,'sec','width=380,height=250,scrollbars=auto');
 }
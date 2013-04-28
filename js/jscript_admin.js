function initial() {
	obj=document.getElementById('desc');
	if (obj.value=='Post your comments here...') { obj.value=''; } 
}

function initial_end() {
	if (obj.value=='') { obj.value='Post your comments here...'; }
}



function show_bar_window(sid,h,w)
{

  var popup = window.open(sid, "News",'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=' + h + ',height=' + w);


}



function DoDel(iserial)
{
	if (confirm("Are you sure?"))
	{
		window.location.href=iserial;
	}
}



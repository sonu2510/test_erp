//active inactive and delete 
function formsubmitsetaction(frmid,act,elemName,msg)
{
	document.getElementById("action").value = act;
	elem = document.getElementsByName(elemName);
	var flg = false;
	for(i=0;i<elem.length;i++){
		if(elem[i].checked)
		{
			flg = true;
			break;
		}
	}
	if(flg)
	{
		$("#myModal").modal("show");
		$(".modal-title").html(act.toUpperCase());
		$("#setmsg").html(msg);
		$("#popbtnok").show();
		$("#myModal").modal("show");
		$("#popbtnok").click(function(){
			document.getElementById(frmid).submit();
		});
	}
	else
	{
		//alert("Please select atlease one record");
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
}
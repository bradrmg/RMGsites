//funtion page

function Add(){
	$("#tblData tbody").append( 
			"<tr>"+ 
			"<td><input type='text'/></td>"+ 
			"<td><input type='text'/></td>"+ 
			"<td><input type='text'/></td>"+ 
			"<td><input type='text'/></td>"+ 
			"<td><img src='images/disk.png' class='btnSave'><img src='images/delete.png' class='btnDelete'/></td>"+ 
			"</tr>"); 
	$(".btnSave").bind("click", Save); 
	$(".btnDelete").bind("click", Delete); 
}; 


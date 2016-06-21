$(function(){

	function Add(){
		$("#agents tbody").append(
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

	function Edit(){
		var par = $(this).parent().parent(); //tr
		var pFname = par.children("td:nth-child(1)");
		var pLname = par.children("td:nth-child(2)");
		var pAgentID = par.children("td:nth-child(3)");
		var TSagentID = par.children("td:nth-child(4)");

		pFname.html("<input type='text' id='txtNome' value='"+pFname.html()+"'/>");
		pLname.html("<input type='text' id='txtTelefone' value='"+pLname.html()+"'/>");
		pAgentID.html("<input type='text' id='txtEmail' value='"+pAgentID.html()+"'/>");
		TSagentID.html("<img src='images/disk.png' class='btnSalvar'/>");

		$(".btnSave").bind("click", Save);
		$(".btnEdit").bind("click", Edit);
		$(".btnDelete").bind("click", Delete);
	};

	function Save(){
		var par = $(this).parent().parent(); //tr
		var pFname = par.children("td:nth-child(1)");
		var pLname = par.children("td:nth-child(2)");
		var pAgentID = par.children("td:nth-child(3)");
		var TSagentID = par.children("td:nth-child(4)");

		pFname.html(pFname.children("input[type=text]").val());
		pLname.html(pLname.children("input[type=text]").val());
		pAgentID.html(pAgentID.children("input[type=text]").val());
		TSagentID.html("<img src='images/delete.png' class='btnExcluir'/><img src='images/pencil.png' class='btnEditar'/>");

		$(".btnEdit").bind("click", Edit);
		$(".btnDelete").bind("click", Delete);
	};

	function Excluir(){
		var par = $(this).parent().parent(); //tr
		par.remove();
	};

	$(".btnEdit").bind("click", Edit);
	$(".btnDelete").bind("click", Delete);
	$("#btnAdd").bind("click", Add);			

});
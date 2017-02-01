<script language="javascript">
function excluir() {
	if(confirm("Tem certeza que deseja excluir?")) return true;
	else return false;
}

function pesquisa(){
	F1.target="";
	document.F1.submit();	
	document.F1.SearchText.value=""
}

</script>
	
<script src="./base/lib/jquery/jquery.mask.js"></script>

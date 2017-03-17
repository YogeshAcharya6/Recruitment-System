<?php
/* LSLmysql.php */
/**
* mysql_queryEH: mysql_query with Error Handling
* 
*/
function mysql_queryEH($conn,$qry, $errmsg='Error executing the query'){
	
    if ($resqry = $conn->query($qry)) 
		return $resqry;
    else 
		throw new Exception(mysql_error());
}

?>
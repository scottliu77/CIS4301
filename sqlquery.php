<?php

function sqlQuery($connection, $query)
{
	//fuction will complete an SQL Query. It can also return array of rows
	//if a select query is used

	//oci_parse takes in string of requested query and prepares it for execution
	$statement = oci_parse($connection, $query);
	
	//actually performs action to database, returns false if failed
	$execution = oci_execute($statement);
	if ($execution == true)
	{
		//echo "Query " . '"' . ($query) . '"' . " was successful.<br>";
	}
	else 
	{
		//echo "Query " . '"' . ($query) . '"' .
		//" was NOT successful.<br>";
	}

	if (oci_statement_type($statement) == "SELECT")
	{
		//oci_fetch_array creates an array that retrieves row of select query
		//can be indexed with name of attribute, such as $array['NAME']
		//which will return the name attribute select query

		//use oci_fetch_assoc to use associative arrays instead of numeric
		$i = 0;
		while (($row = oci_fetch_assoc($statement)) == true)
    	{
    		$arrayOfRows[$i] = $row;
    		$i++;
   		}
		return $arrayOfRows;
	}

	oci_free_statement($statement);
}

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
	</head>
	<body>

		<?php

			include 'sqlquery.php';

			$conn = oci_connect('gdiaz', 'Qazwsx12', '//oracle.cise.ufl.edu/orcl:1531');

			$submittedSummonerName = $_GET['summonername'];

			$queryString = "Select * from LOL where summoner_name = '" . $submittedSummonerName . "'";

			echo $queryString;

			$row = sqlQuery($conn, $queryString);

			echo '<table style="width:100%">
  				<tr>
					<th>Summoner ID</th>
					<th>Summoner Name</th>
					<th>Tier</th> 
					<th>Division</th>
					<th>Region</th>
					<th>Champion Points</th>
					<th>Champion Level</th>
					<th>Kills</th>
					<th>Deaths</th>
					<th>Assists</th>
					<th>KDA Ratio</th>
					<th>Games Won</th>
					<th>Games Lost</th>
					<th>Games Played</th>
					<th>Win Rate</th>
		  		</tr>';

			echo $row[0][SUMMONER_ID];



			oci_close($conn);


		?>

	<body>
<html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
	</head>
	<body>

		<?php

			include 'sqlquery.php';

			$conn = oci_connect('gdiaz', 'Qazwsx12', '//oracle.cise.ufl.edu/orcl:1531');

			//receive all variables
			$championName = $_GET['championName'];

			$numberOfEntries = $_GET['numEntries'];

			$sortBy = $_GET['sortBy'];

			echo '<h3>Top ' . $numberOfEntries . ' ' . $championName . ' Players by ' . $sortBy .  '</h3>';


			$order = "desc";
			if ($sortBy == "KDA")
			{
				$sortBy = "ckda";
			}
			else if ($sortBy == "Most Kills")
			{
				$sortBy = "ck";
			}
			else if ($sortBy == "Least Deaths")
			{
				$sortBy = "cd";
				$order = "asc";
			}
			else if($sortBy == "Win Rate")
			{
				$sortBy = "winrate";
			}



			$rankSelectString = "";

			$ranks = array('BRONZE', 'SILVER', 'GOLD', 'PLATINUM', 'DIAMOND', 'MASTER', 'CHALLENGER');

			foreach ($ranks as $eachRank)
			{
				$current = $eachRank;
				$checked = $_GET[$current];
				if (isset($checked))
				{
					if ($rankSelectString == "")
					{
						$rankSelectString .= "where tier = '" . $eachRank . "'";
					}
					else
					{
						$rankSelectString .= " or tier = '" . $eachRank . "'";
					}
				}
			}

			if ($rankSelectString == "")
			{
				$rankSelectString = "where tier == 'no rank'";
			}
			


			$regionSelectString = "";

			$regions = array('na', 'euw', 'eune', 'lan', 'las', 'oce', 'ru', 'tr', 'jp', 'sea', 'kr');

			foreach ($regions as $eachRegion)
			{
				$current = $eachRegion;
				$checked = $_GET[$current];
				if (isset($checked))
				{
					if ($regionSelectString == "")
					{
						$regionSelectString .= "where region = '" . $eachRegion . "'";
					}
					else
					{
						$regionSelectString .= " or region = '" . $eachRegion . "'";
					}
				}
			}

			if ($regionSelectString == "")
			{
				$regionSelectString = "where region == 'no region'";
			}



			$queryString = 
			"select * from (select * from (select * from (select * from (
				select summoner_id, summoner_name, tier, division, region, c1_name c_name, c1_id c_id, c1_points c_points, c1_level c_level, c1k ck, c1d cd, c1a ca, c1kda ckda, c1_games_won c_games_won, c1_games_lost c_games_lost, c1_games_played c_games_played, round(((c1_games_won/c1_games_played)*100), 2) winrate from LOL where c1_name = '" . $championName . "' union
				select summoner_id, summoner_name, tier, division, region, c2_name c_name, c2_id c_id, c2_points c_points, c2_level c_level, c2k ck, c2d cd, c2a ca, c2kda ckda, c2_games_won c_games_won, c2_games_lost c_games_lost, c2_games_played c_games_played, round(((c2_games_won/c2_games_played)*100), 2) winrate from LOL where c2_name = '" . $championName . "' union
				select summoner_id, summoner_name, tier, division, region, c3_name c_name, c3_id c_id, c3_points c_points, c3_level c_level, c3k ck, c3d cd, c3a ca, c3kda ckda, c3_games_won c_games_won, c3_games_lost c_games_lost, c3_games_played c_games_played, round(((c3_games_won/c3_games_played)*100), 2) winrate from LOL where c3_name = '" . $championName . "' union
				select summoner_id, summoner_name, tier, division, region, c4_name c_name, c4_id c_id, c4_points c_points, c4_level c_level, c4k ck, c4d cd, c4a ca, c4kda ckda, c4_games_won c_games_won, c4_games_lost c_games_lost, c4_games_played c_games_played, round(((c4_games_won/c4_games_played)*100), 2) winrate from LOL where c4_name = '" . $championName . "' union
				select summoner_id, summoner_name, tier, division, region, c5_name c_name, c5_id c_id, c5_points c_points, c5_level c_level, c5k ck, c5d cd, c5a ca, c5kda ckda, c5_games_won c_games_won, c5_games_lost c_games_lost, c5_games_played c_games_played, round(((c5_games_won/c5_games_played)*100), 2) winrate from LOL where c5_name = '" . $championName . "'

				) " . $rankSelectString . "
				) " . $regionSelectString . "
				) where " . $sortBy . " is not null and c_games_played >= 100 order by " . $sortBy . " " . $order .
				") where rownum <= " . $numberOfEntries;

			$summonerAttributes = sqlQuery($conn, $queryString);

			

			echo '<table style="width:100%">
  				<tr>
		  			<th></th>
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
				
			$i = 1;
			foreach ($summonerAttributes as $row)
			{
				echo 
				'<tr>
					<td>' . $i . '</td>
					<td>' . $row[SUMMONER_ID] . '</td>
					<td>' . $row[SUMMONER_NAME] . '</td>
					<td>' . $row[TIER] . '</td>
					<td>' . $row[DIVISION] . '</td>
					<td>' . strtoupper($row[REGION]) . '</td>
					<td>' . $row[C_POINTS] . '</td>
					<td>' . $row[C_LEVEL] . '</td>
					<td>' . $row[CK] . '</td>
					<td>' . $row[CD] . '</td>
					<td>' . $row[CA] . '</td>
					<td>' . $row[CKDA] . '</td>
					<td>' . $row[C_GAMES_WON] . '</td>
					<td>' . $row[C_GAMES_LOST] . '</td>
					<td>' . $row[C_GAMES_PLAYED] . '</td>
					<td>' . $row[WINRATE] . '</td>
				</tr>';

				$i++;
			}



			echo '</table>';



			

			oci_close($conn);


		?>

	<body>
<html>
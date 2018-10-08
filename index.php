<?php

function UpdateDatabase()
{
	$listings = json_decode( file_get_contents("https://api.coinmarketcap.com/v2/listings/") , true);

	$sql = mysqli_connect("localhost", "bitlpaip_stablecoins", "9u]KpJSHLa_y", "bitlpaip_stablecoins");
	if (!$sql)
	{
		echo mysqli_error($sql);
		exit;
	}
	$result = mysqli_query($sql, "SELECT * from stablecoin_info");
	$stablecoins = array();
	while ($row = mysqli_fetch_row($result))
		array_push($stablecoins, $row[1]);

	foreach ($listings["data"] as $listing)
	{
		foreach ($stablecoins as $stablecoin)
		{
			if ($stablecoin == $listing["symbol"])
			{
				$ticker = json_decode( file_get_contents("https://api.coinmarketcap.com/v2/ticker/".$listing["id"]) , true)["data"];
				mysqli_query($sql, "UPDATE stablecoin_info SET mcap=".$ticker["quotes"]["USD"]["market_cap"].", volume=".$ticker["quotes"]["USD"]["volume_24h"].", name='".$ticker["name"]."', cmc_link='https://coinmarketcap.com/currencies/".$ticker["website_slug"]."' WHERE ticker='".$stablecoin."'");
			}
		}
	}

	mysqli_close($sql);
}

function PrintStablecoin($ticker)
{
	$sql = mysqli_connect("localhost", "bitlpaip_stablecoins", "9u]KpJSHLa_y", "bitlpaip_stablecoins");
	if (!$sql)
	{
		echo mysqli_error($sql);
		exit;
	}
	$result = mysqli_query($sql, "SELECT * from stablecoin_info where ticker='".$ticker."'");
	$row = mysqli_fetch_row($result);
	if (count($row) == 0)
	{
		echo "No such coin in database";
		exit;
	}

	mysqli_close($sql);

	echo '<div class="currency" id="'.$ticker.'">
		<div class="ticker">'
			.$row[1].
		'</div>
		<div class="name">'
			.$row[2].
		'</div>
		<div class="mcap_val">'
			.$row[3].
		'</div>
		<div class="mcap_graph">
		</div>
		<div class="volume">'
			.$row[4].
		'</div>
		<div class="peg">'
			.$row[6].
		'</div>
		<div class="decentralized">';
			if ($row[7])
				echo "Yes";
			else
				echo "No";
		echo '</div>
		<div class="stable_now">';
			if ($row[8])
				echo "Yes";
			else
				echo "No";
		echo '</div>
		<div class="stable_past">';
			if ($row[9])
				echo "Yes";
			else
				echo "No";
		echo '</div>
		<div class="platform">'
			.$row[10].
		'</div>
	</div>';
}
?>

<html>
<head>
	<script src="stablecoins.js"></script> 
</head>
<body onload="init()">
	<div id="content">
		<div id="header">
		</div>
		<?php
		UpdateDatabase();
		PrintStablecoin("USDT");
		PrintStablecoin("DAI");
		PrintStablecoin("TUSD");
		PrintStablecoin("USNBT");
		?>
	</div>
</body>
</html>
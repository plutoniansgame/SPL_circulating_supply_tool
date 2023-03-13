<?php

header('Content-Type: application/json');

$tokenmint;
$filename = 'cache/error.json';
if ( $_GET["max"] == 'rpc' ) {
	echo '35000000000.00';
} else if ( $_GET["max"] == 'pld' ) {
	echo '350000000.00';
} else {

	if ( $_GET["supply"] == 'rpc' ) {
		$tokenmint = "EAefyXw6E8sny1cX3LTH6RSvtzH6E5EFy1XsE2AiH1f3"; // rpc
		$filename = 'cache/rpc.json';
	} else if ( $_GET["supply"] == 'pld' ) {
		$tokenmint = "2cJgFtnqjaoiu9fKVX3fny4Z4pRzuaqfJ3PBTMk2D9ur"; // pld
		$filename = 'cache/pld.json';
	}


	//$url = "https://api.mainnet-beta.solana.com";
	$url = "https://stylish-tame-resonance.solana-mainnet.quiknode.pro/b28848b480b5295d95ea48e8e72f40fde8bb3104/";

	/* try to use as long a cache time as you can to make the script quick */
	//$cachetime = 3600; // 3600 seconds = 1 hour
	//$cachetime = 86400; // 86400 seconds = 1 day
	//$cachetime = 604800; // 604800 seconds = 1 week
	$cachetime = 2600640; // 604800 seconds = 1 month
	$ignorelist = "3ojc6QExLJANXwd7fu7YfLAnQ1XxxM3TA5ZaUN6c7KC
	87pY5Bjru2CSAs3NzyAie8fkr43ne1PRS9B3H2Dgjtmw
	7aqeU5g2raiGrx9VXXbJkP7GfrVkAJpj33KzMJeuWJyE
	j2kVbpCUzpN7ZRvNKzPmBYqzXTeSiSppkkjBNpJaDj7
	phren4RPitKMrbjQVtdA7SHc4SaPhTAMZC3sjS9KS8x
	3UNLvrQKW53W4BMEBdHQxFBBiKBanXvqNN47YpqidpQr
	FUX2kGRdsa8fk8qvVzPTe4XDo6gNDYiuY1EmGiH1EzcS
	eQM64rBbQTPSSbphyiTTVVHPmbxPBfnmEs63XYkZTcC
	J2V6f21WBYmSeG6URbHHviVQk9xPtMY8oRuByD1hiTzV
	8sS3e4LF4xPknBSZSFeVti1NkuJF8ZKNcJw6X3MwbEZ7
	2Ji493nQMmd6o7XZZkcQwD3aKxcqUgwM94PbmtEhArFG
	EABoqFtZeKCse5gzT7bheDxQSAQntVmA4oGKf67sMVt5
	FZHeczw2JLUXDy7whiLiDLsvqkpgQEXuoGUfgnDiJ8Yi
	8FYJ1z9vhqf6aAWdg7MMo51pi6HdfJW1UW3yPrijWKgf
	2y1NTQwSLr7UwPkz35BRiiUDWQJKHCib69pzNrn4awV8
	5BjN7qJVwgaDRTuNsAtF1PpTpiCMWnBPnBSHC85WLcf6
	FMae8Lg8baEQzJmho85jBJQu58mZZZJgSzfJXT3UJjC4
	GHPyEJAA6bTKj3FkiidXhMBK5hM3Mxw6veJt8Fkj4b4C
	2JteQps1BcUYrRA1MtPf8GwaRswrfi3yw1Ewt73EqLVf
	25Qr6ss2AxvUhGDk69cDPVeJGcX7JYDBsek6rQdZonb6
	1nc1nerator11111111111111111111111111111111";



	if ( file_exists( $filename ) ) {
		$file_time = filemtime( $filename );
		$expire = $cachetime; // Time in seconds to cache the file for
		if ( $file_time < ( time() - $expire ) ) {
			// if expired, overwrite file
			updateJSON( $url, $tokenmint, $filename );
			echo 'cache updated';
		} else {
			// if the cache is valid
			// echo 'current cache will expire in '.($file_time) - ( time() - $expire ).' seconds';
			// Get the contents of the JSON file 
			$string = file_get_contents( $filename );
			if ($string === false) {
			    // deal with error...
			}
			$json_a = json_decode($string, true);
			if ($json_a === null) {
			    // deal with error...
			}
			$totaltokens = 0;
			$decimals = 0;
			$decimals = 0;
			foreach ($json_a["result"] as $val) {
				$wallet = $val["account"]["data"]["parsed"]["info"]["owner"]; // wallet
			    $decimals = $val["account"]["data"]["parsed"]["info"]["tokenAmount"]["decimals"]; // decimals
			    $tokenval = $val["account"]["data"]["parsed"]["info"]["tokenAmount"]["amount"]; // token amount
			    if ( str_contains( $ignorelist, $wallet) ) {
			    	// echo $wallet . " " . $tokenval . " " . "IGNORE\n\n";
			    } else {
			    	$totaltokens += $tokenval;
					// echo $wallet . " " . $tokenval . "\n\n";
			    }
			}
			echo number_format( $totaltokens/1000000, $decimals, '.', '');
		}
	} else {
		// if file does not exist, write to file
		updateJSON( $url, $tokenmint, $filename );
		// echo 'added new version to cache';
	}
	//header('Content-Type: application/json');
	$file_data = file_get_contents( $filename );

	/*
	{"jsonrpc":"2.0","result":[
		{"account":
			{"data":
				{"parsed":
					{"info":
						{"isNative":false,
						"mint":"2cJgFtnqjaoiu9fKVX3fny4Z4pRzuaqfJ3PBTMk2D9ur",
						"owner":"AHfUg6BHnzaqCzKCTeSEr2NnUvUMdSKJEb3ZPwpuuhWi",
						"state":"initialized",
						"tokenAmount":{"amount":"348333333",
							"decimals":6,
							"uiAmount":348.333333,
							"uiAmountString":"348.333333"
							}
						},
						"type":"account"
					},
					"program":"spl-token",
					"space":165
				},
				"executable":false,
				"lamports":2039280,
				"owner":"TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA",
				"rentEpoch":353
			},
			"pubkey":"Gop2cvnjSF22GkNLY7p3DkQpC8tk932kG9bwQLWiiPPY"
		},
		*/



	function write_data_to_file( $filename, $json ) {
		//$json = file_get_contents( 'http://path.to/external-site/file.json');
		$time = time();
		file_put_contents( $filename, $json );
	}



	function updateJSON( $url, $tokenmint, $filename ) {

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$data = <<<DATA
		{
		    "jsonrpc": "2.0",
		    "id": 1,
		    "method": "getProgramAccounts",
		    "params": [
		      "TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA",
		      {
		        "encoding": "jsonParsed",
		        "filters": [
		          {
		            "dataSize": 165
		          },
		          {
		            "memcmp": {
		              "offset": 0,
		              "bytes": "$tokenmint"
		            }
		          }
		        ]
		      }
		    ]
		}
		DATA;


		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$resp = curl_exec($curl);
		curl_close($curl);

		//echo $resp;
		write_data_to_file( $filename, $resp );

	}

}

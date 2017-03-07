<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Scrape test</title>
</head>
<?php
require_once 'scrapeClass.php'; 

class ScrapeYelp extends ScrapeClass
{
	
	private function getBigHeader($node, $nodeName)
	{
		$count = 0; 
		if(!$node->previousSibling)
			return ""; 
		else
			while($node->previousSibling->nodeName != $nodeName)
			{
				$node = $node->previousSibling; 
				$count++;
				if($count>20 || !$node->previousSibling) return ""; 
			}
		return $node->previousSibling->textContent;  
	}
	
	private function contains($str, $findme)
	{
		$pos = strpos($str, $findme);
		if($pos === false)
			return false;
		else
			return true;
	}
	
	public function parseHTML($html)
	{
		set_time_limit(0);
		//parse the html into a DOMDocument
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		//get ul 
		$reviewDiv = $dom->getElementById('restaurant-menu');
		//get all html tags that are in the div businessresults
		$divs = $xpath->evaluate("table[@class='prices-three']", $reviewDiv);
		$count=0; 
		foreach($divs as $div){ 
			$features = array(); 
			//for each menu section 
			$heading = $xpath->evaluate("h3", $reviewDiv);
			echo "<br/>-------------------------------",$this->getBigHeader($div, 'h3'),"-----------------------------------<br/>"; 
			$rf = $this->getBigHeader($div, 'h3');
			$bheads = $xpath->evaluate("h2", $reviewDiv); 
			if($bheads->length > 0)
			{
				$bh = $this->getBigHeader($div, 'h2');
				echo 'Big Header: ',$this->getBigHeader($div, 'h2'),'<br/>';
				if($this->contains($bh, 'Vegetarian'))
				{
					preg_match('/^Vegetarian/', $bh, $match);
					if(sizeof($match) > 0)
					array_push($features, 'Vegetarian');
				}
				if($bh == 'Breakfast')
				{
					array_push($features, 'Breakfast');
				}
				else if($this->contains($bh, 'Lunch'))
				{
					array_push($features, 'Lunch');
				}
				else if($bh == 'Dinner')
				{
					array_push($features, 'Dinner');
				}
				
				if($this->contains($bh, 'Vegan'))
				{
					array_push($features, 'Vegan');
				}
				if($this->contains($bh, 'Entre') || $this->contains($bh, 'Platos'))
				{
					array_push($features, 'Entre');
				}
				
				if($this->contains($bh, 'Special'))
				{
					array_push($features, 'Specials');
				}
				
				if($bh == 'Drinks' || $bh == 'Bourbon Or Whiskey' || $bh == 'Wine' || $bh == 'Bottled Wine')
				{
					array_push($features, 'Drinks');
				} 
			}
			
			if($this->contains($rf, 'Vegetarian'))
			{
				preg_match('/Vegetarian/', $rf, $match);
				preg_match('/non/', $rf, $match2); 
				if(sizeof($match) > 0 && sizeof($match2) == 0)
				array_push($features, 'Vegetarian');
			}
			
			if($this->contains($rf, "Paneer"))
			{
				array_push($features, 'Vegetarian');
			}
			if($this->contains($rf, "Lunch"))
			{
				array_push($features, 'Lunch');
			}
			else if($this->contains($rf, "Breakfast"))
			{
				array_push($features, 'Breakfast');
			}
			else if($this->contains($rf, "Dinner"))
			{
				array_push($features, 'Dinner');
			}
			
			if($this->contains($rf, 'Vegan'))
			{
				array_push($features, 'Vegan');
			}
			if($this->contains($rf, "Beverage") || $this->contains($rf, "Cocktail") || $this->contains($rf, "Juice") || $this->contains($rf, "Drink") || $this->contains($rf, "Beer") || $this->contains($rf, "Bottle"))
			{
				array_push($features, 'Drinks');
			}
			if($this->contains($rf, "Special"))
			{
				array_push($features, 'Specials');
			}
			if($this->contains($rf, 'Entre') || $this->contains($rf, 'Curry') || $rf == 'Eggs' || $rf == 'Griddle $8' || $rf == 'Griddle' || $rf == 'Main Courses' || $rf == 'Poached Eggs' || $rf == 'Steaks' || $rf == 'Lighter Fare' || $rf == 'Daily Specials') 
				array_push($features, 'Entre');
			if($rf == 'Small Plates' || $this->contains($rf, 'Side') || $this->contains($rf, 'Snack') || $this->contains($rf, 'Acompanant'))
				array_push($features, 'Side Order');
			if($this->contains($rf, 'Salad'))
				array_push($features, 'Salads');
			if($this->contains($rf, 'Sandwich'))
				array_push($features, 'Sandwiches');
			if($this->contains($rf, 'Starter') || $this->contains($rf, 'Appetizer') || $this->contains($rf, 'Aperitivos'))
				array_push($features, 'Appetizer');
			if($rf == 'Pasta' || $this->contains($rf, 'Pasta'))
				array_push($features, 'Pasta');		
			if($this->contains($rf, 'Dessert') || $this->contains($rf, 'Icecream'))
			{
				array_push($features, 'Dessert');
			}
			if($this->contains($rf, 'Soup'))
			{
				array_push($features, 'Soups');
			}
			if($this->contains($rf, 'Breads'))
			{
				array_push($features, 'Breads');
			}
			if($this->contains($rf, 'Rice') || $this->contains($rf, 'Noodle'))
			{
				array_push($features, 'Rice/Noodles');
			}
			if($this->contains($rf, 'Wine'))
			{
				array_push($features, 'Wine');
			}
			
			$dishes = $xpath->evaluate("tbody/tr", $div);
			foreach($dishes as $dish)
			{
				$trclass = $dish->getAttribute('class');
				if($trclass != 'sub' && $trclass != 'extra')
				{
					$names = $xpath->evaluate("th", $dish);
					$name = $xpath->evaluate("cite", $names->item(0));
					if($name->length)
					{
						echo "------------------------------------------------------------------<br/>"; 
						echo $name->item(0)->textContent, '<br/>'; 
						$dishName = $name->item(0)->textContent; 
						$desc = $names->item(0)->removeChild($name->item(0)); 
						echo $names->item(0)->textContent, '<br/>';
						$dishDesc = $names->item(0)->textContent; 
						//to get price
						$prices = $xpath->evaluate("td", $dish); 
						$priceNode = $prices->item($prices->length-1);
						echo $priceNode->textContent,'<br/>'; 
						$dishPrice = $priceNode->textContent; 
						var_dump($features);
						$this->addDish($dishName, $dishDesc, $dishPrice, $features);
						//return;
					}
					
				}
			}
			$count++;
		}
		return 1; 
	}
	
	function getTotalNumber($html)
	{
		//parse the html into a DOMDocument
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		$reviewDiv = $dom->getElementById('businessresults');
		//get all html tags that are in the div businessresults
		$tds = $xpath->evaluate("table[@class='fs_pagination_controls']/tr/td", $reviewDiv);
		$span = $xpath->evaluate("span", $tds->item(0));
		
		$text = $span->item(0)->textContent; 
		$noOfRests = substr($text, strrpos($text, " ")); 
		//extract number
		//preg_match_all('/([\d]+)/', $element->nodeValue, $match);
		return (int)$noOfRests; 
	}
		
}
/* CLASS ScrapeYelp ENDS HERE
********************************************/

function getYelpData($target_url, $restID, $siteID, $connection)
{
	$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
	$yelp = new ScrapeYelp($target_url, $userAgent, $restID, $siteID, $connection);	
	// make the cURL request to $target_url
	$ch = curl_init();
	$yelp->setopt($ch); 
	$html= curl_exec($ch); 
	if (!$html) {
		echo "<br />cURL error number:" .curl_errno($ch);
		echo "<br />cURL error:" . curl_error($ch);
		$yelp->siteID++; 
		echo '<br/>',$yelp->siteID,'<br/>'; 
		return;
	}
	//$noReviews = $yelp->getTotalNumber($html);
	//echo $noReviews; 
	//return;
	$noReviews = 20; 
	$i = 0;
	$cont = 1;  
	while($i<$noReviews && $cont == 1){
		if($i == 0)
			$origURL = $target_url; 
		//$target_url = $origURL."&start=".$i;
		$yelp->setURL($target_url); 
		$yelp->setopt($ch);
		$html= curl_exec($ch);
		if (!$html) {
			echo "<br />cURL error number inside:" .curl_errno($ch);
			echo "<br />cURL error inside:" . curl_error($ch);
			exit;
		}
		//call function to parse the html page
		$cont = $yelp->parseHTML($html);
		$i+=40;
	}
	
	curl_close( $ch );
	//return $yelp->totalReviews; 
	
}
/*
$veg = "Vegetarian something";
$nveg = "Non-Vegetarian something";
$pattern = '/^Vegetarian/'; 
preg_match($pattern, $veg, $matches); 
print_r($matches); 
preg_match($pattern, $nveg, $matches); 
print_r($matches); 
*/

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
	$restaurants = array();
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		getYelpData('http://www.menupages.com/restaurants/bread-factory-cafe-2/menu', 6775, 0, $connection);
/*
		//insert data
		$query = "SELECT *
					FROM restaurants
					WHERE id < 5213
					ORDER BY id DESC 
					LIMIT 0,8000";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$restaurants[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no restaurants';
		}
		
		//mysql_free_result($result);
		//mysql_close($connection);
		
	//create target url
	foreach($restaurants as $row)
	{
		$rname = htmlspecialchars_decode($row['name'], ENT_NOQUOTES); 
		$rname = preg_replace("/[^A-Za-z0-9 ]/", "", $rname );
		$rname = strtolower(str_replace(' ', '-', $rname));
		$rname = strtolower(str_replace('039', '', $rname));
		$target = 'http://www.menupages.com/restaurants/'.$rname.'/menu'; 
		echo $target,'<br/>'; 
		getYelpData($target, $row['id'], 0, $connection);
	}
*/
//getYelpData('http://www.menupages.com/restaurants/saravanaas/menu', 0, 82);
?>
<body>
</body>
</html>

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
	public function parseHTML($html)
	{
		set_time_limit(0);
		//parse the html into a DOMDocument
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		//get ul 
		$reviewDiv = $dom->getElementById('businessresults');
		//get all html tags that are in the div businessresults
		$divs = $xpath->evaluate("div[@class='businessresult clearfix']", $reviewDiv);
		$count=0; 
		foreach($divs as $div){
			echo "<br/>-------------------------------separator-----------------------------------<br/>";
			$rName;
			$adr;
			$city = "New York"; 
			$state = "NY";
			$zip; 
			$phone; 
			$rating = 0; 
			$categories = array(); 
			$names = $xpath->evaluate("div[@class='leftcol']/h4/a", $div);
			foreach($names as $name){
				//$user = addslashes($username->textContent);
				$rName = substr($name->textContent, strpos($name->textContent, ".")+1);
				echo $name->textContent,'<br/>';
			}
			
			$addresses = $xpath->evaluate("div[@class='rightcol']/address", $div);
			foreach($addresses as $address){
				//$user = addslashes($username->textContent);
				//echo addslashes($address->textContent),'<br/>';
				//get the address first
				$fullAddress = addslashes($address->textContent); 
				$adr = substr($fullAddress, 0, strpos($fullAddress, "New York"));
				if($adr=="")
				{
					$adr = substr($fullAddress, 0, strpos($fullAddress, "Manhattan"));
				}
				echo $adr,'<br/>';
				$zip = substr($fullAddress, strpos($fullAddress, "NY")+3);
				$zip = substr($zip, 0, 5);
				echo $zip,'<br/>';
				//echo strpos($fullAddress, '('); 
				echo addslashes($address->textContent),'<br/>';
			}
			
			$phNos = $xpath->evaluate("div[@class='rightcol']/address/div[@class='phone']", $div);
			foreach($phNos as $phone){
				//$user = addslashes($username->textContent);
				$phone = addslashes($phone->textContent); 
				echo $phone,'<br/>';
			}
			
			$ratings = $xpath->evaluate("div[@class='rightcol']/div/span/img", $div);
			foreach($ratings as $rat){
				$userRating = $rat->getAttribute('alt');
				preg_match_all("/[\d]/", $userRating, $ratingInt); 
				$rating = $ratingInt[0][0].'.'.$ratingInt[0][1]; 
				echo 'Rating: ',$rating,"<br/>"; 
			}
			
			$cats = $xpath->evaluate("div[@class='leftcol']/div[@class='itemcategories']/a", $div);
			foreach($cats as $cat)
			{
				$cText = trim($cat->textContent);
				if($cText == "Asian Fusion")
				{
					array_push($categories,"Asian", "Fusion");  
				}
				else if(substr($cText, 0, 8) == "American")
				{
					array_push($categories, "American");
				}
				else if($cText == "Delis")
				{
					array_push($categories, "Deli");
				}
				else if($cText == "Bars")
				{
					array_push($categories, "Bar");
				}
				else if($cText == "Southern")
				{
					array_push($categories, "American");
				}
				else if($cText == "Latin American")
				{
					array_push($categories, "South American");
				}
				else if($cText == "Burgers")
				{
					array_push($categories, "Burger");
				}
				else if($cText == "Food Stands" || $cText == "Halal")
				{
					array_push($categories, "Cart");
				}
				else if($cText == "Sandwiches")
				{
					array_push($categories, "Sandwich");
				}
				else if($cText == "Desserts")
				{
					array_push($categories, "Dessert");
				}
				else if($cText == "Sushi Bars")
				{
					array_push($categories, "Sushi");
				}
				else if($cText == "Restaurants")
				{
					array_push($categories, "Restaurant");
				}
				else if($cText == "Bakeries")
				{
					array_push($categories, "Bakery");
				}
				else if($cText == "Tea Rooms")
				{
					array_push($categories, "Cafe");
				}
				else if($cText == "Seafood")
				{
					array_push($categories, "Sea Food");
				}
				else if($cText == "Steakhouses")
				{
					array_push($categories, "Steak House");
				}
				else if($cText == "Hot Dogs")
				{
					array_push($categories, "American");
				}
				else if($cText == "Coffee & Tea")
				{
					array_push($categories, "Cafe");
				}
				else
					array_push($categories, $cText); 
			}
			/*
			$dates = $xpath->evaluate("div/div/em[@class='smaller']", $div);
			foreach($dates as $date){
				$date = split("/", $date->textContent); 
				$dateOfReview = $date[2]."-".$date[0]."-".$date[1];
			}
			
			$comments = $xpath->evaluate("div/p", $div);
			foreach($comments as $comment){
				$comment = $comment->textContent; 
				$comment = htmlspecialchars($comment, ENT_QUOTES); 
			}
			
			//insert the data into db
			//$title = 'No Title'; 
			//only get new reviews
			if(strtotime($dateOfReview) <= $this->lastReviewDate && substr($comment, 0,20) == substr($this->lastReview, 0,20))
				return 0;
			*/
			
			//$this->insertIntoDB($user, $rating, $title, $dateOfReview, $comment); 
			
			//echo $name; 
			$this->geocodeAddress($rName, $adr, $city, $state, $zip, $rating, $phone, $categories); 
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

function getYelpData($target_url, $restID, $siteID)
{
	$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
	$yelp = new ScrapeYelp($target_url, $userAgent, $restID, $siteID);	
	// make the cURL request to $target_url
	$ch = curl_init();
	$yelp->setopt($ch); 
	$html= curl_exec($ch);
	if (!$html) {
		echo "<br />cURL error number:" .curl_errno($ch);
		echo "<br />cURL error:" . curl_error($ch);
		exit;
	}
	$noReviews = $yelp->getTotalNumber($html);
	echo $noReviews; 
	//return;
	//$noReviews = 20; 
	$i = 0;
	$cont = 1;  
	while($i<$noReviews && $cont == 1){
		if($i == 0)
			$origURL = $target_url; 
		$target_url = $origURL."&start=".$i;
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
getYelpData('http://www.yelp.com/search?find_loc=Harlem%2C+Manhattan%2C+NY&cflt=restaurants&rpp=40', 1, 2);
?>
<body>
</body>
</html>

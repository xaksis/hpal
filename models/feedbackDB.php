<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//all of DB interactions will take place from here


class FeedbackDb{

/////////////////////////////////////////////////////////////////////////
	/*IMAGE SPECIFIC FUNCTIONS*/
/////////////////////////////////////////////////////////////////////////

	public function insert_feedback($userId, $subject, $comment)
	{
		//create response object
		$response = new ResponseObject();
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		
		//insert new user into db
		$query = "INSERT INTO feedbacks (user_id,
									comment,
									subject,
									created) 
							VALUES($userId,
									'$comment',
									'$subject',
									now())";
		mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		$response->code = 0; 
		$response->explanation  = 'Feedback Saved!';

		mysql_close($connection);
		return $response;	
	}

	public function get_feedbacks($start, $n, &$feedbacks)
	{
		//create response object
		$response = new ResponseObject();
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query	= "SELECT u.username, f.id, f.user_id, f.comment, f.subject, f.created, f.reply
					FROM feedbacks as f left join users as u on f.user_id = u.id
					WHERE allow = 1
					Order by f.created desc
					LIMIT $start , $n";
					
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Feedbacks Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$feedbacks[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no dishes';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	
//////////////////////////////////////////////////////////
///////////////////private functions//////////////////////
//////////////////////////////////////////////////////////
	
	
	
}
?>
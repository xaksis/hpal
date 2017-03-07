<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"
    xmlns:fb="https://www.facebook.com/2008/fbml">
<head prefix="og: http://ogp.me/ns# aa_haplette: http://ogp.me/ns/apps/aa_haplette#"> 
    <meta property="fb:app_id" content="164932876924803" /> 
    <meta property="og:type" content="aa_haplette:restaurant" /> 
    <meta property="og:title" content="Ginger" /> 
    <meta property="og:image" content="http://70.104.139.232:8081/hp/uploaded/1313463437.3125_thumb.jpg" /> 
    <meta property="og:description" content="146" /> 
    <meta property="og:url" content="http://70.104.139.232:8081/hp/fbOgTest.php" />
</head>

<script type="text/javascript">

	function postLike()
    {
        FB.api('/me/aa_haplette:like' + 
                    '?restaurant=http://70.104.139.232:8081/hp/fbOgTest.php','post',
            function(response) {
				if (!response || response.error) {
					console.log(response);
                    alert('Error occured:'+response.error);
				} else {
					alert('Post was successful! Action ID: ' + response.id);
                }
        });
    }
</script
<body>

<form>
	<input type="button" value="Like" onclick="postLike()" />
</form>
 <div id="footer">
			<?php include 'footer.php'; ?>
</div>
</body>
</html>
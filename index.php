<?php
	require_once (__DIR__.'/crawler.php');
	require_once (__DIR__.'/VietnamnetCrawler.php');
	require_once (__DIR__.'/VnexpressCrawler.php');
	if(isset($_POST['submit'])){
		$error = '';
		if(isset($_POST['url'])){
			$url = $_POST['url'];
			if(!empty($url)){
				if(strpos($url,'//vietnamnet.vn/')){
					$cls = new VietnamnetCrawler($url);
				}
				if(strpos($url,'//vnexpress.net/')){
					$cls = new VnexpressCrawler($url);
				}
			}else{
				$error = 'Field URL not empty!';
			}
			
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>GET DATA BY URL</title>
	<link rel="stylesheet" href="">
</head>
<style>
	.label{
		width: 300px;
		float:left;
	}
	.right{
		width: 700px;
		float: right;
	}
	.container{
		width: 1000px;
		margin: 0 auto;
		text-align: center;
	}

	input[type="text"]{
		width: 80%;
		float:left;
	}

	table{
		width: 100%;
	}

	td:first-child{
		text-align: right;
		width: 300px;
	}

	td:last-child{
		width: 700px;
	}

	textarea{
		width: 100%;
	}
</style>
<body>
	<div id="page">
		<div class="container">
			<div class="message" style="text-align: center;">
				<span>PLEASE ENTER A LINK DETAIL PAGE OF WEBSITE VIETNAMENET.COM OR VNEXPRESS.NET</span>
			</div>
			<div class="search">
				<div class="show_error">
					<?= isset($error) ? $error : ''; ?>	
				</div>
				<div class="label">
					<span style="color:red;">PLEASE ENTER A  URL:</span>
				</div>
				<div class="right">
					<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
						<input type="text" name="url" value="" id="url">
						<input type="submit" name="submit" value="GET DATA" id="submit">
					</form>
				</div>
				
			</div>
			<div class="info">
				<?php if(isset($_POST['submit'])){if((strpos($_POST['url'],'//vietnamnet.vn/')) || (strpos($_POST['url'],'//vnexpress.net/'))){ ?>
				<table>
					<tr>
						<td>URL :</td>
						<td>
							<input type="text" value="<?= trim($cls->content[0]['url']) ?>">
						</td>
					</tr>
					<tr>
						<td>
							TITLE :
						</td>
						<td>
							<input type="text" value="<?= trim($cls->content[0]['title']) ?>">
						</td>
					</tr>
					<tr>
						<td>
							DATE :
						</td>
						<td>
							<input type="text" value="<?= trim($cls->content[0]['date']) ?>">
						</td>
					</tr>
					<tr>
						<td>
							DESCRIPTION :
						</td>
						<td>
							<textarea name="" id="" cols="30" rows="10">
								<?php  
									$text = trim($cls->content[0]['description']);
									$texto = explode("\n", $text);
								    foreach($texto as $line){
								        echo trim(str_replace(' ','&nbsp;',$line));
								    }  
								?>
							</textarea>
						</td>
					</tr>
					<tr>
						<td>
							CONTENT :
						</td>
						<td>
							<textarea name="" id="" cols="30" rows="10">
								<?php 
									$text1 = trim($cls->content[0]['content']);
									$texto1 = explode("\n", $text1);
								    foreach($texto1 as $line){
								        echo trim(str_replace(' ','&nbsp;',$line));
								    }  
								?>
							</textarea>
						</td>
					</tr>
				</table>
				<?php } } ?>
			</div>
		</div>
	</div>	
</body>
</html>
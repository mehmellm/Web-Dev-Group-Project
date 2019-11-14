<?php
session_start();
if ($_SESSION["username"]==""){
        header("Location: sqlLogin.php");
}
$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名
$username = $_SESSION['username'];
$tags = array();
$tagdata = array();
$tagarr = array();
?>


<?php

    try{
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $stmt = $pdo->prepare('SELECT qids, tags FROM quiz WHERE teacher = ?');
            $stmt->execute(array($username));
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    //echo var_dump($tags);
	    /*foreach ($tags as $tag){
		echo $tag['tags'];
	    }*/
            $tagarr = $tags['tags'];
            $errorMessage ='successfully released';
        }catch (PDOException $e){
            echo "something wrong";
        }

?>
<html>
<head>
<link rel="stylesheet" href="SecondStylesheet.css">
</head>
<body>
<div style="margin: 3%">
<h2>Tags</h2>
<div style="margin: 3%">
<ul>
<?php
    $listStr = '';
    //echo var_dump($tags);
    foreach ($tags as $tagwith){
	//echo $tagwith['tags'];
	if ($tagwith['tags'] != null){
                //echo $tagwith['tags'];
		$splitedTag = explode(",",$tagwith['tags']);
		//echo var_dump($splitedTag);
		if (count($splitedTag) == 1){
			//echo $tagwith['tags'];
			$tagkey = $tagwith['tags'];
			if (array_key_exists($tagKey,$tagdata)){
				array_push($tagdata[$tagkey], $tagwith['qids']);
			}else{
				$tagdata[$tagkey] = array();
				array_push($tagdata[$tagkey], $tagwith['qids']);
				$idKey = implode(",",$tagdata[$tagkey]);
				//echo "<li><a href='tagResult.php?id=".$idKey."'>".$tagwith['tags']."</a></li>";
			}
		}else{
			//$splitedTag = explode(",",$tagwith['tags']);
        		//echo 'here'.var_dump($splitedTag);
			foreach ($splitedTag as $atag){
	    			//echo $atag;
				$tagkey = $atag;
				if (array_key_exists($tagkey,$tagdata)){ 
					array_push($tagdata[$tagkey], $tagwith['qids']);
                        	}else{
					$tagdata[$tagkey] = array();
                        		array_push($tagdata[$tagkey],$tagwith['qids']);
					$idKey = implode(",",$tagdata[$tagkey]);
            				//echo "<li><a href='tagResult.php?id=".$idKey."'>".$atag."</a></li>";
        			}	
			}
		}
        }
    }
    foreach (array_keys($tagdata) as $tags){
	  //echo var_dump($tagdata);
	  $idKey = '';
	  $tag = '';
	  foreach ($tagdata[$tags] as $tag){
		//echo $tag;
	  	$idKey = $idKey.",".$tag;
          	//echo "<li><a href='tagResult.php?id=".$idKey."'>".$tags."</a></li>";
	  }
	  $idKey = substr($idKey,1);
	  echo "<li><a href='tagResult.php?id=".$idKey."&tag=".$tags."'>".$tags."</a></li>";
    }
	//echo var_dump($tagdata);
?>
</ul>
</div>
</div>
<button value="go back"><a href="teacher.php">go back</a></button>
</body>
</html>

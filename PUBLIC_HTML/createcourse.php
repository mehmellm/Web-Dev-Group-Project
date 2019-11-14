<?php
	session_start();

	$coursename = str_replace(' ', '', $_POST['coursename']);
	$username = $_SESSION['username'];
	
	$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
	$db['user'] = "CSC380f";  // ユーザー名
	$db['pass'] = "paris99";  // ユーザー名のパスワード
	$db['dbname'] = "CSC380f";  // データベース名

	if (isset($_POST["create"])) 
	{// 1. ユーザIDの入力チェック
    		if (empty($_POST["coursename"])) 
		{  // 値が空のとき
        		$errorMessage = 'type coursename';
    		}
    		if (!empty($_POST["coursename"])) 
		{ // 入力したユーザIDとパスワードを格納
        	  // 3. エラー処理
        		$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        		try 
			{
            			$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	    			$stmt = $pdo->prepare('SELECT * FROM course WHERE course = ?');
            			$stmt->execute(array($coursename));
            			$row = $stmt->fetch(PDO::FETCH_ASSOC);
	    			if ($row == false)
				{
                			$inst = $pdo->prepare("INSERT INTO course(course, user) VALUES (?, ?)");
                			$inst->execute(array($coursename, $username)); 
                			//$inst2 = "UPDATE user SET course=".$coursename." WHERE name =".$username;
                			//$pdo->prepare($inst2); 
					//$inst2 = $pdo->prepare("SELECT user SET course = ('a,d') WHERE name = ?")->execute(array($username));
					$inst2 = $pdo->prepare("INSERT INTO courselist(course) VALUES (?)");
                			$inst2->execute(array($coursename));
					$errorMessage = 'course is created';
            			}
				else
				{
                			$errorMessage = 'course name is already taken';
        			}  
        		} 
			catch (PDOException $e) 
			{
            			$errorMessage = 'DB Error';
       	 		}
    		} 
		else if($_POST["password"] != $_POST["password2"]) 
		{
        		$errorMessage = 'passwords did not match';
    		}
	}
?>
<!DOCTYPE html>
<html lang=“ja”>
<head>
    	<meta charset=“UTF-8”>
	<title>Document</title>
	<link rel="stylesheet" href="SecondStylesheet.css">
</head>
<body>
	<h2>Create Course</h2>
<?php

	echo "Currently Logged In","</br></br>";
	echo "Username : ",$username,"</br>";
	echo "Faculity : teacher";
?>
<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
 <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
<form name="form1" method="POST">
<fieldset>
	<legend>Create course</legend>
	<input type="text" name="coursename" id="coursename" placeholder="space is automatically took off">
	<input type="submit" name="create" value="create" id='create' onclick="return clicked();">
</fieldset>
</form>
<script type="text/javascript">
        myregexp = /;truncate/i;
        function clicked() {
            if (document.getElementById('coursename').value == ""){
            alert('Please make sure fill all fields')
                return false;
            }else {
                if (document.getElementById('coursename').value.match(myregexp)){
                    alert('";truncate" cannot be used')
                    return false;
                }else {
                    return true;
                }
            }
        }
    </script>

<button value="go back"><a href="teacher.php">go back</a></button>
</body>
</html>

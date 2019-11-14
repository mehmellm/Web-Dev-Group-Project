<?php
	session_start();
	if ($_SESSION["username"]=="")
	{header("Location: sqlLogin.php");}

	$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
	$db['user'] = "CSC380f";  // ユーザー名
	$db['pass'] = "paris99";  // ユーザー名のパスワード
	$db['dbname'] = "CSC380f";  // データベース名
	$username = $_SESSION['username'];
?>

<?php
if (isset($_POST["create"])) 
{ 
    if (!empty($_POST["quizname"]) && 
	!($_POST["coursename"] == 'default') && 
	!empty($_POST["question"]) && 
	!empty($_POST["answer"]) && 
	!empty($_POST["tags"])) 
	{
        // 入力したユーザIDとパスワードを格納
        $coursename = $_POST["coursename"];
        $title = str_replace(" ","",$_POST["quizname"]);
        $quiz = $_POST["question"];
        $ans = $_POST["answer"];
	$tags = $_POST["tags"];
        // 3. エラー処理
        
        try{
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM quiz WHERE course = ? AND title = ?');
            $stmt->execute(array($coursename,$title));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //$date = date("Y-m-d H:i:s");
            if ($row == null)
	    {
                $inst = $pdo->prepare("INSERT INTO quiz(tags, course, teacher,title, quiz, answer, mult,relstatus) VALUES (?,?, ?, ?, ?, ?,'no','not yet')");
                $inst->execute(array($tags,$coursename,$username,$title,$quiz,$ans)); 
                $errorMessage = 'quiz is created';
	    }
	    else
		{$errorMessage = 'quiz title is already taken';}
           }
	catch (PDOException $e)
	   {$errorMessage = 'DB Error';}
    	} 
	else
	{$errorMessage = 'type all section';}
}
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
	<link rel="stylesheet" href="SecondStylesheet.css">
    <title>Teacher Page</title>
</head>
<body>
	<h2>Teacher Home Page</h2>
	<?php
	  echo "Currently logged in as:</br></br>";
	  echo "Username : ",$_SESSION["username"],"</br>";
	  echo "Faculity : teacher","</br></br>";
	?>
	<p><br><p>
	<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
	<div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>


	<form  method = 'post'  action="createquiz.php">
	<fieldset>
	  <legend>Create Quiz</legend>
	  <input type="text" id="n" name="quizname" placeholder="Space is taken off." >
	  <?php
    	    try
	    {
              $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
              $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	      $stmt = $pdo->prepare('SELECT course FROM course WHERE user = ?');
              $stmt->execute(array($username));
	      $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
              echo '<select name = "coursename">';
	      echo "<option> default </option>";
              if ($cols != false)
		{
            	foreach($cols as $col) 
		{echo "<option>".$col['course']."</option>";}
        	} 
	      else 
		{echo "something went wrong";}
    	    }
	    catch (PDOException $e)
	    {echo "something wrong";}
    	  ?>
 <select>
<br>
tags:
<input type="text" name="tags">
<br>
  <p>please type question here</p>
  <textarea name="question" id="q" rows="4" cols="50"></textarea>
        <p>please type correct answer</p>
        <input type="text" id="a" name="answer">
        <br>
        <input type="submit" id="create" name="create" value="create">
        </fieldset>
</form>
<script type="text/javascript">
        function clicked() {
            if (document.getElementById('a').value == ""
        || document.getElementById('n').value == ""
        || document.getElementById('q').value == "")
                {
                alert('Please make sure fill all fields')
                return false;
            }else {
                if (document.getElementById('a').value.includes(";")
        || document.getElementById('n').value.includes(";")
        || document.getElementById('q').value.includes(";")
                   {
                    alert('";" cannot be used')
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

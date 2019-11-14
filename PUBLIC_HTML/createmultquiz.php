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
?>

<?php
if (isset($_POST["create"])) {
    //echo $_POST["question"];
    if (!empty($_POST["quizname"]) && !($_POST["coursename"] == 'default') && !empty($_POST["question"]) && !empty($_POST["answer"]) 
    && !empty($_POST["multa"]) && !empty($_POST["multb"]) && !empty($_POST["multc"]) && !empty($_POST["multd"]) && !empty($_POST["tags"])) {
        // 入力したユーザIDとパスワードを格納
        $coursename = $_POST["coursename"];
        $title = str_replace(' ', '', $_POST["quizname"]);
        $quiz = $_POST["question"];
        $ans = $_POST["answer"];
        $multa = $_POST["multa"];
        $multb = $_POST["multb"];
        $multc = $_POST["multc"];
        $multd = $_POST["multd"];
	$tags = $_POST["tags"];
        // 3. エラー処理 
        try {
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM quiz WHERE course = ? AND title = ?');
            $stmt->execute(array($coursename,$title));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row == null){
                $inst = $pdo->prepare("INSERT INTO quiz(tags,relstatus,course, teacher,title, quiz, answer, mult, multa, multb, multc, multd) VALUES (?,'not yet',?, ?, ?, ?, ?,'yes',?,?,?,?)");
                $inst->execute(array($tags,$coursename,$username,$title,$quiz,$ans,$multa,$multb,$multc,$multd)); 
                $errorMessage = 'quiz is created';
	    }else{
                $errorMessage = 'quiz title is already taken';
            }  
        } catch (PDOException $e) {
            $errorMessage = 'DB Error';
        }
    } else {
        $errorMessage = 'type all section';
    }
}
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
	<link rel="stylesheet" href="SecondStylesheet.css">
    <title>create multiple choice quiz</title>
</head>
<body>
<h2>Create Multiple Choice Quiz</h2>

<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
<div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>

<form  method="post" action="createmultquiz.php">
<fieldset>
  <legend>Create Quiz</legend>
  <input type="text" id="quiz"  name="quizname" placeholder="inesrt quiz name" >
  <?php
    
    try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	$stmt = $pdo->prepare('SELECT course FROM course WHERE user = ?');
        $stmt->execute(array($username));
	$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<select name = "coursename">';
	echo "<option> default </option>";
        if ($cols != false){
            foreach($cols as $col) {
                echo "<option>".$col['course']."</option>";
            }
        } else {
            echo "something went wrong";
        }
    }catch (PDOException $e){
        echo "something wrong";
    }
    ?>
 <select>
<br>
tags:
<input type="text" name="tags">
<br>
  <p>please type question here</p>
  <textarea name="question" rows="4" cols="50"></textarea>
        <p>please type multiple choice</p>
        <p>a</p>
        <input type="text" name="multa" id="a">
        <br>
        <p>b</p>
        <input type="text" name="multb" id="b">
        <br>
        <p>c</p>
        <input type="text" name="multc" id="c">
        <br>
        <p>d</p>
        <input type="text" name="multd" id="d">
        <br>
        <p>please choose correct answer</p>
        <select type="text" name="answer">
        <option>a</option>
        <option>b</option>
        <option>c</option>
        <option>d</option>
        </select>
        <br>
        <input type="submit" id="create" name="create" value="create" onclick="return clicked();">
        </fieldset>
</form>
<script type="text/javascript">
        function clicked() {
            if (document.getElementById('a').value == ""
        || document.getElementById('b').value == ""
        || document.getElementById('c').value == "")
	|| document.getElementById('d').value == "")
	|| document.getElementById('quiz').value == "")
		{
                alert('Please make sure fill all fields')
                return false;
            }else {
		if (document.getElementById('a').value.includes(";")
        || document.getElementById('b').value.includes(";")
        || document.getElementById('c').value.includes(";")
        || document.getElementById('d').value.includes(";")
        || document.getElementById('quiz').value.includes(";")
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

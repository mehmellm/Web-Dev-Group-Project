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
if (isset($_POST["rev"])) {
    if (empty($_POST["revquiz"])) {  // emptyは値が空のとき
        $errorMessage = 'choose quiz to revealed';
    }

    if (!empty($_POST["revquiz"])) {
        try{
	    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $data = explode("--",$_POST["revquiz"]);
            $course = $data[0];
            $title = $data[1];
            //echo 'here'.$title;
            $stmt = $pdo->prepare("UPDATE quiz SET revealable='yes' WHERE teacher = ? AND title = ? AND course = ?");
            $stmt->execute(array($username,$title,$course));
            $errorMessage ='successfully released';
        }catch (PDOException $e){
            echo "something wrong";
        }
    }
}
?>
<html>
<head>
<link rel="stylesheet" href="SecondStylesheet.css">
</head>
<body>
<script type="text/javascript">
    function clicked() {
       if (confirm('Do you want to kill it?')) {
           killfrom.submit();
       } else {
           return false;
       }
    }

</script>

<script type="text/javascript">
    function relclicked() {
       if (confirm('Do you want to reveal the quiz? (currently realesing quiz will be stopped releasing)')) {
           relfrom.submit();
       } else {
           return false;
       }
    }

</script>

<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
<br>
<h2>Reveal Control</h2>
<form id="relform" name="relform" action="" method="POST">
            <fieldset>
                <legend>reveal control</legend>
                <?php
                    try{
                        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

                        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
                        $stmt = $pdo->prepare('SELECT title,course FROM quiz WHERE teacher = ? AND revealable="no"');
                        $stmt->execute(array($username));
                        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo "--Quiz Reveable--";
                        echo '<br>'; 
                        if ($cols != false){
                            echo '<select style="width: 200px;" name = "revquiz">';
                            foreach($cols as $col) {
                                echo "<option>".$col['course']."--".$col['title']."</option>";
                            }
                            echo "</select>";
			    echo '<input type="submit" id="rev" name="rev" value="reveable" onclick="relclicked()">';
                        } else {
                            echo "no quiz can be released";
                        }
                    }catch (PDOException $e){
                        echo "something wrong";
                    }
                ?>
            </fieldset>
        </form>
<button value="logout"><a href="logout.php">logout</a></button>
<button value="go back"><a href="teacher.php">go back</a></button>
<br>
</body>
</html>

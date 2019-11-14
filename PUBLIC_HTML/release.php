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
if (isset($_POST["release"])) {
    if (empty($_POST["relquiz"])) {  // emptyは値が空のとき
        $errorMessage = 'choose quiz to release';
    }

    if (!empty($_POST["relquiz"])) {
        try{
	    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $data = explode("--",$_POST["relquiz"]);
            $course = $data[0];
            $title = $data[1];
            $stmt = $pdo->prepare('SELECT title FROM quiz WHERE teacher = ? AND relstatus = "releasing" AND course = ?');
            $stmt->execute(array($username,$course));
            $cols = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($cols != false){
                $stmt = $pdo->prepare('UPDATE quiz SET relstatus="released" WHERE teacher = ? AND title = ? AND course = ?');
                $stmt->execute(array($username,$cols['title'],$course));
           }
            $stmt = $pdo->prepare('UPDATE quiz SET relstatus="releasing" WHERE teacher = ? AND title = ? AND course = ?');
            $stmt->execute(array($username,$title,$course));
            $errorMessage ='successfully released';
        }catch (PDOException $e){
            echo "something wrong";
        }
    }
}

if (isset($_POST["end"])) {
    if (empty($_POST["selquiz"])) {  // emptyは値が空のとき
        $errorMessage = 'choose quiz to stop releasing';
    }

    if (!empty($_POST["selquiz"])) {
        try{
	    //echo $_POST["selquiz"];
            $data = explode(" -- ",$_POST["selquiz"]);
            $course = $data[0];
            $title = $data[1];
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $stmt = $pdo->prepare('UPDATE quiz SET relstatus="released" WHERE teacher = ? AND title  = ? AND course = ?');
            $stmt->execute(array($username,$title,$course));
            $errorMessage ='successfully release stoped';
        }catch (PDOException $e){
            echo "something wrong";
        }

    }
}
?>
<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	    $stmt = $pdo->prepare('SELECT title,course FROM quiz WHERE teacher = ? AND relstatus = "releasing"');
        $stmt->execute(array($username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $selstr ="<select style='width: 200px;' name ='selquiz'>";
        if ($cols != false){
	    echo '<form method="post" action="release.php" id=killform" name="killform">';
            echo "currently releasing";
	    echo '<br>';
            foreach($cols as $col) {
                echo $col['course']." -- ".$col['title'];
                $selstr = $selstr. "<option>".$col['course']." -- ".$col['title']."</option>";
		echo '<br>';
            }
            $selstr = $selstr . "</select>";
            echo $selstr;
            echo "<br>";
            echo '<input type="submit" name="end" value="end release" onclick="clicked()">';
        echo "</form>";
	}else{
		echo 'no quiz is being released';
		echo '<br>';
	}
    }catch (PDOException $e){
        echo "something wrong";
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
       if (confirm('Do you want to release new quiz? (currently realesing quiz will be stopped releasing)')) {
           relfrom.submit();
       } else {
           return false;
       }
    }

</script>

<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
<br>
<p>release control</p>
<form id="relform" name="relform" action="" method="POST">
            <fieldset>
                <legend>release control</legend>
                <?php
                    try{
                        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

                        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
                        $stmt = $pdo->prepare('SELECT title,course FROM quiz WHERE teacher = ? AND relstatus= "not yet"');
                        $stmt->execute(array($username));
                        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo "--quiz release--";
                        echo '<br>'; 
                        if ($cols != false){
                            echo '<select style="width: 200px;" name = "relquiz">';
                            foreach($cols as $col) {
                                echo "<option>".$col['course']."--".$col['title']."</option>";
                            }
                            echo "</select>";
			    echo '<input type="submit" id="release" name="release" value="release" onclick="relclicked()">';
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
<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	    $stmt = $pdo->prepare('SELECT title,course,relstatus FROM quiz WHERE teacher = ?');
        $stmt->execute(array($username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p style='font-family:monospace;'>quiz status</p>";
        if ($cols != false){
            echo "<table border='1'><tr> <th>course</th> <th>quiz</th> <th>status</th> </tr>";
            foreach($cols as $col) {
                echo "<tr>";
                echo "<td>".$col['course']."</td>";
                echo "<td>".$col['title']."</td>";
                echo "<td>".$col['relstatus']."</td>";
                echo "</tr>";
            }
            echo "<table>";
        } else {
            echo "no quiz created";
        }
    }catch (PDOException $e){
        echo "something wrong";
    }
    ?>
</body>
</html>

<?php
require_once 'Class_Selector_Creater.php';
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

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
	<link rel="stylesheet" href="SecondStylesheet.css">
    <title>Message Page</title>
</head>
<body>
<h2>Message</h2>

<?php
    try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

	    $stmt = $pdo->prepare("SELECT DISTINCT A.user
        FROM ((course A
        INNER JOIN course C ON A.course = C.course)
        INNER JOIN user U ON A.user = U.name) WHERE C.user = ? AND U.fac = 'teacher';");
        $stmt->execute(array($username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $teacher = array();
        if ($cols != false){
            foreach($cols as $col) {
                array_push($teacher,$col['user']);
            }
        } else {
            echo "something went wrong";
        }
    }catch (PDOException $e){
        echo "something wrong";
    }
?>

<?php
if (isset($_POST["send"])) {
    
    $text = $_POST['text'];
    $user = $_POST['sel'];
    $anon = $_POST['anon'];
    $uniqueID = uniqid();
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    
    try{
        $stmt = $pdo->prepare("INSERT INTO stud_message(teacher, student,chat, caseId) VALUES (?, ?, ?, ?)");
        $stmt->execute(array($user, $anon, $text, $uniqueID));
        
        $stmt = $pdo->prepare("INSERT INTO chat_case(user, caseId) VALUES (?, ?)");
        $stmt->execute(array($username, $uniqueID));

        echo "<script>alert('Successfully sent')</script>";
    }catch (PDOException $e){
        echo "<script>alert('Something Wrong')</script>";
    }

}
?>

<form id='send' name='send' method='post' action = "">
    <?php
        if (count($teacher) > 0){
            echo 'Please select a teacher:<br>';
        //echo var_dump($takers);
            $sel = new Select_Creater($teacher);
            $sels = $sel->creater();
            echo $sels;
	    echo "<br>";	
            }else{
            echo "There is no student!";
        }
    ?>
    <input type="radio" id="anon" name="anon" value="anonymous"> anonymous <br>
    <?php echo '<input type="radio" id="anon" name="anon" value="'.$username.'">use my name <br>';?>
    <textarea name="text" id="text" cols="30" rows="10"></textarea>
    <input type='submit' value='send' id="send" name="send" onclick="return clicked();">
</form>
<br>

<br><h2>Chats</h2>

<input type="button" value="refresh" onclick="return RefreshWindow();">
<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
<script>
function RefreshWindow() { 
            window.location.replace(location.href);
            //alert('h')
            /*var a = new Ajax.Updater( 
                "message", 
                "./chat_teacher.php", 
                { 
                "method": "post", 
                "parameters": "", 
                    onSuccess: function(request) { 
                    }, 
                    onComplete: function(request) {
                    },  
                onFailure: function(request) { 
                        alert('fail'); 
                    }, 
                    onException: function (request) { 
                        alert('fail'); 
                    }
                } 
            );*/
            //alert('h') 
}

</script>


<?php
    try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        
        $stmt = $pdo->prepare("SELECT A.student, A.teacher, A.chat, A.caseId
        FROM stud_message A
        INNER JOIN chat_case C ON A.caseId = C.caseId WHERE C.user = ? ORDER BY A.caseId, A.datetime;");
	    /*$stmt = $pdo->prepare('SELECT teacher, chat
        FROM chat
        WHERE student = ?;');*/
        $stmt->execute(array($username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($cols != false){
	echo"<ul>";
            foreach($cols as $col) {
		if ($col['student'] == 'reply'){
                echo "<li>".$col['teacher']."-- responce"."</li>";
                echo "&nbsp".$col['chat']."<br>";
		echo '<br>';
		}else{
		 echo "<li>".$username." as ".$col['student']."</li>";
                echo "&nbsp".$col['chat']."<br>";
		}
            }
        } else {
            echo "No message from teacher";
        }
	echo"</ul>";
    }catch (PDOException $e){
        echo "something wrong";
    }
?>


<button value="go back"><a href="user.php">go back</a></button>

<script type="text/javascript">
        function clicked() {
            if (document.getElementById('text').value == "")
            {
                alert('Please make sure fill all fields')
                return false;
            }else {
                if (document.getElementById('text').value.includes(";")){
                    alert('";" cannot be used')
                    return false;
                }else {
                    return isOneChecked();
                }
            }
        }

        function isOneChecked() {
            if ($("input[type=radio]:checked").length > 0) {
                    if (confirm('Do you want to submit?')) {
                        return true
                    }else{
                    return false
                }
            }else{
                alert("please slect one chat to reply")	
                return false
            }	
        }
</script>

</body>
</html>

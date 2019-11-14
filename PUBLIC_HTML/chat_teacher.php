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
    <title>Chat Page</title>
</head>
<body>
<h2>Message</h2>




<?php
    try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

	    $stmt = $pdo->prepare('SELECT A.user
        FROM course A
        INNER JOIN course B ON A.course = B.course WHERE B.user = ? AND A.user <> ?;');
        $stmt->execute(array($username, $username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        echo "Your Students";
        $studs = array();
        if ($cols != false){
            foreach($cols as $col) {
                array_push($studs,$col['user']);
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
    $caseId = $_POST['case'];

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    
    try{
        $stmt = $pdo->prepare("INSERT INTO stud_message(teacher,student,chat,caseId) VALUES (?, ?, ?, ?)");
        $stmt->execute(array($username, 'reply', $text,$caseId));  

        echo "<script>alert('Successfully sent')</script>";
    }catch (PDOException $e){
        echo "<script>alert('Something Wrong')</script>";
    }

}
?>

<form id='send' name='send' method='post' action ="chat_teacher.php">
    <br>
    <textarea name="text" id="text" cols="30" rows="10"></textarea>
    <input type="submit" value="send" id="send" name="send" onclick="return clicked();">
    <br>

<button value="go back"><a href="teacher.php">go back</a></button>

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
<br>
<h2>From your students</h2><br>

<input type="button" value="refresh" onclick="return RefreshWindow();"/>
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
    echo '<p id="message">';
    try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

	    $stmt = $pdo->prepare('SELECT caseId, student, chat
        FROM stud_message
        WHERE teacher = ? ORDER BY caseId, datetime');
        $stmt->execute(array($username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
        if ($cols != false){
            echo "<ul>";
	    foreach($cols as $col) {
		if ($col['student'] != 'reply'){
                echo '<input type="radio" id="case" name="case" value='.$col['caseId'].'>';
		//echo '<input type="radio" id="case" name="case" value='.$col['caseId'].'>';
                echo "<li>".$col['student']."</li>";
		echo "  ".$col['chat']."<br>";
		}else{
                echo "	Your responce:  ".$col['chat']."<br>";
		}
		echo "<br>";
            }
	    echo "</ul>";
        } else {
            echo "No message from teacher";
        }
    }catch (PDOException $e){
        echo "something wrong";
    }
echo '</p>';
?>

</form>
</body>
</html>

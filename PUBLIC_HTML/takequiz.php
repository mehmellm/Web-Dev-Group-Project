<?php
require_once 'class_shambles.php';
session_start();
$course = $_GET['course'];
$title = $_GET['title'];
//echo $title;
$username = $_SESSION['username'];

if ($_SESSION["username"]==""){
        header("Location: sqlLogin.php");
}

$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

$result = array();
$waiting = array();
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
	<link rel="stylesheet" href="SecondStylesheet.css">

    <title></title>
</head>
<body>
<?php echo '<h2>'.$course.'</h2>';?>
<?php echo '<h2>'.$title.'</h2>';?>

<?php
$question = '';
$mult = "";
$multa = "";
$multb = "";
$multc = "";
$multd = "";
$correctans = "";
$setlev = 0;
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT mult,multa,multb,multc,multd,quiz,answer,setlev FROM quiz WHERE title = ? AND course = ?');
        $stmt->execute(array($title,$course));
        $quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($quiz);
        foreach ($quiz as $row) {
            //echo var_dump($row);
            $question = $row['quiz'];
            $mult = $row['mult']; 
	    if ($row['mult'] == 'yes'){
	    	$multa = $row['multa'];
		    $multb = $row['multb'];
		    $multc = $row['multc'];
		    $multd = $row['multd'];	
	    }
            $correctans = $row['answer'];
	    $setlev = $row['setlev'];
        }
} catch (PDOException $e){
        echo "something wrong";
    }
?>

<?php
//echo 'here';
if (isset($_POST["ans"])) {
    //echo 'here';
    $levRates = $_POST['lev'] * 1;
    $ans = $_POST['answer'];
    echo $ans;
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    //echo $levRates;
    if ($levRates <= $setlev){
        $checker = 'correct';
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        //$sql = "UPDATE quiz SET stats = stats + 1 WHERE title = ? AND course = ?";
        //echo $course;
        $stmt= $pdo->prepare("UPDATE quiz SET stats=stats + 1 WHERE title = '".$title."' AND course = '".$course."'");
        $stmt->execute();
    }else{
        $checker = 'wrong';
    }
     
    try{
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $stmt = $pdo->prepare('SELECT taker FROM quiztaker WHERE quiztitle = ? AND course = ? AND taker =  ?');
        $stmt->execute(array($title,$course,$username));
        $judge = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($judge == false ) {
	    //echo $title;
            $stmt = $pdo->prepare('INSERT INTO quiztaker(taker, quiztitle, answer, course, checker, levRates) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute(array($username,$title,$ans,$course,$checker,$levRates));
            //echo "<script>alert('Successfuly submitted')</script>";
	    header('Location: user.php');
            exit();
        }else{
            header('Location: dead.php');
            exit();
        }
    }catch (PDOException $e){
        echo "something wrong";
    }

}

    ?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    </head>
    <script type="text/javascript">
    var correctans = '<?php echo $correctans;  ?>'; 
    function clicked() {
       var ans = $("#answer").val()
       //alert(ans)
       if (ans == ""){
		alert("Your answer is empty")
		return false
       }
       if (confirm('Do you want to submit?')) {
	   //alert(correctans)
           var dist = LevenshteinDistance(ans,0,ans.length,correctans,0,correctans.length)
	   //alert(dist)
           //$(':input').attr('type', 'hidden').attr('name', "levRates").attr('value', parseInt(dist/correctans*100)).appendTo('#ans');
            $('#lev').val(dist.toString())
	    //alert($('#lev').val())
	}
	else {
           return false;
       }

    }
    
    function isOneChecked() {
  	if ($("input[type=radio]:checked").length > 0) {
    		if (confirm('Do you want to submit?')) {
			var ans = $("#mul").val()
			var dist = LevenshteinDistance(ans,0,ans.length,correctans,0,correctans.length)
            		$('#lev').val(dist.toString())
	    		return true
       		}else{
			return false
		}
	}else{
		alert("Your answer is empty")	
  		return false
    	}	
    }

    </script>
    <script>
    memos = []    // will be used as a dictionary (hashmap)
    //i is the start index of str1, j is the start index of str2
    function LevenshteinDistance(str1,  i,  len1,  str2,  j,  len2) {
        var key = i+"," + len1 + "," + j + "," + len2; 

        if(memos[key] != undefined) return memos[key]

        if(len1 == 0) return len2;
        if(len2 == 0) return len1;
        var cost = 0;
        if(str1.charAt(i) != str2.charAt(j)) cost = 1

        var dist = min3(
            LevenshteinDistance(str1, i+1,len1-1, str2,j,len2)+1, 
            LevenshteinDistance(str1,i,len1,str2,j+1,len2-1)+1,
            LevenshteinDistance(str1,i+1,len1-1,str2,j+1,len2-1)+cost);
            memos[key] = dist
            return dist;
        }

        function min3(a, b, c) {
            if (a < b && a < c)
                return a;
            if (b < a && b < c)
                return b;
            return c;
    }

    //str1 = prompt("Enter string 1")
    //str2 = prompt("Enter string 2")

    //dist = LevenshteinDistance (str1, 0, str1.length, str2, 0, str2.length);
    //alert("distance = "+dist)
    </script>

    <body>
    <form id='ans' name='ans' method='post' action = <?php echo "takequiz.php?course=".$course."&title=".$title; ?>>
        <?php
	    echo 'question';
	    echo '<br>'; 
            echo $question;
	    echo '<br><br>';
	    //$sham_data = new Shambles("choice a: ".$row['multa'],"choice b: ".$row['multb'],"choice c: ".$row['multc'],"choice d: ".$row['multd']);
	    //$sham_arr = $sham_data->shambles();
            if ($mult == 'yes'){
		//echo 'here';
		$sham = new Shambles("a:".$row['multa'],"b:".$row['multb'],"c:".$row['multc'],"d:".$row['multd']);
		$sham_arr = $sham->data;
		
		$sham_arr0 = explode(":",$sham_arr[0]);
		$sham_arr1 = explode(":",$sham_arr[1]);
		$sham_arr2 = explode(":",$sham_arr[2]);
		$sham_arr3 = explode(":",$sham_arr[3]);
		//echo "please makes sure this is not alphabetical order<b><br>";
                echo '<input type="radio" id="mul" name="answer" value="'.$sham_arr0[0].'">'.$sham_arr0[1]."<br>";
		echo '<input type="radio" id="mul" name="answer" value="'.$sham_arr1[0].'">'.$sham_arr1[1]."<br>";
		echo '<input type="radio" id="mul" name="answer" value="'.$sham_arr2[0].'">'.$sham_arr2[1]."<br>";
		echo '<input type="radio" id="mul" name="answer" value="'.$sham_arr3[0].'">'.$sham_arr3[1]."<br>";
		//echo $sham_arr0[0];
                /*echo '<select name ="answer">';
                echo "<option>a</option>";
                echo "<option>b</option>";
                echo "<option>c</option>";
                echo "<option>d</option>";
                echo "</select>";*/
		echo '<input type="hidden" id="lev" name="lev" val="0">';
		echo '<input type="submit" onclick="return isOneChecked();"  value="submit answer" name = "ans" id="answer">';
            }else{
                echo '<input type="text" id="answer" name="answer" placeholder="Answer Here">';
                echo '<input type="hidden" id="lev" name="lev" val="0">';
		echo '<input type="submit" onclick="return clicked();"  value="submit answer" name = "ans" id="answer">';
            }
        
        ?>
		<br>

	</form>
    <button value="Go Back"><a href="user.php">Go Back</a></button>
    </body>
</html>


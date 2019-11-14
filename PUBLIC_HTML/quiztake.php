<?php
session_start();
$quiz_arr = $_SESSION['quiz'];
$title = $_GET["selectedquiz"];
if ($_SESSION['username'] == ""){
header("Location: sqlLogin.php");
exit();
}
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>

    <title>Quiz</title>
</head>
<body>
<h2>Quiz</h2>
<?php
echo "Quiz taking","</br></br>";
echo "username : ",$_SESSION["username"],"</br>";
echo "faculity : student";
?>


<?php
    echo '<br> <br>quiz <br>'; 
    echo $title;
    echo '<br><br>';
    echo  $quiz_arr[$title][0];
    ?>
    
<form action=ans.php method=”POST”>
<input type="text" placeholder="answer" name="stud_ans">
<input type="submit" value="submit">
</form>

</body>
</html>

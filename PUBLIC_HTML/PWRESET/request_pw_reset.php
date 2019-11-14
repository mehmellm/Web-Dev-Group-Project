<?php
$username = $_POST["username"];
$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

if ($username == "") {
    print("<script>alert('Your username is empty')</script>");
    return;
}

try{
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    $stmt = $pdo->prepare('SELECT email FROM user WHERE name =  ?');
    $stmt->execute(array($username));
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($row == false ) {
        print("<script>alert('Your username is incorrect')</script>");
        return;
    }else{
	foreach($row as $col){
        	$email = $col['email'];
	}
    }
}catch (PDOException $e){
        echo "something wrong";
}

//echo 'email'.$email;
$start = 1000000000000000;
$refnum =  rand($start, $start*2-1);
$s = "\n" . $username . ";" .  date("Y-m-d h:m", time()) . ";". $refnum;
file_put_contents("./request.txt", $s, FILE_APPEND);

$url = "http://brahe.canisius.edu/~CSC380f/PWRESET/actual_reset_form.php?username=$username&refnum=$refnum";
$mail_message = "Click on the following link.\n" . $url;
mail ($email, "Password reset", $mail_message);
?>
<p>
Now check your email for a message that has a link to click on.
<br>
Or you can cut and paste that URL into your browser here.
<button value="go to login"><a href="../sqlLogin.php">go back</a></button>


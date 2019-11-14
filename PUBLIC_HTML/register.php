<?php


if ($_SESSION['username'] == ""){
header("Location: login.html");
exit();
}

$f = fopen("passwords.txt","r");
$text = fread($f,filesize("passwords.txt"));
fclose($f);
$lines = explode("\n",$text);

$find= false;
foreach ($lines as $line) {
    $parts = explode(',',$line);
    if ($parts[0] == $_POST["username"]){
            $find=true;    
            break;
    }
}
if ($find==false){
    $fa = fopen("passwords.txt","a");
    fwrite($fa,$_POST["username"]);
    fwrite($fa,",");
    $pw = $_POST["password"];
    $pw_enc = sha1($pw);
    fwrite($fa,$pw_enc);
    fwrite($fa,",");
    fwrite($fa,$_POST["faculty"]);
    fwrite($fa,"\n");
    fclose($fa);
    header("location: login.html");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration Failed</title>
<link rel="stylesheet" href="LoginANdRegStylesheet.css">
</head>
<body>
<h1>Registration Failed!</h1>
<p>The username that you have tried to use is already taken!</br>
Think its you? <a href="login.html">Log In</a></br>
Or try another username: <a href="register.html">Register</a>
</p>
</body>
</html>

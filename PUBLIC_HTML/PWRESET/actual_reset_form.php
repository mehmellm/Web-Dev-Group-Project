<html>
<head>
<meta charset="UTF-8">
<title>Passowrd Reset</title>
<link rel="stylesheet" href="FirstStylesheet.css">
</head>
<body>
<?php
$username = $_GET["username"];
$refnum = $_GET["refnum"];
?>
<script>
function verify() {
    if (document.getElementById("username").value == "" || document.getElementById("password1").value == "" || document.getElementById("password2").value == ""){
        alert("Your username cannot be empty")
        return false
    } else {
        if (document.getElementById("password1").value == document.getElementById("password2").value){
            return true
        }else{
            alert("passwords are not match")
            return false
        }
    }
}
</script>
<form method="post" action="accept_change.php" onsubmit="return verify()">
<input type="hidden" name="username" value="<?php echo $username;?>"/>
<input type="hidden" name="refnum" value="<?php echo $refnum;?>"/>
<label>Username:</label>
<input readonly  value="<?php echo $username;?>" />
<br>
<label>New password:</label>
<input type="Password" name="password1" id="password1"/>
<br>
<label>New password (again):</label>
<input type="Password" name="password2" id="password2"/>
<p>
<input type="submit" value="Submit change"/>
</form>
</body>
</html>


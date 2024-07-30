<?php 
$req = $_POST;
$status = "";
if(isset($_POST["uname"]) && isset($_POST["pass"])){
    $conn = mysqli_connect('localhost','root', '123123');
    if(mysqli_connect_errno()){
        die();
    }
    mysqli_select_db($conn,'snapshack');
    $uname = "'".$_POST['uname'] . "'";
    $password = "'".$_POST['pass']."'";

    $query = "SELECT name, id, isAdmin, disabled FROM users WHERE (name = $uname OR email = $uname) AND pass = $password";
    
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        if($row['disabled']=='1'){
            echo "<script> alert('Cannot log into this user');</script>";
        }else{
            session_start();
            $_SESSION["localusername"] = $row["name"];
            $_SESSION["localuserid"] = $row["id"];
            if($row['isAdmin']=='1'){
                $_SESSION['isAdmin'] = 1;
            }
            $status ="Log in sucessfull, you can close this page";
        }
    }else{
        $status = "Invalid username or passowrd";
        echo "<script> showReset = true; </script>";
        
    }
}elseif(isset($_POST["reguname"]) && isset($_POST["regmail"]) && isset($_POST['regpassword']) && isset($_POST['passverify'])){
    
    $conn = mysqli_connect('localhost','root', '123123');
    if(mysqli_connect_errno()){
        die();
    }
    mysqli_select_db($conn,'snapshack');
    $query = "SELECT * FROM users WHERE name = '".$_POST["reguname"]."' OR email = '". $_POST['regmail']. "'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){      
            echo "<script>alert('user already exists please log in'); </script>";
    }else{
        $reguname = $_POST['reguname'];
        $regmail = $_POST['regmail'];
        $regpassword = $_POST['regpassword'];
        $query = "INSERT INTO users(name, email, pass) values('$reguname', '$regmail', '$regpassword')";
        mysqli_query($conn, $query);
        $status  = "User created sucessfully, please log in.";
    }


    mysqli_close($conn);


}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapShack</title>
    <link rel="stylesheet" href="./styles.css">
    <script src="./navigator.js"></script>
    <style>

    form{
        display: flex;
        flex-direction: column;
    }
</style>


</head>
<body>
<div id="head">
    
</div>
<div id="main">
    <div id="leftSpace">
    </div>
    <div id="mainContent">
        <div id="mainBox">
            <div id="mainTitle">
                <h1>Login </h1>
            </div>
                <h4>Existing user</h4>
            <div id="topThreads">
               <form id='loginform' action="login.php" method="POST">
                <label for="uname">Username or email</label>
                <input type="text" name="uname" required>
                <label for="pass"> Password </label>
                <input type="password" name="pass" required>
                <button type="submit"> Log in</button>
                   
            </form>
                <script>
                  if (showReset) {
                    let link = document.createElement('a');
                    link.href = 'forgorpassword.php';
                    link.target = "blank";
                    link.innerText = "Forgot password?";
                    document.getElementById('loginform').appendChild(link);
                }

                </script>
            </div>
                <h4>Create new account</h4>
                <form action="login.php" method="POST" >
                <label for="reguname">Username </label>
                <input type="text" name="reguname" required>
                <label for="regmail">Email (optional, used to reset password)</label>
                <input type="email" name="regmail" required>
                <label for="regpass" id="notification"> Passowrd </label>
                <input type="password" name="regpassword" required id="regpassword">
                <label for="passverify" > Re-Type password </label>
                <input type="password" name="passverify" required id="passverify">
                <button type="submit" disabled id="regbutton"> Register </button>
                </form>
            <div id="otherThreads">
              <?php echo $status ;?>
            </div>  
        </div>
    </div>
    <div id="rigthSpace">
    </div>
</div>
<script>
    const input = document.getElementById("regpassword");
    const input2 =document.getElementById("passverify");

input.addEventListener("input", function() {
  validateForm();
});
input2.addEventListener("input", function() {
    validateForm();
})

        function validateForm() {
            var password = document.getElementById("regpassword").value;
            var passVerify = document.getElementById("passverify").value;

            if (password !== passVerify) {
                document.getElementById("notification").innerText = "Passwords do not match";
            }else{
                document.getElementById("notification").innerText = "Passwords do match";
                document.getElementById("regbutton").disabled = false;
            }
        }
    </script>


</body>
</html>


<?php
if (isset($_POST["uname"]) && isset($_POST['pass'])) {
    $uname = $_POST['uname'];
    $newPassword = $_POST['pass'];

    $conn = mysqli_connect("localhost", "root", "123123");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_select_db($conn, "snapshack");

    $query = "SELECT id FROM users WHERE name = '$uname' OR email = '$uname'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userid = $row['id'];

        $query = "UPDATE users SET pass = '$newPassword' WHERE id = $userid";
        if (mysqli_query($conn, $query)) {
            echo "Password reset successfully";
        } else {
            echo "Error resetting password: " . mysqli_error($conn);
        }
    } else {
        echo "User not found";
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
        form {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
<div id="head"></div>
<div id="main">
    <div id="leftSpace"></div>
    <div id="mainContent">
        <div id="mainBox">
            <div id="mainTitle">
                <h1>Reset password</h1>
            </div>
            <div id="topThreads">
                <form id='loginform' action="forgorpassword.php" method="POST">
                    <label for="uname">Email used during registration</label>
                    <input type="text" name="uname" required>
                    <label for="pass">New password</label>
                    <input type="password" name="pass" id="pass" required>
                    <label for="passverify">Repeat password</label>
                    <input type="password" name="passverify" id="passverify" required>
                    <button type="submit" id="regbutton" disabled>Reset Password</button>
                    <span id="notification"></span>
                </form>
                
            </div>
        </div>
    </div>
    <div id="rigthSpace"></div>
</div>
<script>
    const input = document.getElementById("pass");
    const input2 = document.getElementById("passverify");

    input.addEventListener("input", validateForm);
    input2.addEventListener("input", validateForm);

    function validateForm() {
        var password = document.getElementById("pass").value;
        var passVerify = document.getElementById("passverify").value;

        if (password !== passVerify) {
            document.getElementById("notification").innerText = "Passwords do not match";
            document.getElementById("regbutton").disabled = true;
        } else {
            document.getElementById("notification").innerText = "Passwords do match";
            document.getElementById("regbutton").disabled = false;
        }
    }
</script>
</body>
</html>

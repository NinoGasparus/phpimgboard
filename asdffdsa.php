
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

    $usermatch;
    $postsmatch;
    $commentmatch;

    $usercount = 0;
    $postcount =0;
    $commentcount =0 ;
    include "./admin.php";

?>

<?php
$req = $_POST;
$conn = mysqli_connect("localhost","root","123123");
mysqli_select_db($conn,"snapshack");
if(isset($_POST["ban"])){
    $targetID = $req['ban'];
    $query = "UPDATE users SET disabled = 1 WHERE id  = $targetID";
    $result = mysqli_query($conn, $query);
}elseif(isset($_POST["delpost"])){
    $targetID = $req["delpost"];
    $query = "DELETE FROM posts WHERE id = $targetID";
    $result = mysqli_query($conn, $query);

}elseif(isset($_POST["delcom"])){
    $targetID = $req["delcom"];
    $query = "DELETE FROM comments where id = $targetID";
    $result = mysqli_query($conn, $query);
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
    #querystats{
        display: flex;
        flex-direction: row;
    }
    #userdisplay{
        width: 100%;
        background-color: aquamarine;
    }
    .userRes{
        display: flex;
        background-color: lime;
        width: 100%;
    }
    .userRes p{
        margin-left: 1vw;
    }
    .userRes :nth-child(2){
        position: absolute;
        left:40%;
    }
    .userRes :nth-child(4){
        position: absolute;
        left: 70%;
        margin-top:1vh;
    }
    #postdisplay{
        width: 100%;
        background-color: aquamarine;
    }
    .gotopost{
        position: absolute;
        left: 60%;
        margin-top:1vh;
    }
    #commentdisplay{
        width: 100%;
        background-color: aquamarine;
    }
</style>


</head>
<body>
<div id="head">
<p>[<a href="index.php">SnapShack</a>]</p>
</div>
<div id="main">
    
    <div id="leftSpace">
    </div>
    <div id="mainContent">
        <div id="mainBox">
            <div id="mainTitle">
                <h1>Control panel </h1>
            </div>
           
            <h4>Search users, posts and comments</h4>
            <div id="topThreads">
               <form id='loginform' action='' method="POST">
                    <label for="keyword">Keyword</label>
                    <input type="text" name="keyword" required>
                
                    <button type="submit"> Search</button>
                   
                </form>

            
           
            </div>
            
                <?php
                echo "<span id='querystats'>";
                echo "<p>Found: $usercount users, </p>";
                echo "<p> $postcount posts, </p>";
                echo "<p> Found: $commentcount comments </p>";
                echo "</span>";
                ?>
            <?php
        
            if(isset($usermatch)){
                echo "<div id='userdisplay'> ";
                echo "Users matching criteria";
                while($row = mysqli_fetch_array($usermatch)){
                    $uid = $row['id'];
                    echo "<div class='userRes'><p>Username: " . $row["name"] ."</p><p> Email: ".$row['email']." <p><form action='' method ='POST' ><button type='submit' name='ban' value='$uid'> Permanently disable account </button></form></p></div> "; 
                }
                echo "</div>";
            }
            if(isset($postsmatch))
            {
                echo "<div id='postdisplay'>";
                echo "Post matching criteria";
                

                while($row = mysqli_fetch_array($postsmatch)){
                    $pid = $row['id'];
                    $boardid = $row['board'];
                    $tmpQuery = "SELECT fullName FROM board where id = $boardid";
                    $tmpResult = mysqli_query($conn, $tmpQuery);
                    $fullname = mysqli_fetch_array($tmpResult)['fullName'];
                    
                    echo "<div class='userRes'><p>Title: " . $row["title"] ."</p><p> Author ID: ".$row['author']." 
                            <p>
                                <form action='' method ='POST' >
                                    <button type='submit' name='delpost' value='$pid'> Permanently delete post </button>
                                </form>
                            </p>
                            <p>
                                <form action='post.php' method = 'GET'>
                                <input type='hidden' name='pid' value='$pid'>
                                <input type='hidden' name='fullName' value='$fullname'>
                                <button type='submit' class='gotopost'> Go to post</button>
                                 </form>
                        
                        
                            </div> "; 
                }
                echo "</div>";
            }

            if(isset($commentmatch)){
                echo "<div id='commentdisplay'>";
                echo "Comments matching criteria";
                while($row = mysqli_fetch_array($commentmatch)){
                    $cid = $row['id'];
                    $authorID = $row['author'];
                    $tmpQuery = "SELECT name FROM users where id = $authorID";
                    $tmpResult = mysqli_query($conn, $tmpQuery);
                    $author = mysqli_fetch_array($tmpResult)["name"];
                    
                    echo "<div class='userRes'><p>Author: " . $author ."</p><p> Content: ".$row['content']." 
                            <p>
                                <form action='' method ='POST' >
                                    <button type='submit' name='delcom' value='$cid'> Permanently delete comment </button>
                                </form>
                            </p>
                            
                               
                        
                            </div> "; 
                }
                echo "</div>";
            }

            // $usermatch;
            // $postsmatch;
            // $commentmatch;
        
            // $usercount = 0;
            // $postcount =0;
            // $commentcount =0 ;
    
        

        




            ?>
        </div>
    </div>
    <div id="rigthSpace">
    </div>
</div>


</body>
</html>

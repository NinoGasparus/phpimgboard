<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
    $postTitle = "";
    $postID = -1;
   if(isset($_GET)){
    $pid = $_GET['pid'];
    $req= $_GET;
    $name = $req['fullName'];
    $localuser;
    if(isset($_SESSION["localusername"])){
        $localuser = $_SESSION["localusername"];
    }else{
        $localuser = "Anonymus";
    }
    $localuserid;
    if(isset($_SESSION["localuserid"])){
        $localuserid = $_SESSION["localuserid"];
    }else{
        $localuserid = 1;
    }
    $order  = "";
    if(isset($_GET['ordering'])){
       
        if($_GET['ordering'] == 'likes'){
            $order = " ORDER BY likes DESC";
        }elseif($_GET["ordering"] == "recent"){
            $order = " ORDER BY timeCreated DESC";
        }
    }
}
    if(isset($_SESSION['localusername']) && isset( $_SESSION['localuserid']) && isset($_POST['logout'])){
        session_destroy();
    }
    
    $params;
    $conn = mysqli_connect("localhost","root","123123");
    mysqli_select_db($conn,"snapshack");
    $query = "SELECT fullName, shortName, id FROM board WHERE fullName != '$name'";
    $result = mysqli_query($conn, $query);

    $query2 = "SELECT id FROM board WHERE fullName = '$name'";
    $result2 = mysqli_query($conn, $query2);
    $row2 = mysqli_fetch_array($result2);
    $thisBoardID = $row2['id'];

    $pid = $_GET['pid'];
    $query3 = "SELECT users.name, comments.* FROM users, comments WHERE comments.author = users.id AND target = $pid". $order;
    $CommentBlock = mysqli_query($conn, $query3);


    if(isset($_POST['likecomment']) && isset( $_POST['commentID']) && isset($_POST['userIP'])){

    if(didlikeComment($_POST['commentID'], $_POST['userIP'],$conn)){
      $query = "UPDATE comments SET likes = likes+1 WHERE id = ".$_POST['commentID'];
      mysqli_query($conn, $query);     
      $ip = $_POST['userIP'];
      $tip = 'comment';
      $targetPost =$_POST['commentID'];
      $query = "INSERT INTO likeTracker (ip, tip, targetComment) VALUES ('$ip', '$tip', '$targetPost')";
      mysqli_query($conn, $query);




      
    }else{
        $query = "UPDATE comments SET likes = likes-1 WHERE id = ".$_POST['commentID'];
        mysqli_query($conn, $query);  
        $ip = $_POST['userIP'];
        $tip = 'comment';
        $targetPost =$_POST['commentID'];
        $query = "DELETE FROM likeTracker WHERE ip = '$ip' AND tip = '$tip' AND targetComment = '$targetPost'";
        mysqli_query($conn, $query);
    }
    if (isset($_POST['fullPath'])) {
        $fullPath = htmlspecialchars_decode($_POST['fullPath']);
        echo "<script>window.location.href = '$fullPath';</script>";
        } else {
            echo "<script>window.history.back();</script>";
        }
    }
    mysqli_close($conn);

    
?>
<?php
    
    function didlikeComment($commentid, $ip, $conn){
        $query = "SELECT * FROM likeTracker WHERE targetComment = $commentid";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
            return false;
        }else{
            return true;
        }

    }


function isAdmin($userid){
    $query = "SELECT isAdmin FROM users  WHERE id = $userid";
    $conn = mysqli_connect("localhost","root","123123");
    mysqli_select_db($conn,"snapshack");
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $row  = mysqli_fetch_array($result);
        if($row["isAdmin"] == 1){
            return true;
        }
        return false;
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapShack /<?php echo $name ?></title>
    <link rel="stylesheet" href="./boardpage.css">
    <script src="./navigator.js"></script>
   
<style>
.comcounter
{
    text-decoration: underline;
}
#commentsection{
    background-color: black;
}
.comment{
    background-color: white;
    color:black;
    font-weight: bold;
    margin-top: 1vh;
    margin-bottom: 1vh;
}
.comment button{
    margin-bottom: 1vh;
    margin-right: 0px;
}
.comAuthor{
    margin-top: 1vh;
}
.blocksomethingidkanymore
{
    display: flex;

}
.blocksomethingidkanymore :first-child{
    margin-right: 1vw;
}
.textcontent{}
    </style>

<script>
        let visible = false;
        function togglePane(){
            if(!visible){
            document.getElementById('createPost').style.right ='0';
            document.getElementById('showhidebutton').style.right = '33vw';
            document.getElementById('showhidebutton').innerText = ">";
            visible =true;
            }else{
            document.getElementById('createPost').style.right ='-33vw';
            document.getElementById('showhidebutton').style.right='0vw';
            document.getElementById('showhidebutton').innerText = "<";
            visible = false;
            }

}



</script>

</head>
<body>
<div id="head">
    <p>[<a href="index.php">SnapShack</a>]</p>
    <p>[<a href="login.php" target="blank">LogIn</a>]
        
    </p>
</div>
<div id="main">

    <div id="leftSpace">
        <?php echo "<div id='thisBoard' >ss/".$name."<br></div>";
       
        while($row = mysqli_fetch_array($result)){
            $params = '"'.$row["shortName"].'","'.$row["fullName"].'"';
            echo "<p onclick='redirectToBoard($params)' class='otherBoards'>ss/".$row["fullName"]."</p>";
        }
        ?>
    </div>
    <div id="mainContent">
        <div id="mainBox">
          
        <?php 


            $conn = mysqli_connect("localhost", "root", "123123");
            if($conn){
                mysqli_select_db($conn,"snapshack");
                $query = "SELECT posts.*, users.name FROM posts INNER JOIN users ON posts.author = users.id WHERE posts.board = $thisBoardID AND posts.id =$pid ORDER BY posts.timeCreated DESC";


                $result = mysqli_query($conn, $query);
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){
                    echo "<div class='post'>";
                        $postTitle = $row['title'];
                        $postAuthor = $row['name'];
                        $imageSource = $row['image'];
                        $likes = $row["likes"];
                        $comments = $row['comments'];
                        $content = $row['content'];
                        $timestamp = $row['timeCreated'];
                        $postID = $row['id'];
                        echo "
            
                        <div class='mainTitle'>
                            <h1 style='margin-bottom:1vh'>$postTitle </h1>
                            <h4 style='margin-top:1vh; margin-bottom:0px'>By $postAuthor 
                              On: $timestamp UTC</h4>
                        </div>
                        
                        <div class='Content'>
                            <div class='textcontent'>
                               $content
                            </div>
                            <img class='imgContent' src='$imageSource'>
                           
                        </div>";
                        
                        echo "
                        <div class='stats'>
                            <p> 
                                <form action='like.php' method='POST'>
                                    <input type='hidden' name='targetpost' value='$postID'>
                                    <input type='hidden' name='fullPath' value='" . htmlspecialchars($_SERVER['REQUEST_URI']) . "'>
                                    <input type='hidden' name='userIP' value='" . $_SERVER['REMOTE_ADDR'] ."'>
                                    <button type='submit' name='like'> ↑ </button>
                                    Likes : $likes ";
                                    
                                        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == '1'){
                                            echo "<button type='submit' name='deletePost'> Delete </button> ";
                                        }else if(isset($_SESSION['localusername']) && $postAuthor == $_SESSION["localusername"] && $localuserid != 1 && $_SESSION['localusername'] != "Anonymus"){
                                            echo "<button type='submit' name='deletePost'> Delete </button> ";
                                        }
                                    
                                    echo" 
                                </form>
                                
                            </p>
                          <button onclick='togglePane()'> Add comment</button>
                          <p class='comcounter'>Comments: $comments </p> "; ?>
                        <form action="post.php" method="GET">
                            <?php
                            foreach ($_GET as $key => $value) {
                                if($key != "ordering"){
                                echo "<input type='hidden' name='$key' value='$value'>";
                                }
                            }
                            ?>
                            <select name='ordering'>
                                <option value='likes'>By most liked</option>
                                <option value='recent'>Newest first</option>
                            </select>
                            <button type='submit'>Confirm</button>
                        </form>


                        </div>
                        <?php

                    
                        echo "<div id='commentsection'>";
                        while($row123 = mysqli_fetch_array($CommentBlock)){
                            echo "<div class='comment'>";
                                echo "<p class='comAuthor'>";
                                echo $row123['name'] . " at: " . $row123['timeCreated'] . " UTC </p>";
                                    
                                echo "<div class='blocksomethingidkanymore'>
                                <form action='' method ='POST'>
                                    <button type='submit' name='likecomment'>↑ </button>
                                    <input  type='hidden' name='commentID' value='".$row123['id']."'>
                                    <input  type='hidden' name='userIP' value='".$_SERVER['REMOTE_ADDR']."'>
                                </form>
                                <div>" . $row123['likes'] . " likes </div>
                                <div style='margin-left:1vw'>" . $row123['content'] . "</div>
                              </div>";
                        
                        
                            echo '</div>';
                        }
                        
                        echo "</div></div><br>";
                    }
                }else{
                    echo "<h1> No posts here, create one!</h1>";
                }
            }else{
                
                mysqli_close($conn);
            }

          
        ?>
         <div id="createPost">
          
          <?php  
            
            echo" <p>Posting as $localuser to ss/$name/$postTitle ";
            if($localuserid != 1){
               echo "<form action='' method='POST'><button type='submit' name='logout'>Log out</button></form>";
            }
            
            echo"</p>"; 
            
            
            ?>
               <form action="createcomment.php" method='POST' enctype="multipart/form-data" id>
               <input type="hidden" name="author" value=<?php echo"'$localuserid'"?>>
               <input type="hidden" name="targetpost" value=<?php echo"$postID"?>>
               <input type="hidden" name="fullPath" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
            <label for="content"> Write a comment* </label>
               <textarea name="content"required> </textarea>
               <button type="submit">Post </button>
                * Required 
             </form>
             <button onclick="togglePane()" id="showhidebutton"> > </button>
           </div>
         
        </div>
  
    </div>
</div>
</body>
</html>
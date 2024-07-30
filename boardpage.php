<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
    $orderPostsBy = "";
   if(isset($_GET)){
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

    if(isset($_GET['ordering'])){
        if($_GET['ordering'] == 'liked'){
            $orderPostsBy = " ORDER BY likes DESC";
        }elseif($_GET['ordering'] == 'recent'){
            $orderPostsBy = ' ORDER BY timeCreated DESC';
        }elseif($_GET['ordering'] == 'comments'){
            $orderPostsBy = ' ORDER BY comments DESC';
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


    mysqli_close($conn);


?>

<?php

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
<style>
.comcounter
{
    text-decoration: underline;
}
    </style>
</head>
<body>
<div id="head">
    <p>[<a href="index.php">SnapShack</a>]</p>
    <p>[<a href="login.php" target="blank">LogIn</a>]
        [<a onclick="togglePane()">New post</a>]

    <?php
        if(isAdmin($localuserid)){
            echo '
            [<a href="asdffdsa.php">Control panel</a>]
            ';
        }
    ?>
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
        <form action='' method='GET'> 
            <?php
                foreach ($_GET as $key => $value) {
                    if($key != "ordering"){
                    echo "<input type='hidden' name='$key' value='$value'>";
                    }
                }
            ?>
        <select name='ordering'>
            <option value='liked'> Most liked first </option>
            <option value='recent'> Newest first  </option>
            <option value='comments'> Most comments first </option>
        </select>
        <button type='submit'> Confirm</button>
    </form>
        <div id="mainBox">
           <div id="createPost">
          
             <?php  
             
             echo" <p>Posting as $localuser to ss/$name ";
             if($localuserid != 1){
                echo "<form action='' method='POST'><button type='submit' name='logout'>Log out</button></form>";
             }
             
             echo"</p>"; 
             
             
             ?>
           
                <form action="createpost.php" method='POST' enctype="multipart/form-data" id>
                <input type="hidden" name="author" value=<?php echo"'$localuserid'"?>>
                <input type="hidden" name="board" value=<?php echo"$thisBoardID"?>>
                <input type="hidden" name="fullPath" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                <label for="title" > Post's title* </label>
                <input type="text" name="title" required>

                <label for="content"> Main content* </label>
                <textarea name="content"required> </textarea>
                
                <label for="image"> Attach an image </label>
                <input type="file" name="image" accept="image/png, image/jpg, image/gif">
                <button type="submit">Post </button>
                 * Required 
              </form>
              <button onclick="togglePane()" id="showhidebutton"> > </button>
            </div>
          
        <?php 


            $conn = mysqli_connect("localhost", "root", "123123");
            if($conn){
                mysqli_select_db($conn,"snapshack");
                $query = "SELECT posts.*, users.name FROM posts INNER JOIN users ON posts.author = users.id WHERE posts.board = $thisBoardID ". $orderPostsBy;


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
                                    Likes : $likes";
                                    
                                    if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == '1'){
                                        echo "<button type='submit' name='deletePost'> Delete </button> ";
                                    }else if(isset($_SESSION['localusername']) && $postAuthor == $_SESSION["localusername"] && $localuserid != 1 && $_SESSION['localusername'] != "Anonymus"){
                                        echo "<button type='submit' name='deletePost'> Delete </button> ";
                                    }
                                    
                                    echo" 
                                </form>
                                
                            </p>
                            <form action='post.php' method='GET'>
                            <p class='comcounter'>
                            <button type='submit' name='pid' value='$postID'>Comments </button>
                            : $comments </p>
                            <input type = 'hidden' name='fullName' value='$name'>
                            </form>
                        </div>
                        ";

                       
                    echo "</div><br>";
                    }
                }else{
                    echo "<h1> No posts here, create one!</h1>";
                }
            }else{
                
                mysqli_close($conn);
            }

          
        ?>
        </div>
  
    </div>
</div>
</body>
</html>
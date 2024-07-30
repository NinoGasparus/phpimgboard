<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$req = $_POST;
if(isset($req["author"]) &&  isset($req['targetpost']) && isset($req['fullPath']) && isset($req['content'])){

    $conn = mysqli_connect('localhost', 'root', '123123');
    mysqli_select_db($conn,'snapshack');
    $author = $req['author'];
    $targetPost= $req['targetpost'];
    $content = $req['content'];
    $query = "INSERT INTO comments (author, target, content) VALUES($author, $targetPost, '$content')";
    $result = mysqli_query($conn, $query);
    $query = "UPDATE posts SET comments = comments+1 WHERE id = $targetPost";
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);


}

if (isset($_POST['fullPath'])) {
$fullPath = htmlspecialchars_decode($_POST['fullPath']);
echo "<script>window.location.href = '$fullPath';</script>";
} else {
    echo "<script>window.history.back();</script>";
}


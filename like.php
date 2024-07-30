<?php

$targetPost = $_POST['targetpost'];
$conn = mysqli_connect('localhost', 'root', '123123');
mysqli_select_db($conn, 'snapshack');

$userIP = $_POST['userIP'];

if (isset($_POST['like'])) {
    if (didLike($conn, $userIP, $targetPost)) {
        decrementLike($conn, $targetPost, $userIP);
    } else {
        incrementLike($conn, $targetPost, $userIP);
    }
}
if(isset($_POST['deletePost'])) {
    $query = "DELETE FROM posts where id = '" . $targetPost . "'";
    mysqli_query($conn, $query);
}

mysqli_close($conn);

if (isset($_POST['fullPath'])) {
    $fullPath = htmlspecialchars_decode($_POST['fullPath']);
    echo "<script>window.location.href = '$fullPath';</script>";
} else {
    echo "<script>window.history.back();</script>";
}
function incrementLike($conn, $targetPost, $userIP)
{
    $query = "UPDATE posts SET likes = likes + 1 WHERE id = " . $targetPost;
    mysqli_query($conn, $query);

    $query = "INSERT INTO likeTracker(ip, tip, targetPost) VALUES('" . $userIP . "', 'like', " . $targetPost . ")";
    mysqli_query($conn, $query);
}

function decrementLike($conn, $targetPost, $userIP)
{
    $query = "UPDATE posts SET likes = likes - 1 WHERE id = " . $targetPost;
    mysqli_query($conn, $query);

    $query = "DELETE FROM likeTracker WHERE ip = '" . $userIP . "' AND targetPost = " . $targetPost . " AND tip = 'like'";
    mysqli_query($conn, $query);
}

function didLike($conn, $userIP, $targetPost)
{
    $query = "SELECT * FROM likeTracker WHERE ip = '" . $userIP . "' AND targetPost = " . $targetPost . " AND tip = 'like'";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

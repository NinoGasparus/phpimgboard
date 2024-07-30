<?php
$req = $_POST;
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($req) {
    $conn = mysqli_connect("localhost", "root", "123123");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_select_db($conn, "snapshack");

    $author = $req['author'];
    if ($author == '1') {
        $author = 1;
    } else {
       
    }

    $title = $req['title'];
    $content = $req['content'];
    $image = NULL;
    $board = $req['board'];

     
    if(isset($_FILES["image"])) {
        $file = $_FILES["image"]["tmp_name"];
        $originalFilename = $_FILES["image"]["name"];
        $fileExtension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $currentTimeSeconds = time();
        $uniqueFilename = md5($originalFilename . $author . $currentTimeSeconds) . '.' . $fileExtension;
    
        $destination = "./slike/" . $uniqueFilename;
    
        if(move_uploaded_file($file, $destination)) {
            echo "File uploaded successfully.";
            $image = "./slike/".$uniqueFilename;
        } else {
            echo "Error moving uploaded file.";
        }
    }
    



    $query = "INSERT INTO posts(author, title, content, image, board) VALUES ($author, '$title', '$content', '$image', $board)";

    if (mysqli_query($conn, $query)) {
        echo "Record inserted successfully";
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }

   
    mysqli_close($conn);
}

if (isset($_POST['fullPath'])) {
    $fullPath = htmlspecialchars_decode($_POST['fullPath']);
    echo "<script>window.location.href = '$fullPath';</script>";
} else {
    echo "<script>window.history.back();</script>";
}
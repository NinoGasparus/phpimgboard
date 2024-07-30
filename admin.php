<?php
    $req = $_POST;
    if (isset($req["keyword"])) {
        $keyword = $req['keyword'];
        $conn = mysqli_connect("localhost", "root", "123123");
        mysqli_select_db($conn,"snapshack");

        $usersQuery = "SELECT * FROM users WHERE disabled = false AND (LOWER(name) LIKE LOWER('%$keyword%') OR LOWER(email) LIKE LOWER('%$keyword%'))";
        $usermatch = mysqli_query($conn, $usersQuery);

        $postsQuery=  "SELECT * FROM posts WHERE LOWER(title) LIKE LOWER('%$keyword%') OR LOWER(content) LIKE LOWER('%$keyword%')";
        $postsmatch = mysqli_query($conn, $postsQuery);

        $commentQuery = "SELECT * FROM comments WHERE LOWER(content) LIKE LOWER('%$keyword%')";
        $commentmatch = mysqli_query($conn, $commentQuery);


        $usercount = mysqli_num_rows($usermatch);
        $postcount = mysqli_num_rows($postsmatch);
        $commentcount = mysqli_num_rows($commentmatch);
        
    }
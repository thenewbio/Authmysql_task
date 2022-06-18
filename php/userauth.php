<?php

require_once "../config.php";
// require_once "..users.sql";



//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db();

   //check if user with this email already exist in the database
   if (isset($_POST['fullnames'])) {
    // removes backslashes
    $fullnames = $_POST['fullnames'];
    //escapes special characters in a string
    $fullnames = mysqli_real_escape_string($conn, $fullnames);
    $email    = $_POST['email'];
    $email    = mysqli_real_escape_string($conn, $email);
    $password = $_POST['password'];
    $password = mysqli_real_escape_string($conn, $password);
    $country = $_POST['country'];
    $country = mysqli_real_escape_string($conn, $country);
    $gender = $_POST['gender'];
    $query    =  "INSERT INTO `Students` (`fullnames`,`email`, `country`,  `password`, `gender`)
                 VALUES ('$fullnames',  '$email', '$country','$password', '$gender');";
    $result   = mysqli_query($conn, $query);
    if ($result) {
        echo " <script>alert('User Successfully registered');
        window.location='../forms/login.html';
    </script>";
    } 
}
}


//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    //if it does, check if the password is the same with what is given
    //if true then set user session for the user and redirect to the dasbboard
    if (isset($_POST['email'])) {
        $email = $_POST['email'];    // removes backslashes
        $email = mysqli_real_escape_string($conn, $email);
        $password = $_POST['password'];
        $password = mysqli_real_escape_string($conn, $password);
        // Check user is exist in the database
        $query    = "SELECT * FROM `Students` WHERE email ='$email'
                     AND password='$password'";
        $result = mysqli_query($conn, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $_SESSION['fullnames'] = $fullnames;
            // Redirect to user dashboard page
            echo '<script>alert("Welcome ' . $_SESSION['fullnames'] . ' ");
              window.location="../dashboard.php";
              </script>';
        } else {
            echo '<script>alert("Invalid Username or Password");
            window.location="../forms/login.html";
            </script>';
        }
    } 
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    //if it does, replace the password with $password given
    if (isset($_POST['email'])) {
        $email = $_POST['email'];    // removes backslashes
        $email = mysqli_real_escape_string($conn, $email);
        $password = $_POST['password'];
        $data = $conn -> prepare("SELECT * FROM `Students` WHERE email = ?");
        $data -> execute([$email]);
         $emailexist = $data->fetch();
        if (!$emailexist) {
            echo "An Error Occured";
            } else{
               $email = $_POST['email'];
               $password = $_POST['password'];
               $conn = db();
               $sql = "SELECT * FROM `Students` WHERE email = '".$email."'";
               $newPass = "UPDATE Students SET password='".$password."' WHERE email ='".$email."'";

               if (mysqli_query($conn, $newPass)){
                header ("Location: ../forms/login.html");
               } mysqli_close($conn);
            }
            }
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
     $sql = "DELETE from Students where id= $id";
if($conn){
    if (mysqli_query($conn, $sql)) {
	echo "Student successfully Deleted";
    header ("Location: ../forms/register.html");
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
 }

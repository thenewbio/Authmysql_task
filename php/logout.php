<?php

session_start();  
session_destroy();  
header("Location: ../forms/login.html");//use for the redirection to login page  
?> 
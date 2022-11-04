<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
Homepage
A basic webpage with two links shaped as buttons for login and registration.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder</title>
        <link rel="stylesheet" href="styles.css"/>
    </head>
    <body>
        <?php
        // The header will be included in all webpages for navigation
        include '../includes/header.php';
        ?>
        <p style="padding: 50px; font: 50px georgia">
            Welcome to Career Ladder
        </p>
        <a class="button" href="login.php">Login</a>
        <a class="button" href="registration">Register</a>
    </body>
</html>

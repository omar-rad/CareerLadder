<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
Registration
The main registration webpage with three links to register as a specific actor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder - Register</title>
        <link rel="stylesheet" href="../styles.css"/>
        <style>
            br {
                margin-bottom: 100px;
            }
        </style>
    </head>
    <body>
        <?php
        include '../../includes/header.php';
        ?>
        <p style="padding-bottom:25px;">Register as</p>
        <p>
            <a class="button" href="./company.php">Company</a><br>
            <a class="button" href="./enduser.php">End User</a><br>
            <a class="button" href="./hrr.php">HRR</a>
        </p>
    </body>
</html>

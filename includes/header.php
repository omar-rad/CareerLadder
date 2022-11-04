<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
Header
The webpages' header for navigation
-->
<?php
session_start();
if (!empty($_SESSION) // Redirect for a required login
        and basename(dirname($_SERVER['PHP_SELF'])) != $_SESSION['redirect']) {
    header('Location: http://' . $_SERVER['SERVER_NAME']
            . '/project/careerladder/' . $_SESSION['redirect']);
    exit();
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            .header {
                padding: 50px;
                font: bold 50px verdana;
                text-align: center;
                color: #ffcb9a;
                background-color: #116466;
                box-shadow: 0 7px 7px rgba(0,0,0,0.21);
            }
        </style>
    </head>
    <body>
        <div class="header">
            <?php
            echo '<a style="text-decoration: none; color: inherit;"'
            . 'href="http://' . $_SERVER['SERVER_NAME']
            . '/project/careerladder">Career Ladder</a>';
            ?>
        </div>
    </body>
</html>

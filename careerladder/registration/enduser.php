<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
End User's Registration
The webpage asks related information for an end user's registration.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder - Register</title>
        <link rel="stylesheet" href="../styles.css"/>
    </head>
    <body>
        <?php
        include '../../includes/header.php';
        ?>
        <p style="color: #cf6679; font-size: 20px;">
            This color indicates required fields
        </p>
        <form method="post">
            <label style="color: #cf6679;" for="username">Username</label>
            <input type="text" id="username" name="username"
                   pattern="(?!^\d+$)^.+$"
                   title="At least one character is required" required>
            <label style="color: #cf6679" for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="fname">First name</label>
            <input type="text" id="fname" name="fname">
            <label for="lname">Last name</label>
            <input type="text" id="lname" name="lname">
            <label for="military">Military service status</label>
            <select id="military" name="military">
                <option></option>
                <option value="C">Completed</option>
                <option value="D">Delayed</option>
                <option value="E">Exempted</option>
            </select>
            <input type="submit" value="Register">
        </form>
        <?php
        if (!empty(($_POST))) {
            include '../../includes/db.php';
            // Check if the username already used
            querify($_POST);
            $username = $_POST['username'];
            $query = 'SELECT username '
                    . 'FROM end_user '
                    . 'WHERE username = ' . $username . ' '
                    . 'UNION '
                    . 'SELECT username '
                    . 'FROM hrr '
                    . 'WHERE username = ' . $username . ';';
            if (mysqli_num_rows(mysqli_query($connection, $query))) {
                echo '<p style="font-size: 20px">'
                . 'Username already exists!'
                . '</p>';
            } else { // If the username is unique, register the end user
                $query = 'INSERT INTO end_user VALUES ('
                        . $username . ', ' . $_POST['password'] . ', '
                        . $_POST['fname'] . ', ' . $_POST['lname'] . ', '
                        . $_POST['military']
                        . ');';
                insert($query);
            }
        }
        ?>
    </body>
</html>

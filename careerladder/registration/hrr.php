<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
HRR's Registration
The webpage asks related information for an hrr's registration.
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
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            <label for="fname">First name</label>
            <input type="text" id="fname" name="fname">
            <label for="lname">Last name</label>
            <input type="text" id="lname" name="lname">
            <label for="eusername">End user's username</label>
            <select id="eusername" name="eusername">
                <option></option>
                <?php
                include '../../includes/db.php';
                // List available end user's usernames
                $query = 'SELECT e.username '
                        . 'FROM end_user e LEFT JOIN hrr '
                        . 'ON e.username = endUser_username '
                        . 'WHERE endUser_username IS NULL;';
                $result = mysqli_query($connection, $query);
                foreach ($result as $row) {
                    $eusername = $row['username'];
                    echo '<option value="' . $eusername . '">'
                    . $eusername . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="Register">
        </form>
        <?php
        if (!empty(($_POST))) {
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
            } else { // If the username is unique, register the hrr
                $query = 'INSERT INTO hrr VALUES ('
                        . $username . ', ' . $_POST['password'] . ', '
                        . $_POST['email'] . ', ' . $_POST['fname'] . ', '
                        . $_POST['lname'] . ', ' . $_POST['eusername']
                        . ');';
                insert($query);
            }
        }
        ?>
    </body>
</html>

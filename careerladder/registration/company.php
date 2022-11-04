<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
Company's Registration
The webpage asks related information for a company's registration.
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
            <label style="color: #cf6679;" for="cid">Company's ID</label>
            <input type="number" id="cid" name="cid" min="0" required>
            <label for="name">Name</label>
            <input type="text" id="name" name="name">
            <label for="phone">Phone number</label>
            <input type="tel" id="phone" name="phone"
                   pattern="\d{10}" title="Ten digits phone number">
            <label for="address">Address</label>
            <textarea id="address" name="address"></textarea>
            <input type="submit" value="Register">
        </form>
        <?php
        if (!empty(($_POST))) {
            include '../../includes/db.php';
            // Check if the company's ID already used
            $query = 'SELECT cid '
                    . 'FROM company '
                    . 'WHERE cid = ' . $_POST['cid'] . ';';
            if (mysqli_num_rows(mysqli_query($connection, $query))) {
                echo '<p style="font-size: 20px">'
                . 'Company\'s ID already exists!'
                . '</p>';
            } else { // If the company's ID is unique, register the company
                querify($_POST);
                $query = 'INSERT INTO company VALUES ('
                        . $_POST['cid'] . ', ' . $_POST['name'] . ', '
                        . $_POST['address'] . ', ' . $_POST['phone']
                        . ');';
                insert($query);
            }
        }
        ?>
    </body>
</html>

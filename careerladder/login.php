<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
Login
The login webpage detects the actor accordingly to the first required field.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder - Login</title>
        <link rel="stylesheet" href="styles.css"/>
    </head>
    <body>
        <?php
        include '../includes/header.php';
        ?>
        <p style="color: #cf6679; font-size: 20px;">
            Companies can login using their HRRs' passwords
        </p>
        <form method="post">
            <label for="id">Company's ID / Username</label>
            <input type="text" id="id" name="id" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <?php
        include '../includes/db.php';
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $password = $_POST['password'];
            if (ctype_digit($id)) { // Check if it's a company's ID
                $query = 'SELECT cid '
                        . 'FROM company '
                        . 'WHERE cid = ' . $id . ';';
                if (mysqli_num_rows(mysqli_query($connection, $query))) {
                    /*
                     * If the company's ID exists,
                     * check if the password is one of their hrrs' passwords
                     */
                    $query = 'SELECT DISTINCT `passwrd` '
                            . 'FROM hrr '
                            . 'WHERE username IN ('
                            . 'SELECT DISTINCT hrr_username '
                            . 'FROM job_posting '
                            . 'WHERE comp_cid = ' . $id
                            . ');';
                    $result = mysqli_query($connection, $query);
                    foreach ($result as $row) {
                        if ($password === $row['passwrd']) {
                            $_SESSION['id'] = $id;
                            $_SESSION['redirect'] = 'company';
                            header('Location: company');
                            exit();
                        }
                    }
                    /*
                     * If the password is incorrect
                     * or the company doesn't have any hrr
                     */
                    echo '<p style="font-size: 20px">'
                    . 'Company\'s ID or password is incorrect!'
                    . '</p>';
                }
            } else { // The ID is considered as a username
                querify($_POST);
                $id = $_POST['id'];
                // Check if it's an end user's username
                $query = 'SELECT username, passwrd '
                        . 'FROM end_user '
                        . 'WHERE username = ' . $id . ';';
                $row = mysqli_fetch_assoc(mysqli_query($connection, $query));
                // Check the password
                if ($row and $password === $row['passwrd']) {
                    $_SESSION['id'] = $id;
                    $_SESSION['redirect'] = 'enduser';
                    header('Location: enduser');
                    exit();
                }
                /*
                 * If the password is incorrect,
                 * check if that was an hrr's username
                 */
                $query = 'SELECT username, passwrd '
                        . 'FROM hrr '
                        . 'WHERE username = ' . $id . ';';
                $r = mysqli_fetch_assoc(mysqli_query($connection, $query));
                // Check the password
                if ($r and $password === $r['passwrd']) {
                    $_SESSION['id'] = $id;
                    $_SESSION['redirect'] = 'hrr';
                    header('Location: hrr');
                    exit();
                } else {
                    echo '<p style="font-size: 20px">'
                    . 'Username or password is incorrect!'
                    . '</p>';
                }
            }
        }
        ?>
    </body>
</html>

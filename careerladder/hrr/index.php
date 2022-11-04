<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
HRR
The HRR's homepage offers related services.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder - HRR</title>
        <link rel="stylesheet" href="../styles.css"/>
    </head>
    <body>
        <?php
        include '../../includes/header.php';
        // Prevents information access when login is required
        if (empty($_SESSION) or $_SESSION['redirect'] != 'hrr') {
            header('Location: ../login.php');
            exit();
        }
        if ($_SERVER['QUERY_STRING'] === 'logout') { // Logout
            session_destroy();
            header('Location: ..');
            exit();
        }
        // Displays the HRR's information
        include '../../includes/db.php';
        $query = 'SELECT * '
                . 'FROM hrr '
                . 'WHERE username = ' . $_SESSION['id'] . ';';
        $row = mysqli_fetch_assoc(mysqli_query($connection, $query));
        $fname = ucfirst(strtolower($row['fname']));
        $style = '';
        $x = true;
        if (empty($fname)) {
            $style = 'style="display: none"';
            $x = false;
        }
        ?>
        <p <?php echo $style; ?>>Welcome Back, <?php echo $fname; ?>!</p>
        <br <?php
        if ($x) {
            echo 'style="display: none"';
        }
        ?>>
        <table>
            <tr>
                <th>Username</th>
                <td><?php echo $row['username']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $row['email']; ?></td>
            </tr>
            <tr>
                <th>First Name</th>
                <td><?php echo $fname ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo ucfirst(strtolower($row['lname'])); ?></td>
            </tr>
            <tr>
                <th>End User's Username</th>
                <td><?php echo $row['endUser_username']; ?></td>
            </tr>
        </table>
        <br style="margin-bottom: 25px">
        <a class="button" href="information.php?q=add">
            Add Job Posting
        </a>
        <br style="margin-bottom: 82px">
        <a class="button" href="information.php?q=jobs">
            Job Postings
        </a>
        <br style="margin-bottom: 82px">
        <a class="button" href="?logout">
            Log Out
        </a>
        <br style="margin-bottom: 50px">
    </body>
</html>

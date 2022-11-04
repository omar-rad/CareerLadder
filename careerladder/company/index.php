<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--
Company
The company's homepage offers related services.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder - Company</title>
        <link rel="stylesheet" href="../styles.css"/>
    </head>
    <body>
        <?php
        include '../../includes/header.php';
        // Prevents information access when login is required
        if (empty($_SESSION) or $_SESSION['redirect'] != 'company') {
            header('Location: ../login.php');
            exit();
        }
        if ($_SERVER['QUERY_STRING'] === 'logout') { // Logout
            session_destroy();
            header('Location: ..');
            exit();
        }
        // Displays the company's information
        include '../../includes/db.php';
        $query = 'SELECT * '
                . 'FROM company '
                . 'WHERE cid = ' . $_SESSION['id'] . ';';
        $row = mysqli_fetch_assoc(mysqli_query($connection, $query));
        ?>
        <br>
        <table>
            <tr>
                <th>Company's ID</th>
                <td><?php echo $row['cid']; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo $row['name']; ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo $row['address']; ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo $row['phone']; ?></td>
            </tr>
        </table>
        <br style="margin-bottom: 25px">
        <a class="button" href="information.php?q=hrrs">
            HRRs
        </a>
        <br style="margin-bottom: 82px">
        <a class="button" href="information.php?q=jobs">
            Job Postings
        </a>
        <br style="margin-bottom: 82px">
        <a class="button" href="information.php?q=interns">
            Internship Postings
        </a>
        <br style="margin-bottom: 82px">
        <a class="button" href="?logout">
            Log Out
        </a>
        <br style="margin-bottom: 50px">
    </body>
</html>

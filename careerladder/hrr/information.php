<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder - Information</title>
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
        include '../../includes/db.php';
        $username = $_SESSION['id'];
        $aStyle = $jStyle = 'style="display: none"';
        $j = false;
        if (!empty($_GET['q'])) {
            switch ($_GET['q']) { // Chooses the service type to display
                case 'add':
                    $aStyle = '';
                    echo '<p>Add Job Posting</p>';
                    break;
                case 'jobs':
                    $query = 'SELECT * '
                            . 'FROM job_posting '
                            . 'WHERE hrr_username = ' . $username . ';';
                    $result = mysqli_query($connection, $query);
                    echo '<p>HRR\'s Job Postings</p>';
                    if (mysqli_num_rows(mysqli_query($connection, $query))) {
                        $jStyle = 'style="font-size: 15px"';
                        $j = true;
                    } else {
                        echo '<p style="font-weight: bold; color: #ffcb9a">'
                        . 'No Job Posting Was Found'
                        . '</p>';
                    }
                    break;
                default:
                    header('Location: .');
                    exit();
            }
        }
        ?>
        <div <?php echo $aStyle; ?>>
            <p style="color: #cf6679; font-size: 20px;">
                This color indicates required fields
            </p>
            <form method="post">
                <label style="color: #cf6679;" for="jid">Job's ID</label>
                <input type="number" id="jid" name="jid" min="0" required>
                <label for="desc">Description</label>
                <textarea id="desc" name="desc"></textarea>
                <label for="salary">Salary</label>
                <input type="number" id="salary" name="salary" min="0">
                <label for="phone">Phone number</label>
                <input type="tel" id="phone" name="phone"
                       pattern="\d{10}" title="Ten digits phone number">
                <label for="openings">Openings' number</label>
                <input type="number" id="openings" name="openings" min="1">
                <label for="openingd">Opening's date</label>
                <input type="date" id="openingd" name="openingd"
                       min="<?php echo date('Y-m-d'); ?>">
                <label for="duration">Duration</label>
                <input type="number" id="duration" name="duration" min="1">
                <label style="color: #cf6679" for="cid">Company's ID</label>
                <select id="cid" name="cid">
                    <?php
                    // List companies' IDs
                    if (empty($aStyle)) {
                        $query = 'SELECT cid FROM company;';
                        $result = mysqli_query($connection, $query);
                        foreach ($result as $row) {
                            $cid = $row['cid'];
                            echo '<option value="' . $cid . '">'
                            . $cid . '</option>';
                        }
                    }
                    ?>
                </select>
                <label for="mi">Job's type</label>
                <select id="mi" name="mi">
                    <option value="0"></option>
                    <option value="1">Manager</option>
                    <option value="2">Internship</option>
                </select>
                <label for="ct">Military service status</label>
                <select id="ct" name="ct">
                    <option></option>
                    <option value="PT">Part time</option>
                    <option value="FT">Full time</option>
                </select>
                <input type="submit" value="Add">
            </form>
            <?php
            if (!empty($_POST)) {
                // Check if the job's ID already used
                $query = 'SELECT jid '
                        . 'FROM job_posting '
                        . 'WHERE jid = ' . $_POST['jid'] . ';';
                if (mysqli_num_rows(mysqli_query($connection, $query))) {
                    echo '<p style="font-size: 20px">'
                    . 'Job\'s ID already exists!'
                    . '</p>';
                } else { // If the job's ID is unique, insert the job posting
                    querify($_POST);
                    $query = 'INSERT INTO job_posting VALUES ('
                            . $_POST['jid'] . ', ' . $_POST['desc'] . ', '
                            . $_POST['salary'] . ', ' . $_POST['phone'] . ', '
                            . $_POST['openings'] . ', ' . $username . ', '
                            . $_POST['openingd'] . ', ' . $_POST['duration'] . ', '
                            . $_POST['cid'] . ', ' . $_POST['mi'] . ', '
                            . $_POST['ct'] . ');';
                    insert($query);
                }
            }
            ?>
        </div>
        <table <?php echo $jStyle; ?>>
            <tr>
                <th>Job's ID</th>
                <th>Description</th>
                <th>Salary</th>
                <th>Contact Number</th>
                <th>Openings' Number</th>
                <th>Opening's Date</th>
                <th>Duration</th>
                <th>Company's ID</th>
                <th>Job's Type</th>
                <th>Contract's Type</th>
            </tr>
            <?php
            if ($j) {
                // Displays the hrr's job postings' information
                foreach ($result as $row) {
                    $jType = &$row['is_manOrIntern'];
                    $cType = &$row['contract_type'];
                    if ($jType === 1) {
                        $jType = 'Manager';
                    } elseif ($jType == 2) {
                        $jType = 'Internship';
                    } else {
                        $jType = 'Normal';
                    }
                    if ($cType === 'PT') {
                        $cType = 'Part Time';
                    } elseif ($cType === 'FT') {
                        $cType = 'Full Time';
                    }
                    echo '<tr>'
                    . '<td>' . $row['jid'] . '</td>'
                    . '<td>' . $row['description'] . '</td>'
                    . '<td>' . $row['salary'] . '</td>'
                    . '<td>' . $row['phone'] . '</td>'
                    . '<td>' . $row['numOpenings'] . '</td>'
                    . '<td>' . $row['openingdate'] . '</td>'
                    . '<td>' . $row['duration'] . '</td>'
                    . '<td>' . $row['comp_cid'] . '</td>'
                    . '<td>' . $jType . '</td>'
                    . '<td>' . $cType . '</td>'
                    . '</tr>';
                }
            }
            ?>
        </table>
    </body>
</html>

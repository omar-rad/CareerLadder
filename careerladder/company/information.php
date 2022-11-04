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
        if (empty($_SESSION) or $_SESSION['redirect'] != 'company') {
            header('Location: ../login.php');
            exit();
        }
        include '../../includes/db.php';
        $cid = $_SESSION['id'];
        $hStyle = $jStyle = $iStyle = $aStyle = $dStyle = 'style="display: none"';
        $j = $i = $a = false;
        if (!empty($_GET['q'])) {
            switch ($_GET['q']) { // Chooses the service type to display
                case 'hrrs':
                    $hStyle = '';
                    echo '<p>Company\'s HRRs</p>';
                    break;
                case 'jobs':
                    $jStyle = 'style="font-size: 15px"';
                    $j = true;
                    echo '<p>Company\'s Job Postings</p>';
                    break;
                case 'interns':
                    $query = 'SELECT *, (SELECT COUNT(*) '
                            . 'FROM application a '
                            . 'WHERE a.jid = j.jid) n '
                            . 'FROM internshipjobposting NATURAL JOIN job_posting j '
                            . 'WHERE comp_cid = '
                            . $cid . ';';
                    $result = mysqli_query($connection, $query);
                    echo '<p>Company\'s Internship Postings</p>';
                    if (mysqli_num_rows($result)) {
                        $iStyle = 'style="font-size: 15px"';
                        $i = true;
                    } else {
                        echo '<p style="font-weight: bold; color: #ffcb9a">'
                        . 'No Internship Posting Was Found'
                        . '</p>';
                    }
                    break;
                default:
                    header('Location: .');
                    exit();
            }
        } elseif (!empty($_GET['jid'])) {
            $aStyle = 'style="font-size: 20px"';
            $a = true;
            echo '<p>Job Posting\'s Applications</p>';
        } elseif (!empty($_GET['d'])) {
            $dStyle = '';
        }
        ?>
        <table <?php echo $hStyle; ?>>
            <tr>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
            <?php
            if (empty($hStyle)) {
                // Displays the company's hrrs' information
                $query = 'SELECT DISTINCT username, fname, lname '
                        . 'FROM hrr, job_posting '
                        . 'WHERE username = hrr_username AND comp_cid = '
                        . $cid . ';';
                $result = mysqli_query($connection, $query);
                foreach ($result as $row) {
                    echo '<tr>'
                    . '<td>' . $row['username'] . '</td>'
                    . '<td>' . ucfirst(strtolower($row['fname'])) . '</td>'
                    . '<td>' . ucfirst(strtolower($row['lname'])) . '</td>'
                    . '</tr>';
                }
            }
            ?>
        </table>
        <table <?php echo $jStyle; ?>>
            <tr>
                <th>Job's ID</th>
                <th>Description</th>
                <th>Salary</th>
                <th>Contact Number</th>
                <th>Openings' Number</th>
                <th>HRR's Username</th>
                <th>Opening's Date</th>
                <th>Duration</th>
                <th>Job's Type</th>
                <th>Contract's Type</th>
                <th>Applicants' Number</th>
            </tr>
            <?php
            if ($j) { // Displays the company's job postings' information
                $query = 'SELECT *, (SELECT COUNT(*) '
                        . 'FROM application a '
                        . 'WHERE a.jid = j.jid) n '
                        . 'FROM job_posting j '
                        . 'WHERE comp_cid = '
                        . $cid . ';';
                $result = mysqli_query($connection, $query);
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
                    $jid = $row['jid'];
                    foreach ($row as &$value) {
                        if (is_null($value)) {
                            $value = '&nbsp';
                        }
                        if ($row['n']) {
                            $value = '<a href="?jid=' . $jid
                                    . '">' . $value . '</a>';
                        }
                    }
                    echo '<tr>'
                    . '<td>' . $row['jid'] . '</td>'
                    . '<td>' . $row['description'] . '</td>'
                    . '<td>' . $row['salary'] . '</td>'
                    . '<td>' . $row['phone'] . '</td>'
                    . '<td>' . $row['numOpenings'] . '</td>'
                    . '<td>' . $row['hrr_username'] . '</td>'
                    . '<td>' . $row['openingdate'] . '</td>'
                    . '<td>' . $row['duration'] . '</td>'
                    . '<td>' . $jType . '</td>'
                    . '<td>' . $cType . '</td>'
                    . '<td>' . $row['n'] . '</td>'
                    . '</tr>';
                }
            }
            ?>
        </table>
        <table <?php echo $iStyle; ?>>
            <tr>
                <th>Job's ID</th>
                <th>Description</th>
                <th>Salary</th>
                <th>Contact Number</th>
                <th>Openings' Number</th>
                <th>HRR's Username</th>
                <th>Opening's Date</th>
                <th>Duration</th>
                <th>Contract's Type</th>
                <th>Days' Minimum Number</th>
                <th>Applicants' Number</th>
            </tr>
            <?php
            if ($i) { // Displays the company's internship postings' information
                foreach ($result as $row) {
                    $cType = &$row['contract_type'];
                    if ($cType === 'PT') {
                        $cType = 'Part Time';
                    } elseif ($cType === 'FT') {
                        $cType = 'Full Time';
                    }
                    $jid = $row['jid'];
                    foreach ($row as &$value) {
                        if (is_null($value)) {
                            $value = '&nbsp';
                        }
                        if ($row['n']) {
                            $value = '<a href="?jid=' . $jid
                                    . '">' . $value . '</a>';
                        }
                    }
                    echo '<tr>'
                    . '<td>' . $row['jid'] . '</td>'
                    . '<td>' . $row['description'] . '</td>'
                    . '<td>' . $row['salary'] . '</td>'
                    . '<td>' . $row['phone'] . '</td>'
                    . '<td>' . $row['numOpenings'] . '</td>'
                    . '<td>' . $row['hrr_username'] . '</td>'
                    . '<td>' . $row['openingdate'] . '</td>'
                    . '<td>' . $row['duration'] . '</td>'
                    . '<td>' . $cType . '</td>'
                    . '<td>' . $row['minnumDays'] . '</td>'
                    . '<td>' . $row['n'] . '</td>'
                    . '</tr>';
                }
            }
            ?>
        </table>
        <table <?php echo $aStyle; ?>>
            <tr>
                <th>Username</th>
                <th>Application's Date</th>
                <th>Resume</th>
                <th>University</th>
                <th>Program</th>
                <th>GPA</th>
                <th>Standing</th>
                <th>Days' Number</th>
            </tr>
            <?php
            if ($a) { // Displays a job posting's applications' information
                $jid = $_GET['jid'];
                $query = 'SELECT jid '
                        . 'FROM job_posting '
                        . 'WHERE jid = ' . $jid . ' AND comp_cid = '
                        . $cid . ';';
                if (!mysqli_num_rows(mysqli_query($connection, $query))) {
                    header('Location: ?q=jobs');
                    exit();
                }
                $query = 'SELECT * '
                        . 'FROM application '
                        . 'WHERE jid = ' . $jid . ';';
                $result = mysqli_query($connection, $query);
                foreach ($result as $row) {
                    echo '<tr>'
                    . '<td>' . $row['username'] . '</td>'
                    . '<td>' . $row['applyDate'] . '</td>'
                    . '<td>' . $row['resumee'] . '</td>'
                    . '<td>' . $row['univ'] . '</td>'
                    . '<td>' . $row['program'] . '</td>'
                    . '<td>' . $row['gpa'] . '</td>'
                    . '<td>' . $row['standing'] . '</td>'
                    . '<td>' . $row['numDays'] . '</td>'
                    . '</tr>';
                }
            }
            ?>
            <tr>
                <td style="font-weight: bold; text-align: center" colspan="8">
                    <a href="?d=<?php echo $jid; ?>">Details</a>
                </td>
            </tr>
        </table>
        <div <?php echo $dStyle; ?>>
            <p>Unemployed End Users</p>
            <table>
                <tr>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Military Service Status</th>
                </tr>
                <?php
                // Displays details about a job posting's applicants
                if (empty($dStyle)) {

                    function status(&$qStatus) {
                        $status = &$qStatus;
                        if ($status === 'C') {
                            $status = 'Completed';
                        } elseif ($status === 'D') {
                            $status = 'Delayed';
                        } elseif ($status === 'E') {
                            $status = 'Exempted';
                        }
                    }

                    $jid = $_GET['d'];
                    $query = 'SELECT jid '
                            . 'FROM job_posting '
                            . 'WHERE jid = ' . $jid . ' AND comp_cid = '
                            . $cid . ';';
                    if (!mysqli_num_rows(mysqli_query($connection, $query))) {
                        header('Location: ?q=jobs');
                        exit();
                    }
                    $query = 'SELECT * '
                            . 'FROM end_user NATURAL JOIN application '
                            . 'WHERE jid = ' . $jid . ';';
                    $result = mysqli_query($connection, $query);
                    foreach ($result as $row) {
                        status($row['military_service_stat']);
                        echo '<tr>'
                        . '<td>' . $row['username'] . '</td>'
                        . '<td>' . ucfirst(strtolower($row['fname'])) . '</td>'
                        . '<td>' . ucfirst(strtolower($row['lname'])) . '</td>'
                        . '<td>' . $row['military_service_stat'] . '</td>'
                        . '</tr>';
                    }
                    $query = 'SELECT * '
                            . 'FROM end_user NATURAL JOIN eu_employer '
                            . 'NATURAL JOIN application '
                            . 'WHERE jid = ' . $jid
                            . ' AND beginDate = (SELECT MIN(beginDate) '
                            . 'FROM application NATURAL JOIN eu_employer '
                            . 'WHERE jid = ' . $jid . ');';
                    $row = mysqli_fetch_assoc(mysqli_query($connection, $query));
                    $x = '';
                    if (!mysqli_num_rows(mysqli_query($connection, $query))) {
                        $x = 'style="display: none"';
                    }
                    status($row['military_service_stat']);
                    $query = 'SELECT *, (SELECT COUNT(*) '
                            . 'FROM application a '
                            . 'WHERE a.username = e.username) n '
                            . 'FROM application NATURAL JOIN end_user e '
                            . 'WHERE jid = ' . $jid . ';';
                    $xResult = mysqli_query($connection, $query);
                    $query = 'SELECT * '
                            . 'FROM end_user '
                            . 'WHERE username IN (SELECT username '
                            . 'FROM (SELECT username, SUM(endDate - beginDate) exp '
                            . 'FROM application NATURAL JOIN employment_history '
                            . 'WHERE jid = ' . $jid . ' GROUP BY username) t '
                            . 'WHERE t.exp >= ALL (SELECT SUM(endDate - beginDate) '
                            . 'FROM application NATURAL JOIN employment_history '
                            . 'WHERE jid = ' . $jid . '));';
                    $yResult = mysqli_query($connection, $query);
                    $y = '';
                    if (!mysqli_num_rows($yResult)) {
                        $y = 'style="display: none"';
                    }
                    $xRow = mysqli_fetch_assoc($yResult);
                    status($xRow['military_service_stat']);
                }
                ?>
            </table>
            <p>Longest Working Employee</p>
            <?php
            if (!empty($x)) {
                echo '<p style="font-weight: bold; color: #ffcb9a">'
                . 'No Employee Was Found'
                . '</p>';
            }
            ?>
            <table <?php echo $x; ?>>
                <tr>
                    <th>Username</th>
                    <td><?php echo $row['username']; ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?php echo ucfirst(strtolower($row['fname'])); ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo ucfirst(strtolower($row['lname'])); ?></td>
                </tr>
                <tr>
                    <th>Military Service Status</th>
                    <td><?php echo $row['military_service_stat']; ?></td>
                </tr>
                <tr>
                    <th>Since</th>
                    <td><?php echo $row['beginDate']; ?></td>
                </tr>
            </table>
            <p>End Users' Applications</p>
            <table>
                <tr>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Military Service Status</th>
                    <th>Applications' Number</th>
                </tr>
                <?php
                foreach ($xResult as $row) {
                    status($row['military_service_stat']);
                    echo '<tr>'
                    . '<td>' . $row['username'] . '</td>'
                    . '<td>' . ucfirst(strtolower($row['fname'])) . '</td>'
                    . '<td>' . ucfirst(strtolower($row['lname'])) . '</td>'
                    . '<td>' . $row['military_service_stat'] . '</td>'
                    . '<td>' . $row['n'] . '</td>'
                    . '</tr>';
                }
                ?>
            </table>
            <p>Most Experienced End User</p>
            <?php
            if (!empty($y)) {
                echo '<p style="font-weight: bold; color: #ffcb9a">'
                . 'No End User Had Experience'
                . '</p>';
            }
            ?>
            <table <?php echo $y; ?>>
                <tr>
                    <th>Username</th>
                    <td><?php echo $xRow['username']; ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?php echo ucfirst(strtolower($xRow['fname'])); ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo ucfirst(strtolower($xRow['lname'])); ?></td>
                </tr>
                <tr>
                    <th>Military Service Status</th>
                    <td><?php echo $xRow['military_service_stat']; ?></td>
                </tr>
            </table>
            <br>
        </div>
    </body>
</html>

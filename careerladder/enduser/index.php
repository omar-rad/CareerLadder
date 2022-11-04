<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Career Ladder - End User</title>
        <link rel="stylesheet" href="../styles.css"/>
        <style>
            table {
                font-size: 15px;
            }
        </style>
    </head>
    <body>
        <?php
        include '../../includes/header.php';
        ?>
        <form method="post">
            <input type="submit" name="button1" value="View Job Postings">
            <input type="submit" name="button2" value="Open Job Postings">
            <input type="submit" name="button3" value="Highest Salary Company">
            <input type="submit" name="button4" value="Search internships Jobs By company Name">
            <input type="submit" name="button5" value="Search Most Paid Manager Job By Dept Size">
            <input type="submit" name="button6" value="Search By Contract Type And City">
        </form>
        <?php
        // Prevents information access when login is required
        if (empty($_SESSION) or $_SESSION['redirect'] != 'enduser') {
            header('Location: ../login.php');
            exit();
        }
        if ($_SERVER['QUERY_STRING'] === 'logout') { // Logout
            session_destroy();
            header('Location: ..');
            exit();
        }
        include '../../includes/db.php';
        if (array_key_exists('button1', $_POST)) {
            button1();
        } else if (array_key_exists('button2', $_POST)) {
            button2();
        } else if (array_key_exists('button3', $_POST)) {
            button3();
        } else if (array_key_exists('button4', $_POST)) {
            button4();
        } else if (array_key_exists('button5', $_POST)) {
            button5();
        } else if (array_key_exists('button6', $_POST)) {
            button6();
        }

        if (isset($_POST['cn'])) {
            $cname = $_POST['cname'] ?? "";
            $query4 = "select * "
                    . "from (job_posting J natural join internshipJobPosting J1) join company C on J.comp_cid = C.cid "
                    . "where C.name = '$cname'"
                    . "and J1.minnumdays>=20;";
            $result4 = mysqli_query($connection, $query4);
            echo'<table><tr><th> Internships Job POSTING </th></tr>';
            while ($row = mysqli_fetch_assoc($result4)) {
                echo "<tr><td>" . "<b>description:</b>" . $row['description'] . " <b>salary:</b> " . $row['salary'] . " <b>Phone:</b> " . $row['phone'] .
                " <b>Number of opening:</b> " . $row['numOpenings'] . "  <b>hrr_username:</b> " . $row['hrr_username'] . " <b>opening date:</b> " . $row['openingdate'] . " <b>duration:</b> "
                . $row['duration'] . " <b>companyID:</b> " . $row['comp_cid'] . " <b>contract type:</b> " . $row['contract_type'] . "<b> Minimum Days:</b>" . $row['minnumDays']
                . "<br></tr>";
            }
            echo '</table>';
            if ($result4) {
                echo'<br> ';
            } else {
                echo 'Error ' . $query4 . "<br>" . mysqli_errno($connection);
            }
        }

        if (isset($_POST['cn1'])) {

            $dsize = $_POST['dsize'] ?? "";
            $query10 = "select jid, description, salary, c.name "
                    . "FROM job_posting, company c "
                    . "WHERE salary = ( select max(j.salary)"
                    . "FROM job_posting j, manager_job_posting m where j.is_manOrIntern=1 "
                    . "AND j.jid=m.jid and m.deptSize<'$dsize')"
                    . "AND comp_cid=c.cid;";
            $result10 = mysqli_query($connection, $query10);
            echo'<table><tr><th> Internships Job POSTING </th></tr>';
            while ($row = mysqli_fetch_assoc($result10)) {

                echo "<tr><td>" . "<b>description:</b>" . $row['description'] . " <b>salary:</b> " . $row['salary'] .
                " <b>JID:</b> " . $row['jid'] . "  <b>Company Name:</b> " . $row['name']
                . "<br></tr>";
            }
            echo '</table>';
            if ($result10) {
                echo'<br> ';
            } else {
                echo 'Error ' . $query10 . "<br>" . mysqli_errno($connection);
            }
        }
        if (isset($_POST['cn2'])) {

            $ctype = $_POST['ctype'] ?? "";
            $city = $_POST['city'] ?? "";
            $query11 = "select J.description, J.salary, C.name "
                    . "from job_posting J, company C "
                    . "where J.contract_type = '$ctype' AND J.comp_cid = C.cid "
                    . "and C.address LIKE '%$city%'";
            $result11 = mysqli_query($connection, $query11);

            echo'<table><tr><th> Internships Job POSTING </th></tr>';
            while ($row = mysqli_fetch_assoc($result11)) {
                echo "<tr><td>" . "<b>description:</b>" . $row['description'] . " <b>salary:</b> " . $row['salary'] . "<b> Company Name:</b> " . $row['name']
                . "<br></tr>";
            }
            echo '</table>';
            if ($result11) {
                echo'<br>';
            } else {
                echo 'Error ' . $query11 . "<br>" . mysqli_errno($connection);
            }
        }

        function button1() {
            global $connection;
            $query = "SELECT * FROM job_posting j WHERE j.jid not in (select jid FROM internshipJobPosting);";
            $result = mysqli_query($connection, $query);
            $query1 = "SELECT * FROM job_posting j, internshipJobPosting i WHERE j.jid = i.jid;";
            $result1 = mysqli_query($connection, $query1);

            echo'<table><tr><th> JOB POSTING </th></tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . "<b>description:</b>" . $row['description'] . " <b>salary:</b> " . $row['salary'] . " <b>Phone:</b> " . $row['phone'] .
                " <b>Number of opening:</b> " . $row['numOpenings'] . "  <b>hrr_username:</b> " . $row['hrr_username'] . " <b>opening date:</b> " . $row['openingdate'] . " <b>duration:</b> "
                . $row['duration'] . " <b>companyID:</b> " . $row['comp_cid'] . " <b>contract type:</b> " . $row['contract_type']
                . "<br></tr>";
            }
            echo '<br><tr><th> INTERN JOB POSTING </th></tr><br>';
            while ($row = mysqli_fetch_assoc($result1)) {
                echo "<tr><td>" . "<b>description:</b>" . $row['description'] . " <b>salary:</b> " . $row['salary'] . " <b>Phone:</b> " . $row['phone'] .
                " <b>Number of opening:</b> " . $row['numOpenings'] . "  <b>hrr_username:</b> " . $row['hrr_username'] . " <b>opening date:</b> " . $row['openingdate'] .
                " <b>duration:</b> " . $row['duration'] . " <b>companyID:</b> " . $row['comp_cid'] . " <b>contract type:</b> " . $row['contract_type'] . "<br></tr>";
            }
        }

        function button2() {
            global $connection;
            $query6 = "SELECT * FROM job_posting j WHERE j.jid not in (select jid FROM internshipJobPosting);";
            $result6 = mysqli_query($connection, $query6);
            $query7 = "SELECT * FROM job_posting j, internshipJobPosting i WHERE j.jid = i.jid;";
            $result7 = mysqli_query($connection, $query7);

            echo '<table><tr><th> JOB POSTING </th></tr>';
            while ($row = mysqli_fetch_assoc($result6)) {
                echo "<tr><td>" . "<b>description:</b>" . $row['description'] . " <b>salary:</b> " . $row['salary'] . " <b>Phone:</b> " . $row['phone'] .
                " <b>Number of opening:</b> " . $row['numOpenings'] . "  <b>hrr_username:</b> " . $row['hrr_username'] . " <b>opening date:</b> " . $row['openingdate'] . " <b>duration:</b> "
                . $row['duration'] . " <b>companyID:</b> " . $row['comp_cid'] . " <b>contract type:</b> " . $row['contract_type']
                . "<br></tr>";
            }

            echo '<br><tr><th> INTERN JOB POSTING </th></tr><br>';
            while ($row = mysqli_fetch_assoc($result7)) {
                echo "<tr><td>" . "<b>description:</b>" . $row['description'] . " <b>salary:</b> " . $row['salary'] . " <b>Phone:</b> " . $row['phone'] .
                " <b>Number of opening:</b> " . $row['numOpenings'] . "  <b>hrr_username:</b> " . $row['hrr_username'] . " <b>opening date:</b> " . $row['openingdate'] .
                " <b>duration:</b> " . $row['duration'] . " <b>companyID:</b> " . $row['comp_cid'] . " <b>contract type:</b> " . $row['contract_type'] . "<br></tr>";
            }
        }

        function button3() {
            global $connection;
            $query3 = "select * from company where cid IN (select j.comp_cid from job_posting as j where j.salary =(select max(salary) from job_posting));";
            $result3 = mysqli_query($connection, $query3);

            echo' <table><tr><th> Company </th></tr>';
            while ($row = mysqli_fetch_assoc($result3)) {
                echo "<tr><td>" . "<b>Company ID: </b>" . $row['cid'] . " <b> Name: </b> " . $row['name'] . " <b> Phone: </b> " . $row['phone'] .
                " <b> address: </b> " . $row['address']
                . "<br></tr>";
            }
        }

        function button4() {
            echo'<center>  
            <p>SEARCH</p>
             <form action"enduser.php" method="POST">
                                <label for="cname">Company Name:</label>
                                <input type="text" id="cname" name="cname"><br><br>
                            <input type="submit" name="cn" value="Search">
                        </form>
        </center>';
        }

        function button5() {
            echo'<center>  
            <p>SEARCH</p>
             <form action"enduser.php" method="POST">
                                <label for="dsize">Department Size:</label>
                                <input type="text" id="dsize" name="dsize"><br><br>
                            <input type="submit" name="cn1" value="Search">
                        </form>
        </center>';
        }

        function button6() {
            echo'<center>  
            <p>SEARCH</p>
             <form action"enduser.php" method="POST">
                                <label for="ctype">Contract Type:</label>
                                <input type="text" id="ctype" name="ctype"><br><br>
                                <label for="city">City:</label>
                                <input type="text" id="city" name="city"><br><br>
                            <input type="submit" name="cn2" value="Search">
                        </form>
        </center>';
        }
        ?>
        <br>
        <a class="button" href="?logout">
            Log Out
        </a>
        <br style="margin-bottom: 50px;">
    </body>
</html>

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * To be included when the database connection is required. 
 */

// Converts strings to sql form
function querify(&$array) {
    foreach ($array as &$value) {
        if (empty($value)) {
            $value = 'NULL';
        } else {
            $value = '\'' . $value . '\'';
        }
    }
}

// Used for insertion and redirection if registration is successful
function insert($query) {
    global $connection;
    if (mysqli_query($connection, $query)) {
        header('Location: ..');
        exit();
    }
}

// The database connection
$connection = mysqli_connect('localhost', 'root', null, 'project');
if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

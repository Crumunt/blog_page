<?php

require 'connection.php';

$files = glob('../assets/blog-assets/*');

$data = showRecords($conn, 'tbl_blogs');

$imageInUse = [];

foreach($data as $image) {
    array_push($imageInUse, $image[2]);
}

foreach($files as $file) {
    $file_name = end(explode('/',$file));

    if(!in_array($file_name, $imageInUse)) {
        unlink($file);
    }
}

// THIS FILE IS FOR ALL RELATED FUNCTIONS THAT CAN BE USED IN THE ENTIRE PROJECT

function sqlExecute($conn, $sql)
{
    $result = mysqli_query($conn, $sql);
    $data = array();

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            // array_push($data, [$row['id'], $row['fname'], $row['mname'], $row['lname'], $row['email'], $row['position']]);
            $data_row = array();
            foreach ($row as $r) {
                array_push($data_row, $r);
            }
            array_push($data, $data_row);
        }
    }

    return $data;
}

function showRecords($conn, $tbl, $where = null)
{
    $sql = "SELECT * FROM $tbl";
    if ($where != null) {
        $sql .= " WHERE $where";
    }

    return sqlExecute($conn, $sql);
}

function addQuery($conn, $data, $tbl)
{

    // get array key in data array
    $tbl_columns = implode(",", array_keys($data));

    // get array value in data array
    $tbl_values = implode("','", $data);

    $sql = "INSERT INTO $tbl($tbl_columns) VALUES ('$tbl_values');";
    return mysqli_query($conn, $sql);
}

function updateQuery($conn, $data, $tbl, $id)
{

    $update = "";
    foreach ($data as $key => $value) {

        $update .= " $key='$value' ,";
    }
    // removes last comma in update statement
    $update = substr($update, 0, -1);
    $primary_key = array_keys($id)[0];
    $key_value = $id[$primary_key];
    $sql = "UPDATE $tbl SET {$update} WHERE $primary_key = $key_value";
    return mysqli_query($conn, $sql);
}

function deleteQuery($conn, $tbl, $id)
{

    $primary_key = array_keys($id)[0];
    $key_value = $id[$primary_key];
    $sql = "DELETE FROM $tbl WHERE $primary_key = $key_value";

    return mysqli_query($conn, $sql);
}

function retrieveCount($conn, $data = NULL, $tbl, $where = NULL, $group) {

    $sql = "SELECT COUNT(*)";

    if($data != NULL) {
        foreach($data as $param) {
            $sql .= ", $param";
        }
    }

    $sql .= " FROM $tbl";

    if($where != NULL) {
        $sql .= " WHERE $where";
    }

    $sql .= " GROUP BY $group";

    // echo "$sql";

    return sqlExecute($conn, $sql);
   
}
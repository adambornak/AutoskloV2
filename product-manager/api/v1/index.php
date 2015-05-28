<?php
require '.././libs/Slim/Slim.php';
require_once 'dbHelper.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app = \Slim\Slim::getInstance();
$db = new dbHelper();

/**
 * Database Helper Function templates
 */
/*
select(table name, where clause as associative array)
insert(table name, data as associative array, mandatory column names as array)
update(table name, column names as associative array, where clause as associative array, required columns as array)
delete(table name, where clause as array)
*/


// Products
$app->get('/cars', function() { 
    global $db;
    $rows = $db->select("cars","carID,carType,carSPZ,carInsurance,carPrice,carDate,carDescription,carStatus",array());
    echoResponse(200, $rows);
});

$app->post('/cars', function() use ($app) { 
    $data = json_decode($app->request->getBody());
    $mandatory = array('carType');
    global $db;
    $rows = $db->insert("cars", $data, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Product added successfully.";
    echoResponse(200, $rows);
});

$app->put('/cars/:carID', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('carID'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("cars", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Product information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/cars/:carID', function($id) { 
    global $db;
    $rows = $db->delete("cars", array('carID'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Product removed successfully.";
    echoResponse(200, $rows);
});

function echoResponse($status_code, $response) {
    global $app;
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response,JSON_NUMERIC_CHECK);
}

$app->run();
?>
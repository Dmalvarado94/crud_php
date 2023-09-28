<?php

require 'flight/Flight.php';
// Comando para conectarse a PhpMyAdmin dbname= nombre api creada, 'root' usuario, no tenemos pass.
Flight::register('db','PDO',array('mysql:host=localhost;dbname=api','root',''));


// Conf para obtener la api alumnos y transformarla en JSON
Flight::route('GET /alumnos', function () {

    $sentencia= Flight::db()->prepare("SELECT * FROM `alumnos`");
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    Flight::json($datos);
});

// Conf para insertar nuevos alumnos
Flight::route('POST /alumnos', function () {
    $nombres=(Flight::request()->data->nombres);
    $apellidos=(Flight::request()->data->apellidos);

    $sql="INSERT INTO alumnos (nombres,apellidos) VALUES(?,?)";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->execute();

    Flight::jsonp(["Alumno agregado"]);
    // Recuerda siempre ir imprimiento el dato que estas trabajando
    //print_r($nombres);
});

// Conf para borrar alumnos
Flight::route('DELETE /alumnos', function () {
    $id=(Flight::request()->data->id);
    $sql="DELETE FROM alumnos WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$id);
    $sentencia->execute();

    Flight::jsonp(["Alumno eliminado"]);
});

// Conf para Editar alumnos
Flight::route('PUT /alumnos', function () {
    $id=(Flight::request()->data->id);
    $nombres=(Flight::request()->data->nombres);
    $apellidos=(Flight::request()->data->apellidos);

    $sql="UPDATE alumnos SET nombres=? ,apellidos=? WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->bindParam(3,$id);
    $sentencia->execute();

    Flight::jsonp(["Alumno modificado"]);
    // Aqui los print eran por separado
    // print_r($id);
    // print_r($nombres);
    // print_r($apellidos);

});

// CONF para consultar un registro en especifico por id
// Ej: GET : http://localhost/api/alumnos/4
Flight::route('GET /alumnos/@id', function ($id) {
 
$sentencia= Flight::db()->prepare("SELECT nombres,apellidos FROM `alumnos` WHERE id=?");
$sentencia->bindParam(1,$id);
$sentencia->execute();
$datos=$sentencia->fetchAll();
Flight::json($datos);

});

Flight::start();

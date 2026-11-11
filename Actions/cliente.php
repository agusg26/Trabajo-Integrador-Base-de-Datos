<?php

require_once "../config/conexion.php";

try {


    $accion = $_POST['accion'];
    $id = $_POST['customerid'];

    $nombre = $_POST['companyname'] ?? null;
    $contacto = $_POST['contactname'] ?? null;
    $ciudad = $_POST['city'] ?? null;
    $pais = $_POST['country'] ?? null;

    $sql = "SELECT fn_abm_cliente(
                :accion,
                :id,
                :nombre,
                :contacto,
                :ciudad,
                :pais
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':accion' => $accion,
        ':id' => $id,
        ':nombre' => $nombre,
        ':contacto' => $contacto,
        ':ciudad' => $ciudad,
        ':pais' => $pais
    ]);

    $mensaje = $stmt->fetchColumn();

    header("Location: ../index.php?mensaje=" . urlencode($mensaje));
    exit;
} catch (PDOException $e) {

    $mensaje = $e->getMessage();

    header("Location: ../index.php?error=" . urlencode($mensaje));
    exit;
}

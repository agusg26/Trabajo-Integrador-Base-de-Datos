<?php

require_once "../Config/conexion.php";

try {
    $accion = $_POST['accion'] ?? null;
    $id = $_POST['customerid'] ?? null;
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

    header("Location: ../index.php?mensaje=" . urlencode($mensaje) . "#abm-clientes");
    exit;
} catch (PDOException $e) {
    $errorSql = $e->getMessage();

    if (preg_match('/ERROR:\s*(.+?)(?:\r?\n|$)/', $errorSql, $matches)) {
        $errorSql = trim($matches[1]);
    }

    header("Location: ../index.php?error=" . urlencode($errorSql) . "#abm-clientes");
    exit;
}

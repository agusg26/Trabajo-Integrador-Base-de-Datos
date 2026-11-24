<?php

require_once "Config/conexion.php";

// Recuperar clientes
$sql = "SELECT
            customerid,
            companyname,
            contactname,
            city,
            country
        FROM customers
        ORDER BY companyname";

$stmt = $pdo->query($sql);

// Obtener todos los registros
$clientes = $stmt->fetchAll();
// Consultar órdenes
$filtroCliente = $_GET['cliente'] ?? '';
$filtroEmpleado = $_GET['empleado'] ?? '';


$sqlOrdenes = "SELECT *
               FROM vw_ordenes
               WHERE companyname ILIKE '%' || :cliente || '%'
               AND employname ILIKE '%' || :empleado || '%'
               ORDER BY orderdate";

$stmtOrdenes = $pdo->prepare($sqlOrdenes);

$stmtOrdenes->execute([
    ':cliente' => $filtroCliente,
    ':empleado' => $filtroEmpleado
]);

$ordenes = $stmtOrdenes->fetchAll();

$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container my-4">

        <h1 class="mb-4">Gestión de clientes</h1>

        <h2 id="abm-clientes" class="h4 mb-3">ABM de clientes</h2>

        <?php if ($mensaje !== ''): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="Actions/cliente.php" method="POST" novalidate class="card p-3 mb-4">

            <div class="mb-3">
                <label class="form-label">Código:</label>
                <input type="text" name="customerid" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Empresa:</label>
                <input type="text" name="companyname" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Contacto:</label>
                <input type="text" name="contactname" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Ciudad:</label>
                <input type="text" name="city" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">País:</label>
                <input type="text" name="country" class="form-control">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="accion" value="A" class="btn btn-success">
                    Registrar
                </button>

                <button type="submit" name="accion" value="M" class="btn btn-warning">
                    Modificar
                </button>

                <button type="submit" name="accion" value="B" class="btn btn-danger">
                    Eliminar
                </button>
            </div>

        </form>

        <h2 class="h4 mb-3">Listado de clientes</h2>

        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped table-sm bg-white">

                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Empresa</th>
                        <th>Contacto</th>
                        <th>Ciudad</th>
                        <th>País</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($clientes as $registro): ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars($registro['customerid']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($registro['companyname']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($registro['contactname'] ?? '') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($registro['city'] ?? '') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($registro['country'] ?? '') ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>
        </div>

        <hr class="my-4">

        <h2 class="h4 mb-3">Consulta de órdenes</h2>

        <form action="index.php" method="GET" class="row g-2 align-items-end mb-3">

            <div class="col-md-4">
                <label class="form-label">Cliente:</label>
                <input
                    type="text"
                    name="cliente"
                    class="form-control"
                    value="<?= htmlspecialchars($filtroCliente) ?>"
                    placeholder="Buscar por cliente">
            </div>

            <div class="col-md-4">
                <label class="form-label">Empleado:</label>
                <input
                    type="text"
                    name="empleado"
                    class="form-control"
                    value="<?= htmlspecialchars($filtroEmpleado) ?>"
                    placeholder="Buscar por empleado">
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="index.php" class="btn btn-secondary">Limpiar filtros</a>
            </div>

        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Código cliente</th>
                        <th>Empresa</th>
                        <th>Código empleado</th>
                        <th>Empleado</th>
                        <th>Importe</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (count($ordenes) > 0): ?>

                        <?php foreach ($ordenes as $orden): ?>
                            <tr>
                                <td><?= htmlspecialchars($orden['orderdate']) ?></td>
                                <td><?= htmlspecialchars($orden['customerid']) ?></td>
                                <td><?= htmlspecialchars($orden['companyname']) ?></td>
                                <td><?= htmlspecialchars($orden['employeeid']) ?></td>
                                <td><?= htmlspecialchars($orden['employname']) ?></td>
                                <td>
                                    <?= number_format((float)$orden['orderamount'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center">No se encontraron órdenes.</td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>
<?php

require_once "config/conexion.php";

$cliente = $_GET['cliente'] ?? '';
$empleado = $_GET['empleado'] ?? '';

$sql = "SELECT *
        FROM vw_ordenes
        WHERE companyname ILIKE '%' || :cliente || '%'
        AND employname ILIKE '%' || :empleado || '%'
        ORDER BY orderdate";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':cliente' => $cliente,
    ':empleado' => $empleado
]);

$ordenes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de órdenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container my-4">

        <h1 class="mb-4">Consulta de órdenes</h1>

        <form action="ordenes.php" method="GET" class="row g-2 align-items-end mb-4">

            <div class="col-md-4">
                <label class="form-label">Cliente:</label>
                <input
                    type="text"
                    name="cliente"
                    class="form-control"
                    value="<?= htmlspecialchars($cliente) ?>"
                    placeholder="Buscar cliente...">
            </div>

            <div class="col-md-4">
                <label class="form-label">Empleado:</label>
                <input
                    type="text"
                    name="empleado"
                    class="form-control"
                    value="<?= htmlspecialchars($empleado) ?>"
                    placeholder="Buscar empleado...">
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>

                <a href="ordenes.php" class="btn btn-secondary">
                    Limpiar filtros
                </a>
            </div>

        </form>

        <h2 class="h4 mb-3">Resultados</h2>

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

                    <?php foreach ($ordenes as $orden): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($orden['orderdate']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($orden['customerid']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($orden['companyname']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($orden['employeeid']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($orden['employname']) ?>
                            </td>

                            <td>
                                <?= number_format((float)$orden['orderamount'], 2, ',', '.') ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    <?php if (empty($ordenes)): ?>

                        <tr>
                            <td colspan="6" class="text-center">
                                No se encontraron órdenes.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>
        </div>

    </div>

</body>

</html>
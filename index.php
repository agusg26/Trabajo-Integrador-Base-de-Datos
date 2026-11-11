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

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>ABM de Clientes</title>
</head>

<body>

    <h1>Gestión de clientes</h1>

    <h2>Listado de clientes</h2>

    <table border="1" cellpadding="8">

        <thead>
            <tr>
                <th>Código</th>
                <th>Empresa</th>
                <th>Contacto</th>
                <th>Ciudad</th>
                <th>País</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($clientes as $cliente): ?>

                <tr>
                    <td>
                        <?= htmlspecialchars($cliente['customerid']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($cliente['companyname']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($cliente['contactname'] ?? '') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($cliente['city'] ?? '') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($cliente['country'] ?? '') ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
    <h2>ABM de clientes</h2>

    <form action="Actions/cliente.php" method="POST">

        <label>Código:</label>
        <input type="text" name="customerid" maxlength="5" required>

        <br><br>

        <label>Empresa:</label>
        <input type="text" name="companyname">

        <br><br>

        <label>Contacto:</label>
        <input type="text" name="contactname">

        <br><br>

        <label>Ciudad:</label>
        <input type="text" name="city">

        <br><br>

        <label>País:</label>
        <input type="text" name="country">

        <br><br>

        <button type="submit" name="accion" value="A">
            Registrar
        </button>

        <button type="submit" name="accion" value="M">
            Modificar
        </button>

        <button type="submit" name="accion" value="B">
            Eliminar
        </button>

    </form>

</body>

</html>
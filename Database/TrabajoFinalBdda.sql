CREATE OR REPLACE FUNCTION fn_abm_cliente(
    p_accion TEXT,
    p_id TEXT,
    p_nombre TEXT,
    p_contacto TEXT,
    p_ciudad TEXT,
    p_pais TEXT
)
RETURNS TEXT AS $$
DECLARE
    v_id TEXT;
BEGIN

    IF p_accion IS NULL OR p_accion NOT IN ('A', 'M', 'B') THEN
        RAISE EXCEPTION 'Acción inválida: %', COALESCE(p_accion, 'NULL');
    END IF;

    IF p_id IS NULL OR TRIM(p_id) = '' THEN
        RAISE EXCEPTION 'El código del cliente es obligatorio';
    END IF;

    v_id := UPPER(TRIM(p_id));

    IF LENGTH(v_id) <> 5 OR v_id ~ '\s' THEN
        RAISE EXCEPTION 'El código del cliente debe tener 5 caracteres sin espacios';
    END IF;

    IF p_accion = 'A' THEN

        IF p_nombre IS NULL OR TRIM(p_nombre) = '' THEN
            RAISE EXCEPTION 'El nombre de la empresa es obligatorio';
        END IF;

        IF EXISTS (SELECT 1 FROM customers WHERE customerid = v_id) THEN
            RAISE EXCEPTION 'Ya existe un cliente con código %', v_id;
        END IF;

        INSERT INTO customers (
            customerid,
            companyname,
            contactname,
            city,
            country
        )
        VALUES (
            v_id,
            NULLIF(TRIM(p_nombre), ''),
            NULLIF(TRIM(p_contacto), ''),
            NULLIF(TRIM(p_ciudad), ''),
            NULLIF(TRIM(p_pais), '')
        );

        RETURN 'Cliente registrado correctamente';

    END IF;

    IF p_accion = 'M' THEN

        IF NOT EXISTS (SELECT 1 FROM customers WHERE customerid = v_id) THEN
            RAISE EXCEPTION 'No existe un cliente con código %', v_id;
        END IF;

        IF p_nombre IS NULL OR TRIM(p_nombre) = '' THEN
            RAISE EXCEPTION 'El nombre de la empresa es obligatorio';
        END IF;

        UPDATE customers
        SET
            companyname = NULLIF(TRIM(p_nombre), ''),
            contactname = NULLIF(TRIM(p_contacto), ''),
            city = NULLIF(TRIM(p_ciudad), ''),
            country = NULLIF(TRIM(p_pais), '')
        WHERE customerid = v_id;

        RETURN 'Cliente modificado correctamente';

    END IF;

    IF p_accion = 'B' THEN

        DELETE FROM customers
        WHERE customerid = v_id;

        IF NOT FOUND THEN
            RAISE EXCEPTION 'No existe un cliente con código %', v_id;
        END IF;

        RETURN 'Cliente eliminado correctamente';

    END IF;

EXCEPTION
    WHEN unique_violation THEN
        RAISE EXCEPTION 'Ya existe un cliente con código %', v_id;
    WHEN foreign_key_violation THEN
        RAISE EXCEPTION 'No se puede eliminar el cliente % porque tiene órdenes asociadas', v_id;
END;
$$ LANGUAGE plpgsql;


SELECT fn_abm_cliente(
    'A',
    'EASTA',
    'Empresa de prueba',
    'Juan Pérez',
    'Miami',
    'USA'
);

SELECT fn_abm_cliente(
    'M',
    'EASTA',
    'Empresa modificada',
    'Pedro González',
    'Miami',
    'USA'
);
SELECT fn_abm_cliente(
    'B',
    'EASTA',
    NULL,
    NULL,
    NULL,
    NULL
);



CREATE OR REPLACE VIEW vw_ordenes AS
	select o.orderdate,
    c.customerid,
    c.companyname,
    o.employeeid,
    e.firstname || ' ' || e.lastname AS employname,
    SUM(
        od.unitprice * od.quantity * (1 - od.discount)
    ) AS orderamount
    from orders o
    INNER JOIN customers c
    ON o.customerid = c.customerid
    INNER JOIN employees e
    ON o.employeeid = e.employeeid
    INNER JOIN orderdetails od
    ON o.orderid = od.orderid
    GROUP BY
    o.orderid,
    o.orderdate,
    c.customerid,
    c.companyname,
    o.employeeid,
    e.firstname,
    e.lastname;


	SELECT *
FROM vw_ordenes
ORDER BY orderdate;
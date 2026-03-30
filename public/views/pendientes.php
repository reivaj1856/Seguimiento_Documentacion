<?php
$sql = "SELECT hr.*, d.* FROM hoja_ruta hr
    JOIN derivaciones d ON hr.idhoja_ruta = d.hoja_ruta_idhoja_ruta
    WHERE d.destinatario=? AND d.ingreso = (
            SELECT MAX(d2.ingreso)
            FROM derivaciones d2
            WHERE d2.hoja_ruta_idhoja_ruta = hr.idhoja_ruta
          )";
$stmt = $conn->prepare($sql);
$stmt->execute([$userid]);
$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
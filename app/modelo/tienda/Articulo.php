<?php

class Articulo {
    private static $instancia;

    public static function singleton_articulo() {
        if (!self::$instancia) {
            self::$instancia = new Articulo();
        }
        return self::$instancia;
    }

    public function listarArticulosControl() {
        // Aquí deberías hacer una consulta a la base de datos para obtener los artículos
        // Por ejemplo:
        // return $this->db->query('SELECT * FROM articulos');
        // Este es solo un ejemplo:
        return [
            ['id_articulo' => 1, 'nombre' => 'Gaseosa', 'precio' => 1.5, 'stock' => 100],
            ['id_articulo' => 2, 'nombre' => 'Crispetas', 'precio' => 2.0, 'stock' => 50],
        ];
    }
}

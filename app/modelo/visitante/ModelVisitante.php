 <?php

class ModelVisitante {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function mostrarMeses() {
        $query = 'SELECT id, nombre FROM meses ORDER BY nombre';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrarCursos() {
        $query = 'SELECT id, nombre FROM cursos ORDER BY nombre';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrarEstudiantes() {
        $query = 'SELECT id, nombre FROM estudiantes ORDER BY nombre';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrarRegistros($curso = null) {
        $query = 'SELECT * FROM registros where fecha_ingreso = CURDATE()';
        if ($curso) {
            $query .= ' WHERE curso_id = :curso_id';
        }
        $query .= ' ORDER BY fecha_ingreso DESC';
        $stmt = $this->pdo->prepare($query);
        if ($curso) {
            $stmt->bindParam(':curso_id', $curso);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarRegistro($data) {
        $query = 'INSERT INTO registros (nombre_visitante, nombre_acudiente, celular, fecha_ingreso, hora_ingreso, duracion, hora_salida, curso_id) VALUES (:nombre_visitante, :nombre_acudiente, :celular, :fecha_ingreso, :hora_ingreso, :duracion, :hora_salida, :curso_id)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nombre_visitante', $data['nombre_visitante']);
        $stmt->bindParam(':nombre_acudiente', $data['nombre_acudiente']);
        $stmt->bindParam(':celular', $data['celular']);
        $stmt->bindParam(':fecha_ingreso', $data['fecha_ingreso']);
        $stmt->bindParam(':hora_ingreso', $data['hora_ingreso']);
        $stmt->bindParam(':duracion', $data['duracion']);
        $stmt->bindParam(':hora_salida', $data['hora_salida']);
        $stmt->bindParam(':curso_id', $data['curso_id']);
        return $stmt->execute();
    }
}

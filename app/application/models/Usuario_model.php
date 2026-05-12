<?php declare(strict_types=1);

/**
 * Modelo de `ci_db.usuarios`
 * 
 * @property CI_DB_mysqli_driver $db
 */
class Usuario_model extends CI_Model
{
  private const TABLE = 'usuarios';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Consulta SQL para obtener un usuario por ID
   * @param int $id
   * @return Usuario|null
   */
  public function findById($id): ?Usuario
  {
    $row = $this->db->where('id', $id)
      ->where('estaActivo', true)
      ->get(self::TABLE)
      ->row_array();

    return $row ? new Usuario($row) : null;
  }

  /**
   * Consulta SQL para obtener listado de usuarios
   * @return Usuario[]
   */
  public function findAll(): array
  {
    $this->db->where('estaActivo', true);

    $rows = $this->db->get(self::TABLE)->result_array();

    return array_map(fn(array $row): Usuario => new Usuario($row), $rows);
  }

  public function findByNombre(string $nombre): ?Usuario
  {
    $row = $this->db->where('nombre', $nombre)
      ->get(self::TABLE)
      ->row_array();

    return $row ? new Usuario($row) : null;
  }

  /**
   * Consulta para insertar un usuario
   * @param Usuario $usuario
   * @return int
   */
  public function create(Usuario $usuario): int
  {
    $this->db->insert(self::TABLE, [
      'nombre' => $usuario->nombre,
      'contrasena' => $usuario->getContrasena(),
      'idRol' => $usuario->idRol,
      'estaActivo' => true,
      'creadoPor' => $usuario->creadoPor,
      'modificadoPor' => $usuario->modificadoPor,
      'fechaCreacion' => date('Y-m-d H:i:s'),
      'fechaModificado' => date('Y-m-d H:i:s'),
    ]);

    return (int) $this->db->insert_id();
  }

  /**
   * Consulta para actualizar un usuario
   * @param Usuario $usuario
   * @return bool
   */
  public function update(Usuario $usuario): bool
  {
    $this->db->where('id', $usuario->id);

    return (bool) $this->db->update(self::TABLE, [
      'nombre' => $usuario->nombre,
      'contrasena' => $usuario->getContrasena(),
      'idRol' => $usuario->idRol,
      'modificadoPor' => $usuario->modificadoPor,
      'fechaModificado' => date('Y-m-d H:i:s'),
    ]);
  }

  /**
   * Consulta para eliminar lógicamente un usuario
   * @param int $id
   * @param int $modificadoPor
   * @return bool
   */
  public function softDelete(int $id, int $modificadoPor): bool
  {
    $this->db->where('id', $id);

    return (bool) $this->db->update(self::TABLE, [
      'estaActivo' => false,
      'modificadoPor' => $modificadoPor,
      'fechaModificado' => date('Y-m-d H:i:s'),
    ]);
  }
}

<?php declare(strict_types=1);

class Usuario extends BaseEntity
{
  public string $nombre;
  private string $contrasena;
  public int $idRol;

  public function __construct(array $SQL)
  {
    parent::__construct($SQL);

    $this->nombre = $SQL['nombre'] ?? '';
    $this->contrasena = $SQL['contrasena'] ?? '';
    $this->idRol = $SQL['idRol'] ?? 0;
  }

  public function getContrasena(): string
  {
    return $this->contrasena;
  }

  /**
   * Establecer nueva contraseña encriptándola
   * @param string $entrada
   * @return void
   */
  public function setContrasena(string $entrada)
  {
    $this->contrasena = password_hash($entrada, PASSWORD_BCRYPT);
  }

  /**
   * Evaluar un texto con la contraseña
   * @param string $entrada
   * @return bool
   */
  public function evaluarContrasena($entrada): bool
  {
    return password_verify($entrada, $this->contrasena);
  }

  public function toArray(): array
  {
    return array_merge(parent::toArray(), [
      'nombre' => $this->nombre,
      'idRol' => $this->idRol,
    ]);
  }
}

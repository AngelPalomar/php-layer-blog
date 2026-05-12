<?php declare(strict_types=1);

interface IUsuarioService
{
  public function obtenerPorId(int $id): ?Usuario;

  /**
   * @return Usuario[]
   */
  public function obtenerTodos(): array;

  public function crear(array $datos, int $creadoPor): Usuario;

  public function actualizar(int $id, array $datos, int $modificadoPor): Usuario;

  public function eliminar(int $id, int $modificadoPor): bool;

  public function login(string $nombre, string $contrasena): Usuario;
}
<?php declare(strict_types=1);

interface IUsuarioRepository
{
  public function findById(int $id): ?Usuario;

  /** @return Usuario[] */
  public function findAll(): array;

  public function findByNombre(string $nombre): ?Usuario;

  public function create(Usuario $usuario): Usuario;

  public function update(Usuario $usuario): Usuario;

  public function softDelete(int $id, int $modificadoPor): bool;
}
<?php declare(strict_types=1);

class UsuarioRepository implements IUsuarioRepository
{
  private Usuario_model $model;

  public function __construct(Usuario_model $model)
  {
    $this->model = $model;
  }

  public function findById(int $id): ?Usuario
  {
    return $this->model->findById($id);
  }

  public function findByNombre(string $nombre): ?Usuario
  {
    return $this->model->findByNombre($nombre);
  }

  public function findAll(): array
  {
    return $this->model->findAll();
  }

  public function create(Usuario $usuario): Usuario
  {
    $usuario->id = $this->model->create($usuario);
    return $usuario;
  }

  public function update(Usuario $usuario): Usuario
  {
    if ($this->model->findById($usuario->id) === null) {
      throw new Exception("Usuario {$usuario->id} no encontrado.", 68);
    }

    $this->model->update($usuario);
    return $usuario;
  }

  public function softDelete(int $id, int $modificadoPor): bool
  {
    if ($this->model->findById($id) === null) {
      throw new Exception("Usuario {$id} no encontrado", 1);
    }

    return $this->model->softDelete($id, $modificadoPor);
  }
}

<?php declare(strict_types=1);

class UsuarioService implements IUsuarioService
{
  private IUsuarioRepository $repository;

  public function __construct(IUsuarioRepository $repository)
  {
    $this->repository = $repository;
  }

  public function obtenerPorId($id): ?Usuario
  {
    return $this->repository->findById($id);
  }

  public function obtenerTodos(): array
  {
    return $this->repository->findAll();
  }

  /**
   * Crear un usuario con validación de nombre, contraseña y rol
   * @param array{nombre: string, contrasena: string, idRol: int} $nuevo
   * @param int $creadoPor
   * @throws Exception
   * @return void
   */
  public function crear(array $nuevo, int $creadoPor): Usuario
  {
    $this->validarDatosCreacion($nuevo);

    if ($this->repository->findByNombre($nuevo['nombre']) !== null) {
      throw new Exception("El nombre {$nuevo['nombre']} ya está en uso.", 1);
    }

    $usuario = new Usuario($nuevo);
    $usuario->creadoPor = $creadoPor;
    $usuario->modificadoPor = $creadoPor;
    $usuario->setContrasena($nuevo['contrasena']);

    return $this->repository->create($usuario);
  }

  /**
   * Actualizar un usuario
   * @param int $id
   * @param array{nombre: string, contrasena: string, idRol: int} $nuevo
   * @param int $modificadoPor
   * @return void
   */
  public function actualizar(int $id, array $nuevo, int $modificadoPor): Usuario
  {
    $usuario = $this->repository->findById($id);

    if (!$usuario) {
      throw new Exception("El usuario no existe", 1);
    }

    /** Si se eligió cambiar el nombre */
    if (isset($nuevo['nombre']) && $nuevo['nombre'] !== $usuario->nombre) {
      if ($this->repository->findByNombre($nuevo['nombre']) !== null) {
        throw new Exception("El nombre {$nuevo['nombre']} ya está en uso.", 1);
      }
    }

    if (isset($nuevo['contrasena'])) {
      $this->validarContrasena($nuevo['contrasena']);
      $usuario->setContrasena($nuevo['contrasena']);
    }

    if (isset($nuevo['idRol'])) {
      $usuario->idRol = (int) $nuevo['idRol'];
    }

    $usuario->modificadoPor = $modificadoPor;

    return $this->repository->update($usuario);
  }

  public function eliminar(int $id, int $modificadoPor): bool
  {
    $usuario = $this->repository->findById($id);

    if (!$usuario) {
      throw new Exception("El usuario no existe", 1);
    }

    return $this->repository->softDelete($id, $modificadoPor);
  }

  public function login(string $nombre, string $contrasena): Usuario
  {
    $usuario = $this->repository->findByNombre($nombre);

    if ($usuario === null || !$usuario->evaluarContrasena($contrasena)) {
      throw new RuntimeException('Credenciales incorrectas.');
    }

    return $usuario;
  }

  /**
   * Validar el ingreso e integridad del nombre, contrasena y rol para la creación
   * de un usuario
   * @param array{nombre: string, contrasena: string, idRol: int} $nuevo
   * @throws InvalidArgumentException
   * @return void
   */
  private function validarDatosCreacion(array $nuevo): void
  {
    if (empty($nuevo['nombre'])) {
      throw new InvalidArgumentException("El nombre es requerido", 1);
    }

    if (empty($nuevo['contrasena'])) {
      throw new InvalidArgumentException("La contraseña es requerida", 1);
    }

    if (empty($nuevo['idRol'])) {
      throw new InvalidArgumentException("El rol es requerido", 1);
    }

    $this->validarNombre($nuevo['nombre']);
    $this->validarContrasena($nuevo['contrasena']);
  }

  /**
   * Verificar el nombre ingresado
   * @param string $nombre
   * @throws InvalidArgumentException
   * @return void
   */
  private function validarNombre(string $nombre): void
  {
    if (mb_strlen($nombre) < 3) {
      throw new InvalidArgumentException('El nombre debe tener al menos 3 caracteres.');
    }

    if (mb_strlen($nombre) > 24) {
      throw new InvalidArgumentException('El nombre no puede superar 24 caracteres.');
    }
  }

  /**
   * Verificar la contraseña ingresada
   * @param string $contrasena
   * @throws InvalidArgumentException
   * @return void
   */
  private function validarContrasena(string $contrasena): void
  {
    if (mb_strlen($contrasena) < 8) {
      throw new InvalidArgumentException('La contraseña debe tener al menos 8 caracteres.');
    }
  }
}

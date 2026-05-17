<?php declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends BaseController
{
    private IUsuarioService $usuariosService;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');

        $repository = new UsuarioRepository($this->Usuario_model);
        $this->usuariosService = new UsuarioService($repository);
    }

    public function index()
    {
        $usuarios = $this->usuariosService->obtenerTodos();
        return $this->_json(200, $usuarios);
    }

    public function show(string $id)
    {
        $usuario = $this->usuariosService->obtenerPorId((int) $id);

        if (!$usuario) {
            return $this->_json(404, [], 'Usuario no encontrado', ['El ID no existe.']);
        }

        return $this->_json(200, $usuario, 'Usuario encontrado');
    }

    public function create()
    {
        try {
            $usuario = $this->usuariosService->crear($this->_body(), 1);
            return $this->_json(201, $usuario, "Se ha creado el usuario: {$usuario->nombre}");
        } catch (\InvalidArgumentException $e) {
            return $this->_json(422, [], $e->getMessage());
        } catch (\RuntimeException $e) {
            return $this->_json(409, [], $e->getMessage());
        }
    }

    public function update(string $id)
    {
        try {
            $usuario = $this->usuariosService->actualizar((int) $id, $this->_body(), 1);
            $this->_json(200, $usuario, "Usuario {$usuario->nombre} actualizado correctamente");
        } catch (InvalidArgumentException $e) {
            $this->_json(422, [], $e->getMessage());
        } catch (RuntimeException $e) {
            $this->_json(404, [], $e->getMessage());
        }
    }
}
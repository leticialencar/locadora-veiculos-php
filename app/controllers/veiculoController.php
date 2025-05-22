<?php
require_once __DIR__ . '/../models/veiculo.php';
require_once __DIR__ . '/../models/VeiculoDAO.php';

class VeiculoController {
    public function listar() {
        $dao = new VeiculoDAO();
        return $dao->listar();
    }

    public function buscarPorId($id) {
        $dao = new VeiculoDAO();
        return $dao->buscarPorId($id);
    }
}

<?php

class Controller {
    public function view($view, $data = []) {
        include '../app/views/'.$view.'.php';
    }

    public function model($model) {
        include '../app/models/'.$model.'.php';
        return new $model;
    }

    protected function checkRole($role) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header("Location: " . BASEURL);
            exit;
        }
    }
}

?>
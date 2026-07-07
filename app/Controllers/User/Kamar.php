<?php namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\KamarModel;

class Kamar extends BaseController {
    public function index() {
        $model = new KamarModel();
        return view('user/kamar/index', ['title' => 'Kamar Tersedia', 'kamar' => $model->getKamarTersedia()]);
    }
    public function detail($id) {
        $model = new KamarModel();
        return view('user/kamar/detail', ['title' => 'Detail Kamar', 'kamar' => $model->find($id)]);
    }
}

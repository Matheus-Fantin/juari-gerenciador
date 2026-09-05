<?php

namespace App\Http\Controllers;

use App\Services\JuariSiteClient;
use Illuminate\View\View;

class VisitsController extends Controller
{
    public function index(JuariSiteClient $client): View
    {
        $conectado = $client->isReachable();
        $dados = null;

        if ($conectado) {
            $dados = $client->pageViews();
        }

        return view('visits.index', [
            'conectado' => $conectado,
            'dados' => $dados,
        ]);
    }
}

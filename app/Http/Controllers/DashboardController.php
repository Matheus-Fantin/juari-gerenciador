<?php

namespace App\Http\Controllers;

use App\Services\JuariSiteClient;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(JuariSiteClient $client): View
    {
        $conectado = $client->isReachable();
        $pendentes = 0;
        $totalFotos = 0;
        $visitasHoje = 0;

        if ($conectado) {
            $depoimentos = $client->testimonials();
            $pendentes = collect($depoimentos)->where('publicado', false)->count();

            $galerias = $client->galleries();
            $totalFotos = collect($galerias)->sum(fn ($g) => count($g['photos'] ?? []));

            $visitasHoje = $client->pageViews()['hoje'] ?? 0;
        }

        return view('dashboard', [
            'conectado' => $conectado,
            'pendentes' => $pendentes,
            'totalFotos' => $totalFotos,
            'visitasHoje' => $visitasHoje,
        ]);
    }
}

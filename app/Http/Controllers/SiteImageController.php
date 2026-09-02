<?php

namespace App\Http\Controllers;

use App\Services\JuariSiteClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteImageController extends Controller
{
    public function index(JuariSiteClient $client): View
    {
        try {
            $imagens = collect($client->siteImages());
            $erro = null;
        } catch (\Throwable) {
            $imagens = collect();
            $erro = 'Não foi possível conectar ao site (juari-eventos-02). Confira se ele está no ar e se o token da API está correto.';
        }

        return view('site-images.index', ['imagens' => $imagens, 'erro' => $erro]);
    }

    public function update(Request $request, string $slot, JuariSiteClient $client): RedirectResponse
    {
        $request->validate([
            'imagem' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [
            'imagem.required' => 'Escolha uma imagem para enviar.',
            'imagem.image' => 'O arquivo precisa ser uma imagem (jpg, png, webp...).',
            'imagem.mimes' => 'Formato não aceito. Envie jpg, png ou webp.',
            'imagem.max' => 'A imagem não pode passar de 8MB.',
        ]);

        try {
            $client->updateSiteImage($slot, $request->file('imagem'));

            return back()->with('status', 'Imagem atualizada.');
        } catch (\Throwable $e) {
            return back()->with('erro', 'Não foi possível enviar a imagem: '.$e->getMessage());
        }
    }
}

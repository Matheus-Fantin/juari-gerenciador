<?php

namespace App\Http\Controllers;

use App\Services\JuariSiteClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(JuariSiteClient $client): View
    {
        try {
            $galerias = collect($client->galleries());
            $grupos = [
                'eventos' => ['titulo' => 'Tipos de Evento', 'galerias' => $galerias->where('tipo', 'eventos')->values()],
                'gastronomia' => ['titulo' => 'Gastronomia', 'galerias' => $galerias->where('tipo', 'gastronomia')->values()],
            ];
            $erro = null;
        } catch (\Throwable) {
            $grupos = [];
            $erro = 'Não foi possível conectar ao site (juari-eventos-02). Confira se ele está no ar e se o token da API está correto.';
        }

        return view('galleries.index', ['grupos' => $grupos, 'erro' => $erro]);
    }

    public function store(Request $request, JuariSiteClient $client): RedirectResponse
    {
        $data = $request->validate([
            'gallery_id' => ['required', 'integer'],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [
            'foto.required' => 'Escolha uma imagem para enviar.',
            'foto.image' => 'O arquivo precisa ser uma imagem (jpg, png, webp...).',
            'foto.mimes' => 'Formato não aceito. Envie jpg, png ou webp.',
            'foto.max' => 'A imagem não pode passar de 8MB.',
        ]);

        try {
            $client->uploadPhoto((int) $data['gallery_id'], $request->file('foto'));

            return back()->with('status', 'Foto adicionada.');
        } catch (\Throwable $e) {
            return back()->with('erro', 'Não foi possível enviar a foto: '.$e->getMessage());
        }
    }

    public function destroy(int $photo, JuariSiteClient $client): RedirectResponse
    {
        try {
            $client->deletePhoto($photo);

            return back()->with('status', 'Foto excluída.');
        } catch (\Throwable) {
            return back()->with('erro', 'Não foi possível excluir a foto. Tente novamente.');
        }
    }
}

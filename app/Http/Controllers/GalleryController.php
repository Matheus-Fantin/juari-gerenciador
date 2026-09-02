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
            'legenda' => ['nullable', 'string', 'max:255'],
        ], [
            'foto.required' => 'Escolha uma imagem para enviar.',
            'foto.image' => 'O arquivo precisa ser uma imagem (jpg, png, webp...).',
            'foto.mimes' => 'Formato não aceito. Envie jpg, png ou webp.',
            'foto.max' => 'A imagem não pode passar de 8MB.',
            'legenda.max' => 'A legenda pode ter no máximo 255 caracteres.',
        ]);

        try {
            $client->uploadPhoto((int) $data['gallery_id'], $request->file('foto'), $data['legenda'] ?? null);

            return back()->with('status', 'Foto adicionada.');
        } catch (\Throwable $e) {
            return back()->with('erro', 'Não foi possível enviar a foto: '.$e->getMessage());
        }
    }

    public function updateCaption(Request $request, int $photo, JuariSiteClient $client): RedirectResponse
    {
        $data = $request->validate([
            'legenda' => ['nullable', 'string', 'max:255'],
        ], [
            'legenda.max' => 'A legenda pode ter no máximo 255 caracteres.',
        ]);

        try {
            $client->updatePhotoCaption($photo, $data['legenda'] ?? null);

            return back()->with('status', 'Legenda atualizada.');
        } catch (\Throwable $e) {
            return back()->with('erro', 'Não foi possível salvar a legenda: '.$e->getMessage());
        }
    }

    public function move(Request $request, int $photo, JuariSiteClient $client): RedirectResponse
    {
        $data = $request->validate([
            'direcao' => ['required', 'in:subir,descer'],
        ]);

        try {
            $client->movePhoto($photo, $data['direcao']);

            return back()->with('status', 'Ordem atualizada.');
        } catch (\Throwable) {
            return back()->with('erro', 'Não foi possível reordenar a foto. Tente novamente.');
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

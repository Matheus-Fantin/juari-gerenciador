<?php

namespace App\Http\Controllers;

use App\Services\JuariSiteClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(JuariSiteClient $client): View
    {
        try {
            $depoimentos = collect($client->testimonials())->sortBy('publicado')->values();
            $erro = null;
        } catch (\Throwable) {
            $depoimentos = collect();
            $erro = 'Não foi possível conectar ao site (juari-eventos-02). Confira se ele está no ar e se o token da API está correto.';
        }

        return view('testimonials.index', ['depoimentos' => $depoimentos, 'erro' => $erro]);
    }

    public function approve(int $testimonial, JuariSiteClient $client): RedirectResponse
    {
        try {
            $client->approveTestimonial($testimonial);

            return back()->with('status', 'Depoimento publicado no site.');
        } catch (\Throwable) {
            return back()->with('erro', 'Não foi possível aprovar o depoimento. Tente novamente.');
        }
    }

    public function unpublish(int $testimonial, JuariSiteClient $client): RedirectResponse
    {
        try {
            $client->unpublishTestimonial($testimonial);

            return back()->with('status', 'Depoimento removido do site (continua salvo lá).');
        } catch (\Throwable) {
            return back()->with('erro', 'Não foi possível despublicar o depoimento. Tente novamente.');
        }
    }

    public function destroy(int $testimonial, JuariSiteClient $client): RedirectResponse
    {
        try {
            $client->deleteTestimonial($testimonial);

            return back()->with('status', 'Depoimento excluído.');
        } catch (\Throwable) {
            return back()->with('erro', 'Não foi possível excluir o depoimento. Tente novamente.');
        }
    }
}

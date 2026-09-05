<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class JuariSiteClient
{
    protected function http(): PendingRequest
    {
        return Http::baseUrl(rtrim(config('services.juari_site.api_url'), '/'))
            ->withToken(config('services.juari_site.token'))
            ->acceptJson()
            ->timeout(15);
    }

    public function testimonials(): array
    {
        return $this->http()->get('/testimonials')->throw()->json('data', []);
    }

    public function approveTestimonial(int $id): void
    {
        $this->http()->patch("/testimonials/{$id}/approve")->throw();
    }

    public function unpublishTestimonial(int $id): void
    {
        $this->http()->patch("/testimonials/{$id}/unpublish")->throw();
    }

    public function deleteTestimonial(int $id): void
    {
        $this->http()->delete("/testimonials/{$id}")->throw();
    }

    public function galleries(): array
    {
        return $this->http()->get('/galleries')->throw()->json('data', []);
    }

    public function uploadPhoto(int $galleryId, UploadedFile $file, ?string $legenda = null): array
    {
        $response = $this->http()
            ->attach('foto', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post("/galleries/{$galleryId}/photos", array_filter(['legenda' => $legenda]));

        if ($response->status() === 422) {
            throw new \RuntimeException($response->json('errors.foto.0', 'Não foi possível enviar a foto.'));
        }

        return $response->throw()->json('data', []);
    }

    public function updatePhotoCaption(int $photoId, ?string $legenda): void
    {
        $response = $this->http()->patch("/photos/{$photoId}", ['legenda' => $legenda]);

        if ($response->status() === 422) {
            throw new \RuntimeException($response->json('errors.legenda.0', 'Não foi possível salvar a legenda.'));
        }

        $response->throw();
    }

    public function movePhoto(int $photoId, string $direcao): void
    {
        $this->http()->patch("/photos/{$photoId}/mover", ['direcao' => $direcao])->throw();
    }

    public function deletePhoto(int $photoId): void
    {
        $this->http()->delete("/photos/{$photoId}")->throw();
    }

    public function siteImages(): array
    {
        return $this->http()->get('/site-images')->throw()->json('data', []);
    }

    public function updateSiteImage(string $slot, UploadedFile $file): array
    {
        $response = $this->http()
            ->attach('imagem', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post("/site-images/{$slot}");

        if ($response->status() === 422) {
            throw new \RuntimeException($response->json('errors.imagem.0', 'Não foi possível enviar a imagem.'));
        }

        return $response->throw()->json('data', []);
    }

    public function pageViews(): array
    {
        return $this->http()->get('/page-views')->throw()->json('data', []);
    }

    /**
     * Verifica se a API do site está acessível e o token é válido.
     */
    public function isReachable(): bool
    {
        try {
            $this->http()->get('/testimonials')->throw();

            return true;
        } catch (RequestException|\Throwable) {
            return false;
        }
    }
}

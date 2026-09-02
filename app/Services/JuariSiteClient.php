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

    public function uploadPhoto(int $galleryId, UploadedFile $file): array
    {
        $response = $this->http()
            ->attach('foto', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post("/galleries/{$galleryId}/photos");

        if ($response->status() === 422) {
            throw new \RuntimeException($response->json('errors.foto.0', 'Não foi possível enviar a foto.'));
        }

        return $response->throw()->json('data', []);
    }

    public function deletePhoto(int $photoId): void
    {
        $this->http()->delete("/photos/{$photoId}")->throw();
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

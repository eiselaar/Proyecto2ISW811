<?php

namespace App\Services\SocialMedia\Contracts;

interface SocialMediaProvider
{
    public function publish(string $content, array $media = []): array;
    public function delete(string $postId): bool;
    public function refreshToken(): bool;
    public function getAccountInfo(): array;
}
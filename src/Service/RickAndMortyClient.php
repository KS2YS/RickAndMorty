<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RickAndMortyClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    public function getCharacters(int $page): array
    {
        $response = $this->httpClient->request('GET', 'https://rickandmortyapi.com/api/character?page=' . $page);
        return $response->toArray();
    }

    public function getEpisodes(array $episodeIds): array
    {
        $episodeResponse = $this->httpClient->request(
            'GET',
            'https://rickandmortyapi.com/api/episode/' . implode(',', $episodeIds)
        );
        return $episodeResponse->toArray();
    }
}
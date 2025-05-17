<?php

namespace App\Service;

class RickAndMortyService
{
    public function __construct(
        private RickAndMortyClient $client,
    ) {
    }

    public function getCharactersData(int $page): array
    {
        return $this->client->getCharacters($page);
    }

    public function getEpisodesIdsMap(array $charactersData): array
    {
        $characterEpisodesIdsMap = [];
        foreach ($charactersData['results'] as $characterInfo) {
            $characterEpisodesIdsMap[$characterInfo['id']] = [];
            foreach ($characterInfo['episode'] as $episodeInfo) {
                $id = basename($episodeInfo);
                $characterEpisodesIdsMap[$characterInfo['id']][$id] = $id;
            }
        }
        return $characterEpisodesIdsMap;
    }

    public function getEpisodeAppearancesForCharacters(array $characterEpisodesIdsMap): array
    {
        $episodeData = $this->client->getEpisodes(array_unique(array_merge(...$characterEpisodesIdsMap)));
        $episodesList = [];
        foreach ($episodeData as $episodeInfo) {
            $episodesList[$episodeInfo['id']] = $episodeInfo;
        }
        return $episodesList;
    }
}
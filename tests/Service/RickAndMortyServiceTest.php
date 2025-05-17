<?php

namespace App\Tests\Service;

use App\Service\RickAndMortyClient;
use App\Service\RickAndMortyService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RickAndMortyServiceTest extends KernelTestCase
{
    public function testGetCharacters(): void
    {
        self::bootKernel();

        $mock = $this->getMockData('characters.json');
        $rickAndMortyClient = $this->createStub(RickAndMortyClient::class);
        $rickAndMortyClient->method('getCharacters')
            ->willReturn($mock);

        $rickAndMortyService = new RickAndMortyService($rickAndMortyClient);
        $rickAnMortyData = $rickAndMortyService->getCharactersData(1);

        $this->assertEquals($mock, $rickAnMortyData);
    }

    public function testGetEpisodesIdsMap(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $rickAndMortyService = $container->get(RickAndMortyService::class);

        $mock = $this->getMockData("characters.json");
        $rickAnMortyData = $rickAndMortyService->getEpisodesIdsMap($mock);

        $this->assertEquals([1 => [1 => "1", 2 => "2"]], $rickAnMortyData);
    }

    public function testGetEpisodeAppearancesForCharacters(): void
    {
        self::bootKernel();

        $rickAndMortyClient = $this->createStub(RickAndMortyClient::class);
        $rickAndMortyClient->method('getEpisodes')
            ->willReturn($this->getMockData('episodes.json'));

        $rickAndMortyService = new RickAndMortyService($rickAndMortyClient);
        $rickAnMortyData = $rickAndMortyService->getEpisodeAppearancesForCharacters([]);

        $this->assertEquals($this->getMockData('response-episode-appearances.json'), $rickAnMortyData);
    }

    private function getMockData(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__ . "/MockData/" . $filename), true);
    }
}
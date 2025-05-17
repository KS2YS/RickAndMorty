<?php

namespace App\Controller;

use App\Service\RickAndMortyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class RickAndMortyController extends AbstractController
{
    #[Route('/{page}', name: 'rnm_character', defaults: ['page' => 1])]
    public function character(int $page, RickAndMortyService $service): Response
    {
        $charactersData = $service->getCharactersData($page);

        if ($charactersData['info']['prev']) {
            $previous = $this->generateUrl('rnm_character', ['page' => $page - 1]);
        }
        if ($charactersData['info']['next']) {
            $next = $this->generateUrl('rnm_character', ['page' => $page + 1]);
        }

        $characterEpisodesIdsMap = $service->getEpisodesIdsMap($charactersData);
        $episodesList = $service->getEpisodeAppearancesForCharacters($characterEpisodesIdsMap);

        return $this->render('rnm/characters.html.twig', [
                'charactersData' => $charactersData,
                'previousUrl' => $previous ?? null,
                'nextUrl' => $next ?? null,
                'page' => $page,
                'episodesList' => $episodesList,
                'characterEpisodesIdsMap' => $characterEpisodesIdsMap
            ]
        );
    }
}
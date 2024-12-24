<?php

namespace Survos\TheGuardianBundle\Controller;

use Survos\TheGuardianBundle\Form\SearchGuardianType;
use Survos\TheGuardianBundle\Service\TheGuardianService;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class TheGuardianController extends AbstractController
{
    public function __construct(
        private TheGuardianService $theGuardianService,
        private $simpleDatatablesInstalled = false
    )
    {
        $this->checkSimpleDatatablesInstalled();
    }

    private function checkSimpleDatatablesInstalled()
    {
        if (! $this->simpleDatatablesInstalled) {
            throw new \LogicException("This page requires SimpleDatatables\n composer req survos/simple-datatables-bundle");
        }
    }
    #[Route('/search', name: 'survos_the_guardian_search', methods: ['GET'])]
//    #[Template('@SurvosTheGuardian/search.html.twig')]
    public function search(
        Request $request,
        #[MapQueryParameter] ?string $q=null
    ): Response|array
    {

        $defaults  = [
            'q' => $q
        ];
        $form = $this->createForm(SearchGuardianType::class, $defaults, [
            'action' => $this->generateUrl('survos_the_guardian_search', ['q' => $q]),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $redirect =  $this->redirectToRoute('survos_the_guardian_search', ['q' => $form->getData()['q']]);
            dump($redirect->getTargetUrl());
            return $redirect;
        }
        if ($q) {
            $query = $this->theGuardianService->contentApi()
                // put the Content methods first
                ->setQuery($q)
                ->setQueryFields('headline')
                // then the ContentAPIEntity methods
                ->setShowFields('all')
                ->setOrderBy('newest')
                ->setOrderDate('published')
                ;
            $response = $this->theGuardianService->fetch($query);
        } else {
            $response = null;
        }
            return $this->render('@SurvosTheGuardian/search.html.twig', [
                'total' => $response?->total,
                'articles' => $response?->results,
                'searchForm' => $form->createView(),
            ]);
            // a nice search form
    }

    #[Route('/sources/{language}', name: 'survos_the_guardian_tags', methods: ['GET'])]
    #[Template('@SurvosTheGuardian/tags.html.twig')]
    public function sources(string $language=null): Response|array
    {
        $tags  = $this->theGuardianService->tagsApi();
        return [
            'tags' => $tags,
        ];
    }


}

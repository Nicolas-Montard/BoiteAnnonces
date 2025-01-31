<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/annonce')]
final class ApiAnnonceController extends AbstractController
{
    #[Route('/', name: 'api_annonces_index', methods: ['GET'])]
    public function apiIndex(AnnonceRepository $annonceRepository): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json($annonceRepository->findAll(), 200, [], ['groups' => 'annonce:read']);
    }

    #[Route('/{id}', name: 'api_annonce_show', methods: ['GET'])]
    public function apiShow(Annonce $annonce): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json($annonce, 200, [], ['groups' => 'annonce:read']);
    }

    #[Route('/', name: 'api_annonce_create', methods: ['POST'])]
    public function apiCreate(Request $request, EntityManagerInterface $entityManager, Security $security): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $annonce = new Annonce();
        $annonce->setTitle($data['title'] ?? '');
        $annonce->setDescription($data['description'] ?? '');
        #$annonce->setOwner($user);

        $entityManager->persist($annonce);
        $entityManager->flush();

        return $this->json($annonce, Response::HTTP_CREATED, [], ['groups' => 'annonce:read']);
    }
}

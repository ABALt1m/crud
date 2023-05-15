<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Provincie;
use App\Form\ProvincieType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ...
class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function showProvincie(ManagerRegistry $doctrine): Response
    {
        $provincies = $doctrine->getRepository(Provincie::class)->findAll();

         return $this->render('provincie/show.html.twig', ['provincies' => $provincies]);
    }

    #[Route('cities/{id}', name: 'cities')]
    public function showCities(ManagerRegistry $doctrine, int $id): Response
    {
        $provincie = $doctrine->getRepository(Provincie::class)->find($id);


        return $this->render('provincie/provincie.html.twig', ['provincie' => $provincie]);
    }

    #[Route('update/{id}', name: 'update')]
    public function updateProvincie(Request $request, EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine): Response
    {
        $provincie = $doctrine->getRepository(Provincie::class)->find($id);

        $form = $this->createForm(ProvincieType::class, $provincie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $provincie = $form->getData();
            $entityManager->persist($provincie);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();



            return $this->redirectToRoute('app_main');
        }

        return $this->renderForm('provincie/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'delete')]
    public function deleteCity(EntityManagerInterface $entityManager, ManagerRegistry $doctrine, int $id): Response
    {
        $city = $doctrine->getRepository(City::class)->find($id);
        $entityManager->remove($city);
        $entityManager->flush();

        return $this->redirectToRoute('cities', ['id' => $city->getProvincie()->getId()]);
    }

    #[Route('insert', name: 'insert')]
    public function insertProvincie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $provincie = new Provincie();

        $form = $this->createForm(ProvincieType::class, $provincie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $provincie = $form->getData();
            $entityManager->persist($provincie);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();



            return $this->redirectToRoute('app_main');
        }

        return $this->renderForm('provincie/new.html.twig', [
            'form' => $form,
        ]);
    }


}

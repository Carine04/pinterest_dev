<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AccountFormType;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function show(): Response
    {
        if ($this->getUser() == false){
            $this->addFlash('error', 'You must login to see your account');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('account/show.html.twig');
    }


    #[Route('/account/edit', name:'app_account_edit', methods: ['GET', 'POST'])] 
public function edit(Request $request, EntityManagerInterface $em): Response
    {
        if ($this->getUser() == false){
            $this->addFlash('error', 'You must login to see your account');
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Account successfuly updated !');
            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit.html.twig', [
            'user' => $user,
            'accountForm' => $form->createView()
        ]);
    }
}


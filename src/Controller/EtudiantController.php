<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EtudiantRepository;
use App\Entity\Etudiant;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EtudiantType;
use Symfony\Component\HttpFoundation\Request;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant_list', name: 'list_etudiant')]
    public function afficher(EtudiantRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('etudiant/afficher.html.twig',['etudiant'=>$list]);
    }
    #[Route('/Add',name:'etudiant_add')]
    public function ajouter(ManagerRegistry $doctrine,Request $request ):response
    {
        $etudiant=new Etudiant(); 
        
        $form=$this->createForm(EtudiantType::class,$etudiant);
        $form->handleRequest($request);
       if ($form->isSubmitted() )
       {
        $em=$doctrine->getManager(); 
        $em->persist($etudiant); 
        $em->flush();
        return $this->redirectToRoute('list_etudiant');
       }
      return $this->render('etudiant/add.html.twig',['form'=>$form->createView()]) ; 
    }
    #[Route('/Update/{id}',name:'etudiant_update')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,EtudiantRepository $repo):response
    {
        $etudiant=$repo->find($id);
        $form=$this->createForm(EtudiantType::class,$etudiant);
        $form->handleRequest($request);
       if ($form->isSubmitted() )
       {
        $em=$doctrine->getManager(); 
        $em->flush();
        return $this->redirectToRoute('list_etudiant');
    }
    return $this->render('etudiant/update.html.twig',['form'=>$form->createView()]) ;
    
    }
    #[Route('/Delete/{id}',name:'etudiant_delete')]
    public function supprimer($id,EtudiantRepository $repo,ManagerRegistry $doctrine ):response
    {
        $etudiant=$repo->find($id);
        $em=$doctrine->getManager();
        $em->remove($etudiant);
        $em->flush();
        return $this->redirectToRoute('list_etudiant');
    }
}

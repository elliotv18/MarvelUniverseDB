<?php

namespace App\Controller;


use App\Services\OrganisationService;
use App\Entity\Organisation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class OrganisationController extends AbstractController
{
    /**
     * @Route("/organisation", name="organisation")
     */
    public function index(OrganisationService $organisationService): Response
    {
        $listeOrganisation = $organisationService->getList();
        return $this->render('organisation/index.html.twig', [
            'controller_name' => 'OrganisationController',
            'listeOrganisations' => $listeOrganisation
        ]);
    }

    /**
     * @Route("organisation/create","organisation_creation")
     */
    public function newOrganisation(Request $request,OrganisationService $organisationService):Response
    {

        $organisation = new Organisation('','');
        $form = $this->createFormBuilder($organisation)
        ->add('name',TextType::class)
        ->add('city',TextType::class)
        ->add('save', SubmitType::class, ['label' => 'Créer organisation'])
            ->getForm();
        $request = Request::createFromGlobals();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $organisation = $form->getData();
            $organisationService->addOrganisation($organisation);
            return $this->render('organisation/createcompleted.html.twig',['organisation'=>$organisation]);
        }
        else
        return $this->render('organisation/creer.html.twig',['formulaire'=>$form->createView()]);
    }
     /**
     * @Route("/organisation/{pId}", "organisation_show")
     */

    public function show($pId, OrganisationService $organisationService): Response
    {
        $organisation = $organisationService->getOrganisation($pId);
        return $this->render('organisation/organisation.html.twig',['organisation' => $organisation['organisation']]);
    }
    /**
     * @Route("/organisation/delete/{pId}", "organisation_delete")
     */
    public function delete($pId, OrganisationService $organisationService): Response
    {
        $organisationService->delOrga($pId);
        $listeOrganisation =$organisationService->getList();
        return $this->render('organisation/orgasupprime.html.twig', [
            
            'listeOrganisation'=>$listeOrganisation,
        ]);
    }
}

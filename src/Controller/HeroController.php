<?php

namespace App\Controller;


use App\Services\HeroService;
use App\Entity\Hero;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HeroController extends AbstractController
{
    /**
     * @Route("/hero", name="hero")
     */
    public function index(HeroService $heroService): Response
    {   
        $listeHeros = $heroService->getList();
        return $this->render('hero/index.html.twig', [
            'controller_name' => 'HeroController',
            'listeHeros'=>$listeHeros,
        ]);
    }

    /**
     * @Route("hero/create","hero_creation")
     */
    public function newHero(Request $request,HeroService $heroService):Response
    {

        $hero = new Hero('','',false,'','', '');
        $form = $this->createFormBuilder($hero)
        ->add('firstname',TextType::class)
        ->add('name',TextType::class)
        ->add('pseudo',TextType::class)
        ->add('description',TextType::class)
        ->add('isEvil', CheckboxType::class)
        /*->add('picture', FileType::class, ['required'=> false, 'constraints' => [new File(['maxSize' => '1024k','mimeTypes' => ['application/pdf','application/x-pdf',],])]])*/
        ->add('save', SubmitType::class, ['label' => 'Créer Hero'])
            ->getForm();
        $request = Request::createFromGlobals();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $hero = $form->getData();
            $heroService->addHero($hero);
            return $this->render('hero/createcompleted.html.twig',['hero'=>$hero]);
        }
        else
        return $this->render('hero/creer.html.twig',['formulaire'=>$form->createView()]);
    }

    /**
     * @Route("/hero/{pId}", "hero_show")
     */

    public function show($pId, HeroService $heroService): Response
    {
        $hero = $heroService->getHero($pId);
        return $this->render('hero/hero.html.twig',['hero' => $hero['hero']]);
    }
    /**
     * @Route("/hero/delete/{pId}", "hero_delete")
     */
    public function delete($pId, HeroService $heroService): Response
    {
        $heroService->delHero($pId);
        $listeHeros =$heroService->getList();
        return $this->render('hero/herosupprime.html.twig', [
            
            'listeHeros'=>$listeHeros,
        ]);
    }
}

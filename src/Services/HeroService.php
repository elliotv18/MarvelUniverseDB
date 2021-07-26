<?php 
namespace App\Services;

use App\Entity\Hero;
use Doctrine\ORM\EntityManagerInterface;

class HeroService
{
    private $_entityManager = [];
    private $_listeHeros = [];

    function __construct(EntityManagerInterface $em)
    {
        $this->_entityManager = $em;
        $this->_listeHeros = $this->_entityManager->getRepository(Hero::class)->findAll();
    }
    
function getList()
    {
        return $this->_listeHeros;
    }
    function addHero($pHero)
    {
        array_push($this->_listeHeros,$pHero);
        $this->_entityManager->persist($pHero);
        $this->_entityManager->flush();
    }
    public function getHero($pId)
    {
        /* $find = false;
         $hero = null;
         $i = 0; 
         while (($i < count($this->_listeHeros))&& $find == false)
         {
             if ($this->_listeHeros[$i]->getId()==$pId)
             {
                 $find = true;
             $hero = $this->_listeHeros[$i];
             }
             $i++;
         }
         return  ['found'=>$find,'hero'=>$hero];*/
         $find = false;
         $hero = $this->_entityManager->getRepository(Hero::class)->find($pId);
         if (isset($hero))
             $find = true;
         return  ['found'=>$find,'hero'=>$hero];
     }
     public function delHero($pId)
    {
        $hero = $this->getHero($pId);
        if ($hero['found']== true)
        {
            $this->_entityManager->remove($hero['hero']);
            $this->_entityManager->flush();
        }
        
    }
    
}
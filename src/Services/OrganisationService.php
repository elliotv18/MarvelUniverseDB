<?php 
namespace App\Services;

use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;
class OrganisationService
{
    private $_entityManager = [];
    private $_listeOrganisations = [];

    function __construct(EntityManagerInterface $em)
    {
        $this->_entityManager = $em; 
        $this->_listeOrganisations = $this->_entityManager->getRepository(Organisation::class)->findAll();
    }
    function getList()
    {
        return $this->_listeOrganisations;
    }
    function addOrganisation($pOrganisation)
    {
        array_push($this->_listeOrganisations,$pOrganisation);
        $this->_entityManager->persist($pOrganisation);
        $this->_entityManager->flush();
    }
    public function getOrganisation($pId)
    {
        $find = false;
         $organisation = $this->_entityManager->getRepository(Organisation::class)->find($pId);
         if (isset($organisation))
             $find = true;
         return  ['found'=>$find,'organisation'=>$organisation];
    }
    public function delOrga($pId)
    {
        $organisation = $this->getOrganisation($pId);
        if ($organisation['found']== true)
        {
            $this->_entityManager->remove($organisation['organisation']);
            $this->_entityManager->flush();
        }
        
    }
}
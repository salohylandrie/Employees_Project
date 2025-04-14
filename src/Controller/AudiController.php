<?php

namespace App\Controller;

use App\Entity\EmployeAudit;
use App\Repository\EmployeAuditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AudiController extends AbstractController
{
    #[Route('/audit', name: 'app_audit', methods:['get'])]
    public function index(EntityManagerInterface $entityManagerInterface, EmployeAuditRepository $repo): JsonResponse
    {
       $evenements= $entityManagerInterface
        ->getRepository(EmployeAudit::class)
        ->findAll();
        

        $audits = $repo->findAll();
         
         $countAjout= $repo->CountByTypeAction('ajout');
         $countModification= $repo->CountByTypeAction('modification');
         $countSuppresion= $repo->CountByTypeAction('suppression');

        foreach($evenements as $evenement){
            $data=[


                'total_actions'=>[
                    "ajout"=>$countAjout,
                    "modification"=>$countModification,
                    "suppression"=>$countSuppresion,
                ],
    'evenements'=> array_map(function($evenement){ 
        return ['id'=>$evenement->getId(),
        'type_action'=>$evenement->getTypeAction(),
        'date_mise_ajour'=>$evenement->getDateMiseAjour()->format('d/m/Y H:i:s'),
        'matricule'=>$evenement->getMatricule(),
        'nom'=>$evenement->getNom(),
        'salaire_ancien'=>$evenement->getSalaireAncien(),
        'salaire_now'=>$evenement->getSalaireNow(),
        'email'=>$evenement->getEmail(),
    ];},$evenements
               
    )
];
        }
        return $this->json($data);
    }
}

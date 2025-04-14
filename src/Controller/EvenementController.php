<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\EmployeAudit;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Psr\Log\LoggerInterface;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement', methods:['get'])]
    public function index(EmployeRepository $employe_repository): JsonResponse
    {
       /** @var \App\Entity\User $user */
$user = $this->getUser();


// Récupérer les inscriptions pour l'utilisateur connecté ou toutes si admin
$inscriptions = $user 
    ? $employe_repository->findBy(['user' => $user]) 
    : $employe_repository->findAll();

// Transformer les données pour correspondre au format attendu
$formattedInscriptions = array_map(function (Employe $inscription) {
    return [
        'id'=> $inscription->getId(),
        'user_id' => $inscription->getUser()->getId(),
        'matricule'=>$inscription->getMatricule(),
                'nom'=>$inscription->getNom(),
                'salaire'=>$inscription->getSalaire(),
    ];
}, $inscriptions);

return $this->json($formattedInscriptions, 200);
}
    



    



    

#[Route('api/evenement/createEven', name: 'create_Even', methods: ['POST'])]
public function create(
    Request $request,
    EntityManagerInterface $em,
    ValidatorInterface $validator
): JsonResponse {
    $headers = $request->headers->all();
    dump($headers); // Vérifiez si le token JWT est bien reçu

    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return $this->json(['status' => false, 'message' => 'Données invalides ou manquantes'], 400);
    }

    /** @var \App\Entity\User|null $user */
    $user = $this->getUser();
    if (!$user) {
        return $this->json(['status' => false, 'message' => 'Utilisateur non trouvé'], 404);
    }

    if (!$user->getEmail()) {
        return $this->json(['status' => false, 'message' => 'Email utilisateur non défini'], 400);
    }

    // Vérification des champs requis
    if (!isset($data['matricule'], $data['nom'], $data['salaire'])) {
        return $this->json(['status' => false, 'message' => 'Tous les champs sont requis : matricule, nom, salaire'], 400);
    }

    $inscription = new Employe();
    $inscription->setUser($user);
    $inscription->setEmail($user->getEmail());
    $inscription->setMatricule($data['matricule']);
    $inscription->setNom($data['nom']);
    $inscription->setSalaire((float) $data['salaire']);

    // Validation des données
    $errors = $validator->validate($inscription);
    if (count($errors) > 0) {
        $validationErrors = [];
        foreach ($errors as $error) {
            $validationErrors[] = $error->getMessage();
        }
        return $this->json(['status' => false, 'errors' => $validationErrors], 400);
    }

    $em->persist($inscription);
    $em->flush();

    // Transformer l'inscription en réponse formatée
    $formattedInscription = [
        'id' => $inscription->getId(),
        'user_id' => $inscription->getUser()->getId(),
        'email' => $inscription->getEmail(),
        'matricule' => $inscription->getMatricule(),
        'nom' => $inscription->getNom(),
        'salaire' => $inscription->getSalaire(),
    ];

    return $this->json(['status' => true, 'employe' => $formattedInscription], 201);
}











 // DÉTAIL D'UNE INSCRIPTION
 #[Route('/evenement/{id}', name: 'show', methods: ['GET'])]
 public function show(Employe $inscription): JsonResponse
 {
     /** @var UserInterface $user */
     $user = $this->getUser();

     if ($user && $inscription->getUser() !== $user) {
         return $this->json(['status' => false, 'message' => 'Accès non autorisé'], 403);
     }

     return $this->json($inscription, 200, [], ['groups' => 'employe:read']);
 }

 
 #[Route('/evenements/{id}', name: 'edit', methods: ['PUT', 'PATCH'])]
 public function update(
     Request $request,
     Employe $inscription,
     EntityManagerInterface $em,
     ValidatorInterface $validator
 ): JsonResponse {
     /** @var UserInterface $user */
     $user = $this->getUser();
 
     if ($user && $inscription->getUser() !== $user) {
         return $this->json(['status' => false, 'message' => 'Accès non autorisé'], 403);
     }
 
     $data = json_decode($request->getContent(), true);
 
     if (!$data) {
         return $this->json(['status' => false, 'message' => 'Données invalides ou manquantes'], 400);
     }
 
     // Vérifier quels champs sont fournis et les mettre à jour
     if (isset($data['matricule'])) {
         $inscription->setMatricule($data['matricule']);
     }
     if (isset($data['nom'])) {
         $inscription->setNom($data['nom']);
     }
     if (isset($data['salaire'])) {
         $inscription->setSalaire((float) $data['salaire']);
     }
 
     // Validation des données après modification
     $errors = $validator->validate($inscription);
     if (count($errors) > 0) {
         $validationErrors = [];
         foreach ($errors as $error) {
             $validationErrors[] = $error->getMessage();
         }
         return $this->json(['status' => false, 'errors' => $validationErrors], 400);
     }
 
     $em->flush(); // Enregistre les modifications en base de données
 
     return $this->json([
         'status' => true,
         'message' => 'Employé mis à jour avec succès',
         'employe' => [
             'id' => $inscription->getId(),
             'matricule' => $inscription->getMatricule(),
             'nom' => $inscription->getNom(),
             'salaire' => $inscription->getSalaire(),
         ]
     ], 200);
 }
 





#[Route('/evenement/delete/{id}', name:'evenement_delete', methods:'DELETE')]
public function evenementDelet(EntityManagerInterface $entityManagerInterface, int $id) : JsonResponse {
    $evenement = $entityManagerInterface->getRepository(Employe::class)->find($id);
    if(!$evenement){
        return $this->json('Employe not found'.$id, 404);
    }
    $entityManagerInterface->remove($evenement);
    $entityManagerInterface->flush();
    return $this->json('Employe delete successfully with'.$id);
}
}   

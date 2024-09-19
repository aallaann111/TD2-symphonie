<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurManager implements UtilisateurManagerInterface
{
    public function __construct(
        #[Autowire('%dossier_photo_profil%')] private string $dossierPhotoProfil,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * Chiffre le mot de passe puis l'affecte au champ correspondant dans la classe de l'utilisateur
     */
    private function chiffrerMotDePasse(Utilisateur $utilisateur, ?string $plainPassword): void
    {
        if ($plainPassword) {
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
            $utilisateur->setPassword($hashedPassword);
        }
    }

    /**
     * Sauvegarde l'image de profil dans le dossier de destination puis affecte son nom au champ correspondant dans la classe de l'utilisateur
     */
    private function sauvegarderPhotoProfil(Utilisateur $utilisateur, ?UploadedFile $fichierPhotoProfil): void
    {
        if ($fichierPhotoProfil) {
            $fileName = uniqid($utilisateur->getLogin() . '_') . '.' . $fichierPhotoProfil->guessExtension();
            $fichierPhotoProfil->move($this->dossierPhotoProfil, $fileName);
            $utilisateur->setNomPhotoProfil($fileName);
        }
    }

    /**
     * Réalise toutes les opérations nécessaires avant l'enregistrement en base d'un nouvel utilisateur, après soumissions du formulaire
     */
    public function processNewUtilisateur(Utilisateur $utilisateur, ?string $plainPassword, ?UploadedFile $fichierPhotoProfil): void
    {
        $this->chiffrerMotDePasse($utilisateur, $plainPassword);
        $this->sauvegarderPhotoProfil($utilisateur, $fichierPhotoProfil);
    }
}

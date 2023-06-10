<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


use App\Repository\UserRepository;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/users', methods: ['POST'])]
    public function createUser(Request $request, UserRepository $userRepository,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        
    
        if (isset($data['roles']) && is_string($data['roles'])) {
            $roles = explode(',', $data['roles']);
        } else {
        
            $roles = ['ROLE_USER'];
        }

        $user->setRoles($roles);
        
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $user->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
        $user->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $userRepository->save($user, true);

        return $this->json($user);
    }
}




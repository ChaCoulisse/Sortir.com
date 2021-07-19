<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditAccountFormType;
use App\Form\EditPasswordFormType;
// use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class EditUserController extends AbstractController
{
    /**
     * @Route("/user/profile/{id}", name="user_profile")
     */
    public function profileUser(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
    //    $user = $this->getUser();
    //    $user = $userRepository->find($id);
        $user = $entityManager->getRepository(User::class)->find($id);


        return $this->render( 'user/profileUser.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/modify", name="user_modify")
     */
    public function editUser(Request $request): Response
    {
        $user = $this->getUser();

        $editUserForm = $this->createForm(EditAccountFormType::class, $user);
        $editUserForm->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($editUserForm->isSubmitted() && $editUserForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_modify');
        }

        return $this->render('user/editUser.html.twig', [
            'editUserForm' => $editUserForm->createView(),
        ]);
    }

    /**
     * @Route("/user/password", name="user_password")
     */
    public function editPassword(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $editPasswordForm = $this->createForm(EditPasswordFormType::class, $user);
        $editPasswordForm->handleRequest();

        if($editPasswordForm->isSubmitted() && $editPasswordForm->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $editPasswordForm->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }
        return $this->render('user/editPassword.html.twig', [
            'editPasswordForm' => $editPasswordForm->createView(),
        ]);
    }
}

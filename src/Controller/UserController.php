<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_list")
     */

    public function index(UserRepository $repository)
    {

        $user = $repository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $user
        ]);
    }

    /**
     * @Route("/user/new", name="user_new")
     * @Route("/user/{id}/edit", name="user_edit")
     */

    public function new(User $user = null, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {

        if(!$user) {
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            if(!$user->getId()) {
                $user->setCreatedAt(new \DateTime())
                     ->setToken(random_bytes(255))
                     ->setPassword($password);
            } elseif ($user->getId()) {
                $user->setModifiedAt(new \DateTime());
            }

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute("user_more", ['id' => $user->getId()]);
        }

        return $this->render('user/new.html.twig', [
            'formUser' => $form->createView(),
            'editMode' => $user->getId() !== null,
        ]);
    }

    /**
     * @Route("/user/{id}/", name="user_more")
     */

    public function update(User $user)
    {

        return $this->render('user/update.html.twig', [
            'user' => $user
        ]);
    }



}

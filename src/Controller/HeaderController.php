<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeaderController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fornissors', [$this, 'getNewUserNotifications']),
            new TwigFunction('fornissorsCount', [$this, 'getNewUserNotificationsCount']),
        ];
    }

    public function getNewUserNotifications(): array
    {
        return $this->userRepository->findFournisseurAccount();
    }


}

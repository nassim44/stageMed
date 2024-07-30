<?php

namespace App\Services;

use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private UserRepository $userRepository;
    private LoggerInterface $logger;
    public function __construct(UserRepository $userRepository,LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
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
        return $this->userRepository->findFornissorAccount();
    }
    public function getNewUserNotificationsCount(): int
    {
        $fornissors = $this->userRepository->findFornissorAccount();
        return count($fornissors);
    }

}
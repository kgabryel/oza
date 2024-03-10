<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Repository\ApplicationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Applications extends Fixture
{
    private ApplicationRepository $applicationRepository;
    private array $applications;
    private bool $isProd;

    public function __construct(ParameterBagInterface $params, ApplicationRepository $applicationRepository)
    {
        $this->isProd = $params->get('kernel.environment') === 'prod';
        $this->setData();
        $this->applicationRepository = $applicationRepository;
    }

    private function setData(): void
    {
        if ($this->isProd) {
            $this->applications = [
                [
                    'name' => 'KKK',
                    'href' => 'https://kkk-api.gabryelkamil.pl/'
                ],
                [
                    'name' => 'Drink',
                    'href' => 'https://drink-api.gabryelkamil.pl/'
                ]
            ];
        } else {
            $this->applications = [
                [
                    'name' => 'KKK',
                    'href' => 'https://localhost.kkk/'
                ],
                [
                    'name' => 'Drink',
                    'href' => 'https://localhost.drink/'
                ]
            ];
        }
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->applications as $application) {
            $entity = $this->create($application);
            if ($entity === null) {
                continue;
            }
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function create(array $data): ?Application
    {
        $application = $this->applicationRepository->findOneBy(['name' => $data['name']]);
        if ($application !== null) {
            return null;
        }
        $application = new Application();
        $application->setName($data['name']);
        $application->setHref($data['href']);

        return $application;
    }
}

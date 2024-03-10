<?php

namespace App\Command;

use App\Entity\QuickList\QuickList;
use App\Repository\QuickList\ListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteQuickListsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private ListRepository $listRepository;

    public function __construct(ListRepository $listRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->listRepository = $listRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setName('deleteQuickLists');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lists = $this->listRepository->getListToDelete();
        array_walk($lists, fn(QuickList $list) => $this->entityManager->remove($list));
        $this->entityManager->flush();

        return 0;
    }
}

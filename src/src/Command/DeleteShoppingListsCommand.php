<?php

namespace App\Command;

use App\Entity\ShoppingList\ShoppingList;
use App\Repository\ShoppingList\ListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteShoppingListsCommand extends Command
{
    private const NAME = 'deleteShoppingLists';
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
        $this->setName(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lists = $this->listRepository->getListToDelete();
        array_walk($lists, fn(ShoppingList $list) => $this->entityManager->remove($list));
        $this->entityManager->flush();

        return 0;
    }
}

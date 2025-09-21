<?php

namespace App\Vending\Infrastructure\Repository;

use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use App\Vending\Infrastructure\Service\FileManagerInterface;

class SerializedVendingMachineRepository implements VendingMachineRepositoryInterface
{
    private const DATA_PATH = __DIR__ . '/../../../../var/data/vending-machine.json';

    public function __construct(private FileManagerInterface $fileManager)
    {
    }

    public function create(): VendingMachine
    {
        $vendingMachine = VendingMachine::create();

        $this->persist($vendingMachine);

        return $vendingMachine;
    }

    public function get(): VendingMachine
    {
        $data = $this->fileManager->getFileContent(self::DATA_PATH);

        if (empty($data)) {
            return $this->create();
        }

        $vendingMachine = unserialize($data);

        return $vendingMachine;
    }

    public function persist(VendingMachine $vendingMachine): void
    {
        $data = serialize($vendingMachine);

        $this->fileManager->saveFileContent(self::DATA_PATH, $data);
    }
}

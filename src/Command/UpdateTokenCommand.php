<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use App\Entity\Clients;
use App\Repository\ClientsRepository;
use \DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class UpdateTokenCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('command:update_token')
            ->setDescription('This command will update fields token and token_update to all active clients')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $clients = $this->em->getRepository(Clients::class)->findAll();

        foreach ($clients as $editClient) {
            if ($editClient -> getActive()) {

                $date = new DateTime();
                $token_update = $date;
                $editClient -> setTokenUpdate($token_update);

                $hash_login = (string)($editClient -> getLogin());
                $hash_salt = (string)($token_update -> format('Y-m-d H:i:s'));
                $hash = $hash_login . $hash_salt;
                $editClient -> setToken(hash('ripemd160', $hash));

                $this->em->flush();
            }
        }

        return 1;
    }
}
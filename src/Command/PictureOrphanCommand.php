<?php

namespace App\Command;

use App\Repository\PictureRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:picture:orphan',
    description: 'Delete picture qui son orpheline',
)]
class PictureOrphanCommand extends Command
{
    public function __construct(private EntityManagerInterface $em, private PictureRepository $pictureRepository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('days', InputArgument::OPTIONAL, 'Nombre de jours (si vide la valeur sera a 1)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Suppression des photos sans annonces');
        $days = $input->getArgument('days');

        if (!$days) {
            $days = $io->ask('Combien de jour (vide = 1)');
        }
        if (empty($days)) {
            $days = 1;
        }

        $datetime = new DateTime('now');
        $datetime->modify('-' . $days . ' day');


        /** @var Picture $picture */
        foreach ($this->pictureRepository->findAll() as $picture) {
            if (empty($picture->getAdvert()) && $picture->getCreatedAt() <= $datetime) {
                $this->em->remove($picture);
            }
        }
        $this->em->flush();

        $io->success('suppression effectué');
        return Command::SUCCESS;
    }
}

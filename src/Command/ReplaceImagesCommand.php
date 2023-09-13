<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\OmdbApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReplaceImagesCommand extends Command
{
    // c'est la syntaxe qui lance la commande php/bin console ...
    protected static $defaultName = 'app:replace-images';
    // le commentaire qui va décrire la commande
    protected static $defaultDescription = 'Replace all images by images from OMDBAPI';

    private $omdbApi;
    private $movieRepository;

    public function __construct(OmdbApiService $omdbApi, MovieRepository $movieRepository)
    {
        $this->omdbApi = $omdbApi;
        $this->movieRepository = $movieRepository;

        // le constructeur écrase celui du parent, il est donc necessaire de l'appeller manuellement
        parent::__construct();
    }

    // permet de configurer la commande
    protected function configure(): void
    {
       
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //  récupérer les films
        $movies = $this->movieRepository->findAll();
        //  boucler dessus
        foreach ($movies as $movie) {
            //  récupérer l'image sur l'api
            $poster = $this->omdbApi->getPoster($movie);
            //  remplacer l'image par celle de l'api ou un placeholder si elle n'existe pas
            if($poster){
                $movie->setPoster($poster);
            }else{
                // si l'api ne trouve pas de poster, je mets une image par défaut
                $movie->setPoster("https://newsactual.fr/wp-content/uploads/2020/05/josephine-ange-gardien.jpg");
            }
        }
        // je flush ma modif
        $this->movieRepository->add($movie,true);

        $io->success('Bravo ! All images are replaced');

        return Command::SUCCESS;
    }
}

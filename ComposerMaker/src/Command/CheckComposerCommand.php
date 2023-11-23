<?php


namespace App\Command;

use App\Service\CustomLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Console\Input\InputArgument;

class CheckComposerCommand extends Command
{
    private $translator;

    private $customLogger;
    protected static $defaultName = 'app:check-composer';


    public function __construct(CustomLogger $customLogger, TranslatorInterface $translator)
    {
        $this->customLogger = $customLogger;
        $this->translator = $translator;

        parent::__construct();
    }


    
    protected function configure()
    {
        $this->setName('app:check-composer')
            ->setDescription('Check composer.json file')
            ->addArgument('lang', InputArgument::REQUIRED, 'ISO code for language');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lang = $input->getArgument('lang');
        $errorMessage = $this->translator->trans('Une erreur s\'est produite lors de la vérification.', [], null, $lang);

        $this->customLogger->logError($errorMessage);

        $this->customLogger->logError('Une erreur s\'est produite lors de la vérification.');

        $composerData = json_decode(file_get_contents('composer.json'), true);

        $output->writeln('Vérification du fichier composer.json :');   
        $output->writeln('Nom : ' . $composerData['name']);
        $output->writeln('Description : ' . $composerData['description']);

        $output->writeln('Require :');
        foreach ($composerData['require'] as $package => $version) {
            $output->writeln("- $package : $version");
        }

         if (!isset($composerData['keywords'])) {
        $this->customLogger->logError('La clé "keywords" est manquante dans le composer.json.');
    } else {
        $output->writeln('Keywords : ' . implode(', ', $composerData['keywords']));
    }


        return Command::SUCCESS;
    }
}


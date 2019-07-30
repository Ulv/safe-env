<?php

namespace App\Lead\SafeEnvBundle\Command;

use App\Lead\SafeEnvBundle\Encryptor\EncryptorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class EncryptStringCommand
 * @package App\Lead\SafeEnvBundle\Command
 */
class EncryptStringCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'lead:encrypt:string';

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @param EncryptorInterface $encryptor
     */
    public function setEncryptor(EncryptorInterface $encryptor): void
    {
        $this->encryptor = $encryptor;
    }

    protected function configure()
    {
        $this
            ->addArgument('string', InputArgument::OPTIONAL, 'String to encrypt')
            ->setDescription('Encrypts credentials string with configured key')
            ->setHelp('This command encrypts credentials string with key set in config (param "secret"). Encrypted text must be stored in external key file outside web root');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        if (!$input->getArgument('string')) {
            $helper = $this->getQuestionHelper();
            $question = new Question('Please enter the string to encrypt: ', '');
            $question->setNormalizer(function ($value) {
                return $value ? trim($value) : '';
            });
            $input->setArgument('string', $helper->ask($input, $output, $question));
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encryptedString = $this->encryptor->setString($input->getArgument('string'))->encrypt();
        $output->writeln('The encrypted string is:');
        $output->writeln($encryptedString);
    }

    /**
     * @return QuestionHelper
     */
    protected function getQuestionHelper()
    {
        $question = $this->getHelperSet()->get('question');
        if (!$question || get_class($question) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper') {
            $this->getHelperSet()->set($question = new QuestionHelper());
        }
        return $question;
    }
}
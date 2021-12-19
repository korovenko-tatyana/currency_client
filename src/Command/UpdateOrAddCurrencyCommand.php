<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class UpdateOrAddCurrencyCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('command:update_currency')
            ->setDescription('This command will read file with currency and will update or add new currency to the database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = 'http://www.cbr.ru/scripts/XML_daily.asp';
        $fileContents = file_get_contents($url);
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $jsonFromXml = json_encode($simpleXml);
        $arrayFromJson = json_decode($jsonFromXml,TRUE);

        foreach ($arrayFromJson['Valute'] as $value){

            $currency = $this->em->getRepository(Currency::class)->findOneBy(['CharCode' => $value['CharCode']]);

            if ($currency){
                $currencyValue = str_replace(',', ".", $value['Value']);
                $currency -> setValue((float)$currencyValue);
                $this->em->flush();
            } else {
                $currency = new Currency();
                $currency -> setValuteID($value['@attributes']['ID']);
                $currency -> setNumCode($value['NumCode']);
                $currency -> setCharCode($value['CharCode']);
                $currency -> setNominal($value['Nominal']);
                $currency -> setName($value['Name']);

                $currencyValue = str_replace(',', ".", $value['Value']);
                $currency -> setValue((float)$currencyValue);

                $this->em->persist($currency);
                $this->em->flush();
            }
        }

        return 1;
    }
}
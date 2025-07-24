<?php

namespace App\Service;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ExchangeRateFetcher
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly EntityManagerInterface $em
    ) {}

    public function updateRates(): void
    {
        $response = $this->httpClient->request('GET', 'https://api.nbp.pl/api/exchangerates/tables/C/today/?format=json');
        $data = $response->toArray()[0]['rates'];
        $now = new \DateTimeImmutable();

        foreach ($data as $rate) {
            $currency = $this->em->getRepository(Currency::class)->findOneBy(['code' => $rate['code']]) ?? new Currency();
            $currency->setCode($rate['code']);
            $currency->setName($rate['currency']);
            $currency->setBid($rate['bid']);
            $currency->setAsk($rate['ask']);
            $currency->setUpdatedAt($now);
            $this->em->persist($currency);
        }

        $this->em->flush();
    }
}

?>
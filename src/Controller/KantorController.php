<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Currency;
use App\Form\CurrencyExchangeType;
use App\Service\ExchangeRateFetcher;

final class KantorController extends AbstractController
{
    #[Route('/kantor', name: 'app_kantor')]
    public function index(Request $request, EntityManagerInterface $em, ExchangeRateFetcher $fetcher): Response
    {   

        $today = (new \DateTimeImmutable())->format('Y-m-d');
        $updatedAt = $em->getRepository(Currency::class)
                        ->createQueryBuilder('c')
                        ->select('MAX(c.updatedAt)')
                        ->getQuery()
                        ->getSingleScalarResult();

        if (!$updatedAt || (new \DateTimeImmutable($updatedAt))->format('Y-m-d') < $today) {
            $fetcher->updateRates();
        }

        $form = $this->createForm(CurrencyExchangeType::class);
        $form->handleRequest($request);

        $result = null;

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();   
                $amount = $data['amount'];
                /** @var Currency $currency */
                $currency = $data['currency'];
                $result = $amount / $currency->getAsk(); 

                $this->addFlash('result', [
                    'amount' => $result,
                    'code' => $currency->getCode(),
                ]);

                return $this->redirectToRoute('app_kantor');
            } else {
                $this->addFlash('error', 'Wprowadź poprawną kwotę w formacie liczbowym (np. 100.00)');
                return $this->redirectToRoute('app_kantor');
            }
        }

        return $this->render('kantor/index.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }
}

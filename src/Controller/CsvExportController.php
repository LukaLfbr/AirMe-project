<?php

namespace App\Controller;

use App\Entity\Events;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CsvExportController extends AbstractController
{
    #[Route('/csv/export/{id}', name: 'export_event_csv')]
    public function export(Events $event): Response
    {
        $csvFilePath = $this->getParameter('kernel.project_dir') . '/public/export/export.csv';

        $csv = Writer::createFromPath($csvFilePath, 'a+');

        if (filesize($csvFilePath) === 0) {
            $csv->insertOne(['id', 'name', 'date', 'location']);
        }

        $csv->insertOne([
            $event->getId(),
            $event->getName(),
            $event->getDate()->format('Y-m-d H:i:s'), // Format de la date
            $event->getLocation(),
        ]);

        return new Response('Événement exporté avec succès.', 200);
    }
}

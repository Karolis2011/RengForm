<?php

namespace App\Controller\Admin;

use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegistrationController
 */
class RegistrationController extends Controller
{
    /**
     * @var WorkshopRepository
     */
    private $workshopRepository;

    /**
     * RegistrationController constructor.
     * @param WorkshopRepository $workshopRepository
     */
    public function __construct(WorkshopRepository $workshopRepository)
    {
        $this->workshopRepository = $workshopRepository;
    }

    /**
     * @Route("/workshop/{workshopId}/registrations", name="workshop_registrations")
     * @param $workshopId
     * @return Response
     * @throws \Exception
     */
    public function index($workshopId)
    {
        /** @var Workshop $workshop */
        $workshop = $this->workshopRepository->find($workshopId);

        if ($workshop === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $workshopId));
        }

        return $this->render(
            'Admin/Registration/index.html.twig',
            [
                'workshop' => $workshop,
            ]
        );
    }
}

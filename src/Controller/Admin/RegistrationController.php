<?php

namespace App\Controller\Admin;

use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param $workshopId
     * @return Response
     */
    public function index($workshopId)
    {
        $workshop = $this->workshopRepository->find($workshopId);

        if ($workshop === null) {
            throw new NotFoundHttpException(sprintf('Workshop by id %s not found', $workshopId));
            //TODO: Log
        }

        return $this->render(
            'Admin/Registration/index.html.twig',
            [
                'workshop' => $workshop,
            ]
        );
    }
}

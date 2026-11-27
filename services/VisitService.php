<?php

class VisitService
{
    private VisitRepository $visitRepository;

    public function __construct(
        VisitRepository $visitRepository
    ) {
        $this->visitRepository = $visitRepository;
    }

    public function add(Visit $visit): array {
        return $this->visitRepository
                    ->add($visit);
    }

    public function addMultiVisits(array $visits): array {
        foreach ($visits as $visit) {
            if (!$visit instanceof Visit) {
                return Response::error('El arreglo contiene elementos inválidos.');
            }

            $response = $this->add($visit);

            if (!$response['success']) {
                return $response;
            }
        }

        return Response::success('Visitantes agregados correctamente.');
    }
}
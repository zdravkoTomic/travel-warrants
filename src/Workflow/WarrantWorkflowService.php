<?php

namespace App\Workflow;

use App\Entity\Warrant;
use App\Exception\RecordNotFoundException;
use App\Helper\TypeHelper;
use App\Repository\Codebook\App\WarrantStatusRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Workflow\Marking;
use LogicException;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

class WarrantWorkflowService implements MarkingStoreInterface
{
    private WarrantStatusRepository $warrantStatusRepository;

    public function __construct(WarrantStatusRepository $warrantStatusRepository)
    {
        $this->warrantStatusRepository = $warrantStatusRepository;
    }

    public function getMarking(object $subject): Marking
    {
        if ($subject instanceof Warrant) {
            $statusCode = $subject->getStatus()?->getCode();
        } else {
            throw new LogicException(
                sprintf(
                    'Unable to resolve marking for warrant workflow. Expected subject of type object with status property or %s, got %s.',
                    Warrant::class,
                    TypeHelper::getType($subject)
                )
            );
        }

        return new Marking([$statusCode => 1]);
    }

    /**
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function setMarking(object $subject, Marking $marking, array $context = [])
    {
        $markingCode = $marking->getPlaces() ? key($marking->getPlaces()) : null;

        if (null === $markingCode) {
            throw new LogicException('Unable to resolve marking code in setMarking method. Null given.');
        }

        if (!$subject instanceof Warrant) {
            throw new LogicException(
                sprintf(
                    'setMarking can set marking code only on object of type %s',
                    Warrant::class
                )
            );
        }

        $subject->setStatus($this->warrantStatusRepository->findExistingByCode($markingCode));
    }
}
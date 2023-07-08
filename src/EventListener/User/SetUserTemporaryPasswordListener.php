<?php

namespace App\EventListener\User;

use App\Entity\Employee;
use App\Service\MailerService;
use App\Service\User\PasswordHandlerService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

#[AsDoctrineListener(event: Events::onFlush)]
class SetUserTemporaryPasswordListener
{
    private PasswordHandlerService $passwordHandlerService;
    private MailerService $mailerService;
    private ParameterBagInterface $parameterBag;
    private RequestStack $request;

    public function __construct(
        PasswordHandlerService $passwordHandlerService,
        MailerService          $mailerService,
        ParameterBagInterface  $parameterBag,
        RequestStack           $request
    ) {
        $this->passwordHandlerService = $passwordHandlerService;
        $this->mailerService          = $mailerService;
        $this->parameterBag           = $parameterBag;
        $this->request                = $request;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em  = $eventArgs->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Employee) {
                $tmpPlainPassword = $this->passwordHandlerService->getTemporaryPlainTextPassword();

                $password =
                    $this->passwordHandlerService->generateUserTemporaryHashedPassword($entity, $tmpPlainPassword);
                $entity->setPassword($password);

                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);
                $uow->computeChangeSets();

                // TODO replace subject and text with translations once translations are added to project
                $mail = (new Email())
                    ->from($this->parameterBag->get('app_name'))
                    ->to($entity->getEmail())
                    ->subject('Access to company travel warrants app')
                    ->text(
                        sprintf(
                            'An account has been created in your name for travel warrants application by an administrator. 
                    Your temporary password is: %s, you will be prompted to change password on your first login.
                    You can access application via this link: %s',
                            $tmpPlainPassword,
                            $this->request->getCurrentRequest()->server->get('HTTP_REFERER')
                        )
                    );

                $this->mailerService->sendMail($mail);
            }
        }
    }
}
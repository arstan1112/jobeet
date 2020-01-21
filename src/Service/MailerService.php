<?php


namespace App\Service;

use App\Entity\Affiliates;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Templating\EngineInterface;

class MailerService
{
    /**@var MailerInterface */
    private $mailer;

    /** EngineInterface */
    private $templateEngine;

    /**
     * @param EngineInterface $templateEngine
     * @param MailerInterface $mailer
     */
    public function __construct(/*Swift_Mailer $mailer,*/ EngineInterface $templateEngine, MailerInterface $mailer)
    {
        $this->mailer=$mailer;
        $this->templateEngine=$templateEngine;
    }

    /**
     * @param Affiliates $affiliate
     *
     * @throws TransportExceptionInterface
     */
    public function sendActivationEmail(Affiliates $affiliate): void
    {
        $email = (new Email())
            ->from('jobeet@example.com')
            ->to($affiliate->getEmail())
            ->subject('Account activation')
            ->html(
                $this->templateEngine->render(
                    'emails/affiliate_activation.html.twig',
                    [
                        'token' => $affiliate->getToken(),
                    ]
                )
            );

        $this->mailer->send($email);
    }

//    public function send($message)
//    {
//        return $this->mailer->send($message);
//    }
}

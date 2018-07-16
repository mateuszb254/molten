<?php

namespace App\Service;

use App\Entity\Account;

/**
 * This class provides sending prepared emails.
 */
class Mailer
{
    /**
     * @var \Swift_Mailer $swiftMailer
     */
    protected $swiftMailer;

    /**
     * @var \Twig_Environment $twig
     */
    protected $twig;

    /**
     * Array of config data set up in service.yaml
     *
     * @var array $options
     */
    protected $options;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $swiftMailer
     * @param \Twig_Environment $twig
     * @param array $options
     */
    public function __construct(\Swift_Mailer $swiftMailer, \Twig_Environment $twig, array $options)
    {
        $this->swiftMailer = $swiftMailer;
        $this->twig = $twig;
        $this->options = $options;
    }

    /**
     * This method sends e-mail with information about reset password
     *
     * @param Account $account
     */
    public function sendResettingMessage(Account $account): void
    {
        $template = $this->twig->render('email/resetting_message.html.twig', [
            'account' => $account
        ]);

        $this->sendEmailMessage($template, $account->getEmail());
    }

    /**
     * This method finally sends email with passed message and specified email address
     *
     * @param string $renderedTemplate
     * @param string $toEmail
     */
    protected function sendEmailMessage(string $renderedTemplate, string $toEmail): void
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = array_shift($renderedLines);
        $body = implode("\n", $renderedLines);


        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom([
                $this->options['from_email'] => $this->options['from_name']
            ])
            ->setTo($toEmail)
            ->setBody($body)
            ->setContentType('text/html')
            ->setCharset('utf-8');

        $this->swiftMailer->send($message);
    }
}
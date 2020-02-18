<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Database;
use App\Event\CustomerAutoRegistered;
use App\Event\CustomerRegistered;
use App\Event\OrderPlaced;
use App\Event\CustomerResetPasswordRequested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class TransactionalEmail implements EventSubscriberInterface
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $twig;

    public static function getSubscribedEvents()
    {
        return [
            CustomerAutoRegistered::class => 'onCustomerAutoRegistered',
            CustomerRegistered::class => 'onCustomerRegistered',
            CustomerResetPasswordRequested::class => 'onCustomerResetPasswordRequested',
            OrderPlaced::class => 'onOrderPlaced',
        ];
    }

    public function __construct(Database $db, MailerInterface $mailer, Environment $twig)
    {
        $this->db = $db;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function onCustomerRegistered(CustomerRegistered $event): void
    {
        $notification = $this->db->find("select * from notification where deleted_at is null and type = 'email' and event = 'customer.registered'");

        if (!$notification) {
            return;
        }

        $customer = $event->getCustomer();

        $textTemplate = $this->twig->createTemplate($notification['content']);
        $htmlTemplate = $this->twig->createTemplate($notification['content_rich']);

        $email = (new Email())
            ->from(new Address($notification['from_email'], $notification['from_name']))
            ->to($customer->getEmail())
            ->subject($notification['title'])
            ->text($textTemplate->render(['customer' => $customer]))
            ->html($htmlTemplate->render(['customer' => $customer]));

        $this->mailer->send($email);
    }

    public function onCustomerAutoRegistered(CustomerAutoRegistered $event): void
    {
        $notification = $this->db->find("select * from notification where deleted_at is null and type = 'email' and event = 'customer.auto_registered'");

        if (!$notification) {
            return;
        }

        $customer = $event->getCustomer();

        $textTemplate = $this->twig->createTemplate($notification['content']);
        $htmlTemplate = $this->twig->createTemplate($notification['content_rich']);

        $email = (new Email())
            ->from(new Address($notification['from_email'], $notification['from_name']))
            ->to($customer->getEmail())
            ->subject($notification['title'])
            ->text($textTemplate->render(['customer' => $customer]))
            ->html($htmlTemplate->render(['customer' => $customer]));

        $this->mailer->send($email);
    }

    public function onCustomerResetPasswordRequested(CustomerResetPasswordRequested $event): void
    {
        $notification = $this->db->find("select * from notification where deleted_at is null and type = 'email' and event = 'customer.resetPasswordRequested'");

        if (!$notification) {
            return;
        }

        $customer = $event->getCustomer();
        $resetUri = $event->getResetUri();

        $textTemplate = $this->twig->createTemplate($notification['content']);
        $htmlTemplate = $this->twig->createTemplate($notification['content_rich']);

        $email = (new Email())
            ->from(new Address($notification['from_email'], $notification['from_name']))
            ->to($customer->getEmail())
            ->subject($notification['title'])
            ->text($textTemplate->render(['name' => $customer->getFirstName()]))
            ->html($htmlTemplate->render([
                'name' => $customer->getFirstName(),
                'resetUri' => $resetUri
                ]
            ));

        $this->mailer->send($email);
    }

    public function onOrderPlaced(OrderPlaced $event): void
    {
        $notification = $this->db->find("select * from notification where deleted_at is null and type = 'email' and event = 'order.placed'");

        if (!$notification) {
            return;
        }

        $order = $event->getOrder();

        $textTemplate = $this->twig->createTemplate($notification['content']);
        $htmlTemplate = $this->twig->createTemplate($notification['content_rich']);

        $email = (new Email())
            ->from(new Address($notification['from_email'], $notification['from_name']))
            ->to($order->getCustomer()->getEmail())
            ->subject($notification['title'])
            ->text($textTemplate->render(['customer' => $order->getCustomer()]))
            ->html($htmlTemplate->render(['customer' => $order->getCustomer()]));

        $this->mailer->send($email);
    }
}

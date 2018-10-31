<?php

namespace App\DataFixtures;

use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->loadTickets($manager);
    }

    private function loadTickets(ObjectManager $manager)
    {
        foreach ($this->getTicketsData() as $ticketData) {
            $ticket = new Ticket();
            $ticket->setTitle($ticketData['title']);
            $ticket->setContent($ticketData['content']);
            $ticket->setCreatedAt(new \DateTime());
            $ticket->setStatus($ticketData['status']);

            $ticket->setAuthor($ticketData['author']);
            $ticket->setCategory($ticketData['category']);

            $manager->persist($ticket);
        }

        $manager->flush();
    }

    private function getTicketsData(): array
    {
        return [
            [
                'title' => 'Problem z płatnością PayPal',
                'content' => "Witam,\nKupiłem pakiet za 200 złotych i nie doszły mi monety.\n\nNumer identyfikacyjny transakcji: 9KN79****L379**3M.",
                'status' => Ticket::STATUS_OPEN,
                'author' => $this->getReference(AccountFixtures::USER_REFERENCE_NAME),
                'category' => $this->getReference(TicketCategoryFixtures::PAYMENTS_REFERENCE)
            ],
            [
                'title' => 'Błąd na stronie.',
                'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam congue a eros eget cursus. In vel lorem mi. Nullam mauris nisl, tristique et elementum at, faucibus molestie risus. Praesent et nisl et ex tincidunt imperdiet. Maecenas placerat, ipsum id ullamcorper pulvinar, nisi nisi pulvinar dui, nec tempor leo nunc eu libero. Suspendisse id viverra est, a rutrum leo. Cras sodales enim et nulla porttitor mollis. ",
                'status' => Ticket::STATUS_OPEN,
                'author' => $this->getReference(AccountFixtures::USER_BANNED_REFERENCE_NAME),
                'category' => $this->getReference(TicketCategoryFixtures::SITE_BUG_REFERENCE)
            ],
            [
                'title' => 'Tresc zgloszenia',
                'content' => "Tresc zgloszenie, ktore zostalo zamkniete przez administratora.",
                'status' => Ticket::STATUS_CLOSED,
                'author' => $this->getReference(AccountFixtures::USER_REFERENCE_NAME),
                'category' => $this->getReference(TicketCategoryFixtures::PAYMENTS_REFERENCE)
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class, TicketCategoryFixtures::class
        ];
    }
}
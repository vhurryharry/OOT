<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;

class CartRepository
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(Database $db, RequestStack $requestStack)
    {
        $this->db = $db;
        $this->requestStack = $requestStack;
    }

    public function add(int $courseOptionId, ?string $giftFor = null): void
    {
        $this->db->execute(
            'insert into course_cart (id, cart_id, course_option_id, gift_for) values (?, ?, ?, ?)',
            [
                Uuid::uuid4()->toString(),
                $this->getCartId(),
                $courseOptionId,
                $giftFor,
            ]
        );
    }

    public function remove(UuidInterface $item): void
    {
        $this->db->execute(
            'delete from course_cart where id = ?',
            [
                $item->toString(),
            ]
        );
    }

    public function get(): array
    {
        return $this->db->findAll(
            'select co.title, co.price, co.location, co.dates, c.thumbnail, ca.id, ca.gift_for from course_cart as ca join course_option as co on ca.course_option_id = co.id join course as c on co.course = c.id where ca.cart_id = ?',
            [
                $this->getCartId(),
            ]
        );
    }

    protected function getCartId(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new RuntimeException('No request available.');
        }

        $cartId = $request->cookies->get('uid', null);

        if (!$cartId) {
            throw new RuntimeException('No cart id available.');
        }
    }
}

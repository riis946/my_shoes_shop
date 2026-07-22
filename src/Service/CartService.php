<?php


namespace App\Service;

use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductVariantRepository $variantRepository
    ) {}

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    public function add(int $variantId, int $quantity = 1): void
    {
        $cart = $this->getSession()->get('cart', []);

        if (!empty($cart[$variantId])) {
            $cart[$variantId] += $quantity;
        } else {
            $cart[$variantId] = $quantity;
        }

        $this->getSession()->set('cart', $cart);
    }

    public function remove(int $variantId): void
    {
        $cart = $this->getSession()->get('cart', []);

        if (!empty($cart[$variantId])) {
            unset($cart[$variantId]);
        }

        $this->getSession()->set('cart', $cart);
    }

    public function getFullCart(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $fullCart = [];

        foreach ($cart as $variantId => $quantity) {
            $variant = $this->variantRepository->find($variantId);
            if ($variant) {
                $fullCart[] = [
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'subtotal' => $variant->getProduct()->getPrice() * $quantity
                ];
            }
        }

        return $fullCart;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getFullCart() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}

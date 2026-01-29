<?php declare(strict_types=1);

namespace App\Payroll\Domain\Shared\Value;

class Money
{
    private function __construct(
        private readonly int $amountInCents,
    ) {
    }

    public static function create(float|string $amount): static
    {
        return new static((int) (round(100 * $amount)));
    }

    public function getAmountInCents(): int
    {
        return $this->amountInCents;
    }

    public function add(Money $money): Money
    {
        return new Money($this->amountInCents + $money->amountInCents);
    }

    public function subtract(Money $money): Money
    {
        return new Money($this->amountInCents - $money->amountInCents);
    }

    public function increaseByPercent(float $percent): Money
    {
        $multiplier = (100 + $percent) / 100;
        $newAmount = (int) round($this->amountInCents * $multiplier);

        return new Money($newAmount);
    }
}

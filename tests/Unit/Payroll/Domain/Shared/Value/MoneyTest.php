<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Shared\Value;

use App\Payroll\Domain\Shared\Value\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    #[DataProvider('createCasesProvider')]
    public function testCreate(float|string $amount, int $expectedAmountInCents): void
    {
        // When
        $money = Money::create($amount);

        // Then
        $this->assertSame($expectedAmountInCents, $money->getAmountInCents());
    }

    public static function createCasesProvider(): array
    {
        return [
            'integer dollars' => [100, 10000],
            'decimal dollars' => [123.45, 12345],
            'string amount' => ['50.25', 5025],
            'round up half' => [10.005, 1001], // 10.005 * 100 => 1000.5 → 1001
            'round down' => [10.004, 1000],    // 10.004 * 100 => 1000.4 → 1000
            'thousands separator' => [1234.56, 123456],
        ];
    }

    public function testAdd(): void
    {
        // Given
        $a = Money::create(100.00);
        $b = Money::create(25.50);

        // When
        $result = $a->add($b);

        // Then
        $this->assertSame(12550, $result->getAmountInCents());
    }

    public function testSubtract(): void
    {
        // Given
        $a = Money::create(100.00);
        $b = Money::create(25.50);

        // When
        $result = $a->subtract($b);

        // Then
        $this->assertSame(7450, $result->getAmountInCents());
    }

    public function testIncreaseByPercent(): void
    {
        // Given
        $money = Money::create(10.01);

        // When
        $result = $money->increaseByPercent(5.0);

        // Then
        $this->assertSame(1051, $result->getAmountInCents());
    }
}

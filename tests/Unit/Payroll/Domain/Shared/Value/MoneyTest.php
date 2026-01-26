<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Shared\Value;

use App\Payroll\Domain\Shared\Value\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    #[DataProvider('createCasesProvider')]
    public function testCreate(float|string $amount, string $expectedString): void
    {
        // When
        $money = Money::create($amount);

        // Then
        $this->assertSame($expectedString, $money->toString());
    }

    public static function createCasesProvider(): array
    {
        return [
            'integer dollars' => [100, '$100.00'],
            'decimal dollars' => [123.45, '$123.45'],
            'string amount' => ['50.25', '$50.25'],
            'round up half' => [10.005, '$10.01'], // 10.005 * 100 => 1000.5 → 1001
            'round down' => [10.004, '$10.00'],    // 10.004 * 100 => 1000.4 → 1000
            'thousands separator' => [1234.56, '$1,234.56'],
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
        $this->assertSame('$125.50', $result->toString());
    }

    public function testSubtract(): void
    {
        // Given
        $a = Money::create(100.00);
        $b = Money::create(25.50);

        // When
        $result = $a->subtract($b);

        // Then
        $this->assertSame('$74.50', $result->toString());
    }

    public function testIncreaseByPercent(): void
    {
        // Given
        $money = Money::create(10.01);

        // When
        $result = $money->increaseByPercent(5.0);

        // Then
        $this->assertSame('$10.51', $result->toString());
    }
}

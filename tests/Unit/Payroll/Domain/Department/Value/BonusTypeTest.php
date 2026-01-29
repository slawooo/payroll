<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Department\Value;

use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Domain\Department\Value\BonusType;
use PHPUnit\Framework\TestCase;

final class BonusTypeTest extends TestCase
{
    public function testCreateFixed(): void
    {
        // Given
        $value = BonusType::FIXED;

        // When
        $type = BonusType::create($value);

        // Then
        $this->assertTrue($type->isFixed());
        $this->assertFalse($type->isPercentage());
    }

    public function testCreatePercentage(): void
    {
        // Given
        $value = BonusType::PERCENTAGE;

        // When
        $type = BonusType::create($value);

        // Then
        $this->assertTrue($type->isPercentage());
        $this->assertFalse($type->isFixed());
    }

    public function testCreateThrowsExceptionForUnknownValue(): void
    {
        // Given
        $value = 'unknown';

        // Then
        $this->expectException(DepartmentException::class);
        $this->expectExceptionMessage('Unknown bonus type "unknown"');

        // When
        BonusType::create($value);
    }
}

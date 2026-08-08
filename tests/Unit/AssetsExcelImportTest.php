<?php

namespace Tests\Unit;

use App\Imports\AssetsExcelImport;
use Tests\TestCase;

class AssetsExcelImportTest extends TestCase
{
    public function test_date_style_procurement_years_are_normalized(): void
    {
        $import = new AssetsExcelImport(dryRun: true, codeSeed: 1);
        $method = new \ReflectionMethod(AssetsExcelImport::class, 'normalizeProcurementYear');
        $method->setAccessible(true);

        $errors = [];
        $this->assertSame('2014-01-01', $method->invokeArgs($import, ['2014', &$errors]));
        $this->assertSame([], $errors);

        $errors = [];
        $this->assertSame('2018-01-01', $method->invokeArgs($import, ['15/05/2018', &$errors]));
        $this->assertSame([], $errors);

        $errors = [];
        $this->assertSame('2018-01-01', $method->invokeArgs($import, ['2018-05-15', &$errors]));
        $this->assertSame([], $errors);

        $errors = [];
        $this->assertNull($method->invokeArgs($import, ['abc', &$errors]));
        $this->assertSame([], $errors);
    }
}

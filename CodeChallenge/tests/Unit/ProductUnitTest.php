<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;

class ProductUnitTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Set up the test
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->fileFullPath = __DIR__.'\Files\products.csv';
    }
    ///Run error when importing without file
    public function test_importing_without_a_file()
    {
        $this->expectException(RuntimeException::class);
        $this->artisan('import:products');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function test_cancel_import_products_command()
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');
        //Storage::disk('public')->has('CSV/products.csv');
        $this->artisan('import:products '.$this->fileFullPath)
            ->expectsConfirmation('Do you really wish to import products?', 'no')
            ->expectsOutput('Import cancelled')
            ->doesntExpectOutput('Products imported')
            ->assertExitCode(1);
    }
}

<?php

namespace Tests\Feature;

use App\Http\Controllers\ProductController;
use App\Imports\ProductImport;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Exception\RuntimeException;
use Tests\TestCase;

class ImportProductTest extends TestCase
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

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function user_can_import_products()
    {  $this->withoutExceptionHandling();
        Excel::fake();
     //   dd($this->fileFullPath);
        $this->artisan('import:products  '.$this->fileFullPath);
        Excel::assertImported($this->fileFullPath);
    }
    /**
     * @test
     * @group neat
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function queue_the_product_import()
    {
        $this->withoutExceptionHandling();
        Excel::fake();

        $this->artisan('import:products  '.$this->fileFullPath);
        Excel::assertQueued($this->fileFullPath, function(ProductImport $import) {
            $this->assertTrue(true);
        });
        Excel::assertImported($this->fileFullPath);
    }


}

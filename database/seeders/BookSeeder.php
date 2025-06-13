<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Pastikan direktori ada
        Storage::disk('public')->makeDirectory('book_photos');
        Storage::disk('public')->makeDirectory('book_pdfs');

        foreach (range(1, 25) as $i) {
            // Simulasi file dummy gambar dan PDF lokal
            $photoFileName = "book_{$i}.jpg";
            $pdfFileName = "book_{$i}.pdf";

            // Isi dummy file
            Storage::disk('public')->put("book_photos/{$photoFileName}", $this->dummyImage());
            Storage::disk('public')->put("book_pdfs/{$pdfFileName}", '%PDF-1.4 Dummy PDF file for testing');

            Book::create([
                'judul'     => $faker->sentence(3),
                'penerbit'  => $faker->company,
                'dimensi'   => $faker->randomElement(['14x20cm', '15x23cm', '13x19cm']),
                'stock'     => $faker->numberBetween(1, 50),
                'photo'     => "book_photos/{$photoFileName}",
                'attachment'=> "book_pdfs/{$pdfFileName}",
            ]);
        }
    }

    // Fungsi dummy image PNG 1x1 pixel transparan
    protected function dummyImage(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII='
        );
    }
}

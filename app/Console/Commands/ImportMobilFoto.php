<?php

namespace App\Console\Commands;

use App\Models\Mobil;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportMobilFoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mobil:import-foto {file : Path to CSV file (relative to project root)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import/update car photos from CSV file. CSV format: id,foto_url';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = $this->argument('file');

        // Validasi file ada
        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        // Buka dan baca CSV
        $file = fopen($filePath, 'r');
        if (!$file) {
            $this->error("Tidak dapat membuka file: {$filePath}");
            return 1;
        }

        // Lewati header
        fgetcsv($file);

        $updated = 0;
        $errors = 0;

        // Progress bar
        $this->output->progressStart();

        while (($row = fgetcsv($file)) !== false) {
            $this->output->progressAdvance();

            if (count($row) < 2) {
                $errors++;
                continue;
            }

            $id = trim($row[0]);
            $fotoUrl = trim($row[1]);

            // Validasi ID adalah angka
            if (!is_numeric($id)) {
                $errors++;
                continue;
            }

            // Update mobil
            $mobil = Mobil::find($id);
            if ($mobil) {
                $mobil->update(['foto' => $fotoUrl]);
                $updated++;
            } else {
                $errors++;
            }
        }

        fclose($file);
        $this->output->progressFinish();

        // Summary
        $this->info("");
        $this->info("✓ Import selesai!");
        $this->line("  - Updated: <info>{$updated}</info>");
        $this->line("  - Errors: <comment>{$errors}</comment>");
        $this->line("  - Total: <info>" . ($updated + $errors) . "</info>");

        return 0;
    }
}

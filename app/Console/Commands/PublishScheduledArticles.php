<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mempublikasikan artikel yang telah melewati jadwal publikasi (WIB)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();
        $this->info("Menjalankan pengecekan artikel terjadwal pada: {$now->toDateTimeString()} ({$now->timezoneName})");

        $articles = Article::where('status', 'scheduled')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->get();

        if ($articles->isEmpty()) {
            $this->info('Tidak ada artikel terjadwal yang siap dipublikasikan.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($articles as $article) {
            $article->update([
                'status' => 'published',
            ]);
            $this->line("- [ID: {$article->id}] '{$article->judul}' berhasil dipublikasikan.");
            $count++;
        }

        $this->info("Total {$count} artikel terjadwal berhasil diubah menjadi published.");
        return Command::SUCCESS;
    }
}

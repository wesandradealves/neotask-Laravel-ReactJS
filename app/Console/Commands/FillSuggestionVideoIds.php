<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suggestion;

class FillSuggestionVideoIds extends Command
{
    protected $signature = 'suggestions:fill-video-ids';
    protected $description = 'Extrai a ID do YouTube para cada sugestão existente e preenche o campo video_id.';

    public function handle()
    {
        $count = 0;

        foreach (Suggestion::all() as $suggestion) {
            if (!$suggestion->video_id) {
                $videoId = $this->extractYoutubeId($suggestion->youtube_link);

                if ($videoId) {
                    if (!Suggestion::where('video_id', $videoId)->exists()) {
                        $suggestion->video_id = $videoId;
                        $suggestion->save();
                        $count++;
                    } else {
                        $this->warn("Duplicado ignorado: {$suggestion->youtube_link}");
                        $suggestion->delete(); 
                    }
                }
            }
        }

        $this->info("Atualizados $count registros com video_id.");
    }

    private function extractYoutubeId($url)
    {
        $pattern = '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

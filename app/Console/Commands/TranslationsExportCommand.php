<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TranslationsExportCommand extends Command
{
    protected $signature = 'translations:export';

    protected $description = 'Export rows from the translations table into PHP lang files and Vue JSON files.';

    public function handle(): int
    {
        $rows = Translation::query()->orderBy('locale')->orderBy('group')->orderBy('key')->get();

        if ($rows->isEmpty()) {
            $this->warn('No translations found in database.');

            return self::SUCCESS;
        }

        $byLocaleGroup = [];

        foreach ($rows as $row) {
            $byLocaleGroup[$row->locale][$row->group][$row->key] = $row->value;
        }

        foreach ($byLocaleGroup as $locale => $groups) {
            foreach ($groups as $group => $flat) {
                $nested = $this->dotToNested($flat);
                $php = "<?php\n\ndeclare(strict_types=1);\n\nreturn ".var_export($nested, true).";\n";
                $dir = lang_path($locale);
                File::ensureDirectoryExists($dir);
                File::put($dir.'/'.$group.'.php', $php);
            }

            $vueFlat = [];
            foreach ($groups as $group => $keys) {
                foreach ($keys as $key => $value) {
                    $vueFlat[$group.'.'.$key] = $value;
                }
            }

            $vueDir = resource_path('js/lang');
            File::ensureDirectoryExists($vueDir);
            File::put($vueDir.'/'.$locale.'.json', json_encode($vueFlat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n");
        }

        $this->info('Exported translations to lang/ and resources/js/lang/.');

        return self::SUCCESS;
    }

    /**
     * @param  array<string, string>  $flat  dot keys => values
     * @return array<string, mixed>
     */
    private function dotToNested(array $flat): array
    {
        $nested = [];

        foreach ($flat as $dotKey => $value) {
            $segments = explode('.', $dotKey);
            $current = &$nested;

            foreach ($segments as $i => $segment) {
                if ($i === count($segments) - 1) {
                    $current[$segment] = $value;
                } else {
                    if (! isset($current[$segment]) || ! is_array($current[$segment])) {
                        $current[$segment] = [];
                    }
                    $current = &$current[$segment];
                }
            }
        }

        return $nested;
    }
}

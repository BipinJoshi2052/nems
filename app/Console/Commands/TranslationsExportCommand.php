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
        $this->syncFromCsv();
        $this->syncToCsv();

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

            $vueData = [];
            foreach ($groups as $group => $keys) {
                foreach ($keys as $key => $value) {
                    $dotKey = $group.'.'.$key;
                    $segments = explode('.', $dotKey);
                    $current = &$vueData;
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
            }

            $vuePaths = [
                base_path('resources/tenant-admin-portal/src/locales'),
                base_path('resources/tenant-portal/src/locales'),
            ];

            foreach ($vuePaths as $dir) {
                File::ensureDirectoryExists($dir);
                File::put($dir.'/'.$locale.'.json', json_encode($vueData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n");
            }
        }

        $this->info('Exported translations to lang/ and portal locales.');

        return self::SUCCESS;
    }

    /**
     * Update ilsawn.csv with any values from the database that are currently missing in the CSV.
     */
    private function syncToCsv(): void
    {
        /** @var \ilsawn\LaravelIlsawn\LaravelIlsawn $ilsawn */
        $ilsawn = app(\ilsawn\LaravelIlsawn\LaravelIlsawn::class);
        $csvRecords = $ilsawn->loadCsv();
        $locales = (array) config('ilsawn.locales', ['en', 'ne']);

        $dbTranslations = \App\Models\Translation::all();
        foreach ($dbTranslations as $translation) {
            $fullKey = $translation->group === 'messages' ? $translation->key : $translation->group.'.'.$translation->key;

            $found = false;
            foreach ($csvRecords as &$row) {
                if ($row['key'] === $fullKey) {
                    if (empty($row[$translation->locale])) {
                        $row[$translation->locale] = $translation->value;
                    }
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $newRow = ['key' => $fullKey];
                foreach ($locales as $loc) {
                    $newRow[$loc] = ($translation->locale === $loc) ? $translation->value : '';
                }
                $csvRecords[] = $newRow;
            }
        }

        $ilsawn->saveCsv($csvRecords);
    }

    /**
     * Parse ilsawn.csv and update/create translations in the database.
     */
    private function syncFromCsv(): void
    {
        $path = base_path('lang/ilsawn.csv');
        if (! File::exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            return;
        }

        $header = array_shift($lines);
        $locales = explode(';', $header);
        array_shift($locales); // Remove 'key'

        foreach ($lines as $line) {
            $data = str_getcsv($line, ';');
            if (count($data) < 2) {
                continue;
            }

            $fullKey = $data[0];
            $values = array_slice($data, 1);

            if (str_contains($fullKey, '.')) {
                $parts = explode('.', $fullKey, 2);
                $group = $parts[0];
                $key = $parts[1];
            } else {
                $group = 'messages';
                $key = $fullKey;
            }

            foreach ($locales as $index => $locale) {
                $value = $values[$index] ?? '';
                if ($value === '') {
                    continue;
                }

                Translation::updateOrCreate(
                    ['locale' => $locale, 'group' => $group, 'key' => $key],
                    ['value' => $value]
                );
            }
        }
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

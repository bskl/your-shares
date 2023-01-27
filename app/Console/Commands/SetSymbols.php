<?php

namespace App\Console\Commands;

use App\Models\Symbol;
use Carbon\Carbon;
use DOMDocument;
use DOMNodeList;
use DOMXpath;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SetSymbols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yourshares:set-symbols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set symbols with from web service.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $url = 'https://www.isyatirim.com.tr/tr-tr/analiz/hisse/Sayfalar/default.aspx';

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $content = $this->parseHtml();

        if ($content) {
            $symbols = $this->parseSymbols($content);
            $this->storeSymbols($symbols);
        }
    }

    /**
     * Make a GET request to the URL.
     *
     * @return string
     */
    protected function getHtml(): string
    {
        $response = Http::retry(50, 6000, throw: false)->get($this->url);

        $body = preg_replace('/\r|\n|\t/', '', $response->body());

        return $body;
    }

    /**
     * Retrieve data from HTML body.
     *
     * @return \DOMNodeList|false
     */
    protected function parseHtml(): DOMNodeList|false
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        @$dom->loadHTML($this->getHtml());

        $xpath = new DOMXpath($dom);
        $allTr = $xpath->query("//table[contains(@data-csvname,'tumhisse')]/tbody/tr");

        if (! $allTr->length) {
            return false;
        }

        return $allTr;
    }

    /**
     * Retrieve data from HTML body.
     *
     * @param  \DOMNodeList  $content
     * @return \Illuminate\Support\Collection<int,\App\Models\Symbol>
     */
    protected function parseSymbols(DOMNodeList $content): Collection
    {
        $symbols = collect();
        $sessionTime = Carbon::now()->subMinutes(15);

        foreach ($content as $tr) {
            $symbol = [
                'code' => preg_replace('/[^a-zA-Z0-9]/', '', $tr->childNodes[1]->nodeValue),
                'title' => $tr->childNodes[1]->hasAttribute('title') ? trim($tr->childNodes[1]->getAttribute('title')) : '',
                'trend' => $this->getTrend(trim($tr->childNodes[5]->nodeValue)),
                'last_price' => $this->asDecimal(trim($tr->childNodes[3]->nodeValue)),
                'rate_of_change' => trim($tr->childNodes[5]->nodeValue),
                'session_time' => $sessionTime,
            ];

            $symbols->push($symbol);
        }

        return $symbols;
    }

    /**
     * Get trend from given value.
     *
     * @param  string  $value
     * @return int
     */
    protected function getTrend(string $value): int
    {
        $decimal = $this->asDecimal($value);

        return $decimal > 0 ? 1 : ($decimal < 0 ? -1 : 0);
    }

    /**
     * Return a decimal as string.
     *
     * @param  string  $value
     * @return string
     */
    protected function asDecimal(string $value): string
    {
        return preg_replace('/\.(?=.*\.)/', '', str_replace(',', '.', $value));
    }

    /**
     * Update or create new symbol instance for the given collection.
     *
     * @param  \Illuminate\Support\Collection<int,\App\Models\Symbol>  $symbols
     * @return void
     */
    protected function storeSymbols(Collection $symbols): void
    {
        foreach ($symbols as $symbol) {
            $oldSymbol = Symbol::where('code', $symbol['code'])->where('title', $symbol['title'])->first();

            if (filled($oldSymbol)) {
                $oldSymbol->fill($symbol);
                $oldSymbol->last_price = $symbol['last_price'];
                $oldSymbol->rate_of_change = $symbol['rate_of_change'];
                $oldSymbol->save();
            } else {
                if (Str::contains($symbol['title'], 'RÜÇHAN')) {
                    $symbol['code'] = $symbol['code'].'.R';
                }

                Symbol::updateOrCreate(
                    ['code' => $symbol['code']],
                    $symbol
                );
            }
        }
    }
}

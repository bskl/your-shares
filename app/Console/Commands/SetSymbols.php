<?php

namespace App\Console\Commands;

use App\Models\Symbol;
use Carbon\Carbon;
use DOMDocument;
use DOMXpath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
     * @return mixed
     */
    public function handle()
    {
        $content = $this->parseHtml();

        if ($content) {
            $symbols = $this->parseSymbols($content);
            $this->storeSymbols($symbols);
        }
    }

    protected function getHtml()
    {
        do {
            $response = Http::get($this->url);
        } while (!$response->successful());

        $body = preg_replace('/\r|\n|\t/', '', $response->body());

        return $body;
    }

    protected function parseHtml()
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        @$dom->loadHTML($this->getHtml());

        $xpath = new DOMXpath($dom);
        $allTr = $xpath->query("//table[contains(@data-csvname,'tumhisse')]/tbody/tr");

        if (!$allTr->length) {
            return false;
        }

        return $allTr;
    }

    protected function parseSymbols($content)
    {
        $symbols = collect();
        $sessionTime = Carbon::now()->subMinutes(15);

        foreach ($content as $tr) {
            $symbol = [
                'code'           => preg_replace('/[^a-zA-Z0-9]/', '', $tr->childNodes[0]->nodeValue),
                'title'          => $tr->childNodes[0]->hasAttribute('title') ? trim($tr->childNodes[0]->getAttribute('title')) : '',
                'trend'          => $this-getTrend(trim($tr->childNodes[4]->nodeValue)),
                'last_price'     => trim($tr->childNodes[2]->nodeValue),
                'rate_of_change' => $rateOfChange,
                'session_time'   => $sessionTime,
            ];

            $symbols->push($symbol);
        }

        return $symbols;
    }

    protected function getTrend($value)
    {
        $value = floatval(str_replace(',', '.', $value));

        return $value > 0 ? 1 : ($value < 0 ? -1 : 0);
    }

    protected function storeSymbols($symbols)
    {
        foreach ($symbols as $symbol) {
            Symbol::updateOrCreate(
                ['code' => $symbol['code']],
                $symbol
            );
        }
    }
}

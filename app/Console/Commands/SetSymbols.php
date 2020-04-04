<?php

namespace App\Console\Commands;

use \DOMDocument;
use \DOMXpath;
use App\Models\Symbol;
use Carbon\Carbon;
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

        $body = preg_replace("/\r|\n|\t/", "", $response->body());

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
            $symbol['code'] = preg_replace("/[^a-zA-Z0-9]/", "", $tr->childNodes[1]->nodeValue);
            $symbol['data'] = [
                'title'          => trim($tr->childNodes[1]->getAttribute('title')),
                'trend'          => (($rateOfChange = trim($tr->childNodes[5]->nodeValue)) > 0) ? 1 : (($rateOfChange < 0) ? -1 : 0),
                'last_price'     => trim($tr->childNodes[3]->nodeValue),
                'rate_of_change' => $rateOfChange,
                'session_time'   => $sessionTime,
            ];

            $symbols->push($symbol);
        }

        return $symbols;
    }

    protected function storeSymbols($symbols)
    {
        foreach ($symbols as $symbol) {
            Symbol::updateOrCreate(
                ['code' => $symbol['code']],
                $symbol['data']
            );
        }
    }
}

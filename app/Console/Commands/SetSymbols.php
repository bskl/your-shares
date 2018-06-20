<?php

namespace App\Console\Commands;

use App\Models\Symbol;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
    protected $description = 'Set symbols with xml file from web service.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $url = 'http://finans.mynet.com/borsa/canliborsadata/data/';

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
        do {
            $content = $this->fileGetContents($this->url);
        } while ($content['session'] === 'error');

        $symbols = $this->getSymbols($content['stocks']);

        $this->storeSymbols($symbols);
    }

    protected function fileGetContents($url)
    {
        do {
            $content = file_get_contents($url);
        } while ($content === false);

        return json_decode($content, true);
    }

    protected function getSymbols($stocks)
    {
        foreach ($stocks as $key => $symbol) {
            if ($symbol[11] === 1) {
                $symbols[$key] = $symbol;
            }
        }

        return $symbols;
    }

    protected function convertToInt($val)
    {
        if (is_numeric($val)) {
            return (float) $val = $val / 100;
        }

        if (preg_match('#^\d*[\.,]\d$#', $val)) {
            return (float) $val = str_replace(',', '', $val);
        }

        if (preg_match('#\d*([.,\/]?\d+)#', $val)) {
            return (float) $val = str_replace([',', '.'], '', $val);
        }

        throw new \InvalidArgumentException('Not a valid currency amount');
    }

    protected function storeSymbols($symbols)
    {
        foreach ($symbols as $key => $value) {
            Symbol::updateOrCreate(['code' => $key], [
                'trend'          => (int) $value[0],
                'last_price'     => $this->convertToInt($value[1]),
                'rate_of_change' => $this->convertToInt($value[4]),
                'session_time'   => Carbon::parse($value[10]),
            ]);
        }
    }
}

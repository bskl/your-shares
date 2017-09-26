<?php

namespace App\Console\Commands;


use App\Contracts\SymbolRepository;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SetSymbols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-symbols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set symbols with xml file from web service.';

    /**
     * The symbols instance.
     */
    protected $symbols;

    /**
     * Create a new instance.
     *
     * @param  App\Contracts\SymbolRepository  $symbols
     * @return void
     */
    public function __construct(SymbolRepository $symbols)
    {
        parent::__construct();

        $this->symbols = $symbols;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $content = $this->fileGetContents('http://finans.mynet.com/borsa/canliborsadata/data/');

        $array = json_decode($content, true);

        $stocks = $this->getStocks($array);

        $symbols = $this->getSymbols($stocks);

        $this->storeSymbols($symbols);
    }

    protected function fileGetContents($url)
    {
        $content = file_get_contents($url);

        if ($content === false) {
            echo "Error fetching XML\n";
        } else {
            return $content;
        }
    }

    protected function getStocks($data)
    {
        if($data['session'] === "error") {
            echo "Session has error.";
        } else {
            return $data['stocks'];
        }
    }

    protected function getSymbols($stocks)
    {
        foreach($stocks as $key => $symbol) {
            if($symbol[11] === 1) {
                $symbols[$key] = $symbol;
            }
        }

        return $symbols;
    }

    protected function convertToInt($val)
    { 
        if(strpos($val, ",")) { 
            $val = str_replace(",", "", $val);
        }
        if(strpos($val, ".")) {
            $val = $val * 100;
        }
        
        return (int)$val;  
    }

    protected function storeSymbols($symbols)
    {
        foreach($symbols as $key => $value) {
            $this->symbols->updateOrCreate(['code' => $key], [
                'change' => (int)$value[0],
                'last_price' => $this->convertToInt($value[1]),
                'rate_of_change' => $this->convertToInt($value[4]),
                'session_time' => Carbon::parse($value[10]),
            ]);
        }
    }
}
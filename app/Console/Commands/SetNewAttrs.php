<?php

namespace App\Console\Commands;

use App\Models\Portfolio;
use Illuminate\Console\Command;

class SetNewAttrs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yourshares:set-new-attrs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set symbols with xml file from web service.';

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
        $portfolios = Portfolio::all();

        foreach ($portfolios as $portfolio) {
            foreach ($portfolio->shares as $share) {
                if ($share->lot > 0) {
                    $share->average_amount_with_dividend = $share->average_amount->subtract($share->total_dividend_gain);
                    $share->average_with_dividend = $share->average_amount_with_dividend->divide($share->lot);
                    $share->gain_with_dividend = $share->amount->subtract($share->average_amount_with_dividend);
                } else {
                    $share->average_amount_with_dividend = $share->average_amount->subtract($share->total_dividend_gain);
                    $share->average_with_dividend = $share->lot;
                    $share->gain_with_dividend = $share->amount->subtract($share->average_amount_with_dividend);
                }
                $share->update();
            }
        }
    }
}

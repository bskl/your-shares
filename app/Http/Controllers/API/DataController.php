<?php

namespace App\Http\Controllers\API;

use App\Models\Portfolio;
use Money\Money;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    /**
     * Get a set of application data.
     *
     * @return JsonResponse
     */
    public function getData()
    {
        $portfolios = Portfolio::byCurrentUser()->get();

        $portfolios = $portfolios->map(function ($portfolio) {
            $totalAmount = $totalAverageAmount = $totalGain = Money::TRY(0);

            $portfolio->shares->map(function ($share) use (&$totalAmount, &$totalAverageAmount, &$totalGain) {
                $totalAmount = $totalAmount->add($share->amount);
                $totalAverageAmount = $totalAverageAmount->add($share->average_amount);
                $totalGain = $totalGain->add($share->gain);
            });

            $portfolio->total_amount = $totalAmount;
            $portfolio->total_average_amount = $totalAverageAmount;
            $portfolio->total_gain = $totalGain;

            return $portfolio;
        });

        return [
            'user' => auth()->user(),
            'portfolios' => $portfolios,
        ];
    }
}
<?php

namespace App\Providers;

use TCG\Voyager\Facades\Voyager;
use App\Actions\ExportToExcelAction;
use Illuminate\Support\ServiceProvider;

class VoyagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Voyager::addAction(ExportToExcelAction::class);
    }

    public function register()
    {
        //
    }
}

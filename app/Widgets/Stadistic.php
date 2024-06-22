<?php

namespace App\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Arrilot\Widgets\AbstractWidget;

class Stadistic extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        $userCountLastWeek = User::where('created_at', '>=', $oneWeekAgo)->count();
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'fa fa-users',
            'title'  => "Usuarios Registrados en la Última Semana",
            'text'   => "Número de usuarios registrados en la última semana: $userCountLastWeek",
            'button' => [
                'text' => "Ver Usuarios",
                'link' => url('/admin/users')
            ],
            'image' => asset('/images/looper.png'),
        ]));
    }
    public function shouldBeDisplayed()
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Client;

use Carbon\Carbon;
use App\Models\User;
use Orchid\Screen\TD;
use Carbon\CarbonPeriod;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Layout;

class ClientListTable extends Table
{
    protected $target = 'clients';

    protected function columns(): iterable
    {
        return [
            TD::make('name', 'user name')->filter(TD::FILTER_TEXT),
            TD::make('email', 'user email')->filter(TD::FILTER_TEXT),
            TD::make('email_verified_at', 'email verified')->canSee($this->isWorkTime())->sort()->render(function (User $user) {
                return $user->email_verified_at == null ? "not verified" : "verified";
            })->align(TD::ALIGN_RIGHT)->popover('if verified email then user can use this website'),
        ];
    }

    private function isWorkTime(): bool
    {
        $workTime = CarbonPeriod::create('9:00', '20:00');
        return $workTime->contains(Carbon::now(config('app.timezone')));
    }
}

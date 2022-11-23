<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Orchid\Layouts\Client\ClientListTable;
use Orchid\Screen\TD;
use Carbon\CarbonPeriod;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ClientListScreen extends Screen
{
    protected readonly string $description;

    public function __construct()
    {
        $this->description = 'All clients';
    }

    public function query(): iterable
    {
        return [
            'clients' => User::filters()->defaultSort('email_verified_at', 'desc')->paginate(10),
        ];
    }

    public function name(): ?string
    {
        return 'Clients';
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            ClientListTable::class
        ];
    }
}

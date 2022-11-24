<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Client;

use Carbon\Carbon;
use App\Models\User;
use Orchid\Screen\TD;
use Carbon\CarbonPeriod;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Http\Requests\ClientRequest;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\Client\ClientListTable;
use App\Orchid\Layouts\CreateOrUpdateClient;

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
        return [
            ModalToggle::make('create client')->modal('createClient')->method('create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ClientListTable::class,
            Layout::modal('createClient', CreateOrUpdateClient::class)->title('create client')->applyButton('create client'),
            Layout::modal('updateClient', CreateOrUpdateClient::class)->title('update client')->applyButton('update client')
                ->async('asyncGetClient'),
        ];
    }

    public function create(ClientRequest $request): void
    {
        User::create($request->validated()['client']);
        Toast::info('client has been created');
    }

    public function update(ClientRequest $request): void
    {
        User::find($request->input('client.id'))->update($request->validated()['client']);
        Toast::info('client has been updated');
    }

    public function asyncGetClient(User $user): array
    {
        return [
            'client' => $user,
        ];
    }
}

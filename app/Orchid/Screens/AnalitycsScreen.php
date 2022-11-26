<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Http\Requests\PhoneRequest;
use Illuminate\Validation\ValidationException;
use App\Orchid\Layouts\Charts\DynamicInterviewedClients;
use App\Orchid\Layouts\Charts\PercentageFeedbackClients;
use Illuminate\Http\Response;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalitycsScreen extends Screen
{
    public $permission = ['platform.analitycs', 'platform.reports'];

    public function query(): iterable
    {
        return [
            'percentageFeedback' => User::whereNotNull('assessment')->countForGroup('assessment')->toChart(),
            'interviewedClients' => [
                User::whereNotNull('assessment')->countByDays(startDate:null, stopDate:null, dateColumn:'updated_at')
                    ->toChart('interviewed clients'),
                User::countByDays()->toChart('new clients'),
            ],
        ];
    }

    public function name(): ?string
    {
        return 'Analitycs';
    }

    public function layout(): iterable
    {
        return [
            Layout::columns([
                PercentageFeedbackClients::class,
                DynamicInterviewedClients::class,
            ]),
            Layout::tabs([
                'import phones' => Layout::rows([
                    Input::make('file')
                        ->type('file')
                        ->required()
                        ->placeholder('file')
                        ->title('import phones'),

                    Button::make('download')
                        ->confirm('Are you sure?')
                        ->type(Color::PRIMARY())
                        ->method('importPhones'),
                ]),

                'Reports by clients' => Layout::rows([
                    Button::make('download')
                        ->method('downloadPhones')
                        ->rawClick(),
                ]),
            ]),
        ];
    }

    public function importPhones(PhoneRequest $request): void
    {
        $rawPhones = $request->validated()['file'];
        // dd($request->file('file'));
        // dd($rawPhones);

        $phones = array_map(function ($rawPhone) {
            return phone_normalized(array_shift($rawPhone));
        }, array_map('str_getcsv', file($rawPhones->path())));

        $foundPhones = User::whereIn('phone', $phones)->get();

        if ($foundPhones->count() > 0)
        {
            throw ValidationException::withMessages([
                'file' => 'phone number already exists' . PHP_EOL . $foundPhones->implode('phone', ','),
            ]);
        }

        foreach ($phones as $phone)
        {
            User::create(['phone' => $phone]);
        }

        Toast::info('phones has been created');
    }

    public function downloadPhones(): StreamedResponse
    {
        $clients = User::get(['phone', 'email', 'assessment']);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=clients.csv',
        ];
        $columns = ['phone', 'email', 'assessment'];

        $callback = function () use ($clients, $columns) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $columns);

            foreach ($clients as $client)
            {
                fputcsv($stream, [
                    'phone' => $client->phone,
                    'email' => $client->email,
                    'assessment' => $client->assessment,
                ]);
            }

            fclose($stream);
        };

        return response()->stream($callback, Response::HTTP_OK, $headers);
    }
}

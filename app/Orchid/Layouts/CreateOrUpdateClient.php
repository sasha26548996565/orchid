<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;

class CreateOrUpdateClient extends Rows
{
    protected $title;

    protected function fields(): iterable
    {
        $clientExists = is_null($this->query->getContent('client')) ? false : true;
        return [
            Input::make('client.id')->type('hidden'),
            Input::make('client.name')->required()->placeholder('name'),
            Input::make('client.email')->type('email')->placeholder('email'),
            Input::make('client.phone')->type('phone')->disabled($clientExists)->mask('(999) 999-9999')->placeholder('phone'),
            Input::make('client.password')->type('password')->disabled($clientExists)->placeholder('password'),
            Select::make('client.assessment')->required()->options([
                'good' => 'good',
                'bad' => 'bad'
            ])->help('assessment')->empty('unknow', 'unknow'),
        ];
    }
}

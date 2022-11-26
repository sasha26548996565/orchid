<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class DynamicInterviewedClients extends Chart
{
    protected $title = 'dynamic interviewed';
    protected $target = 'interviewedClients';
    protected $type = 'line';
    protected $export = true;
}

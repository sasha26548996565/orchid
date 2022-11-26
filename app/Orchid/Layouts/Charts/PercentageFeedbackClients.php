<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class PercentageFeedbackClients extends Chart
{
    protected $title = 'feedback clients';
    protected $type = 'pie';
    protected $target = 'percentageFeedback';
    protected $export = true;

}

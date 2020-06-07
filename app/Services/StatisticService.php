<?php

namespace App\Services;


use App\Repositories\StatementRepository;
use Carbon\Carbon;
use Str;

class StatisticService
{
    protected $data = '';
    protected $type = '';

    protected $carbonTypes = [
        'statements' => [
            'day' => 'dayName',
            'month' => 'monthName',
            'year' => 'year'
        ]
    ];

    public function __construct($data = '', $type = '')
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function getStatistic()
    {
        $function = $this->data . 'Statistic';
        if (method_exists(__CLASS__, $function)) {
            $data = $this->$function();
        } else {
            $data = ['status' => false, 'message' => 'Такой статистики нет'];
        }

        return $data;
    }

    private function statementsStatistic()
    {
        $repo = new StatementRepository();
        $data = $repo->getByDateType($this->type);
        return $this->formattingStatements($data);
    }

    private function formattingStatements($collection)
    {
        $type = $this->dateCarbonType();

        if($this->type != 'day') {
            $labels = $this->{$type}();
            $data = [];
        }


        foreach ($collection as $i => $collect) {
            if ($this->type == 'day') {
                $labels[] = $i;
                $data[] = count($collect);
            } else {
                $dateType = Str::ucfirst(Carbon::createFromFormat('!' . substr($this->type, 0, 1), $i)->{$type});
                foreach ($labels as $label) {
                    if ($label == $dateType) {
                        $data[] = count($collect);
                    } else {
                        $data[] = 0;
                    }
                }
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => "Количество поступивших заявлений",
                'backgroundColor' => "#3e95cd",
                'data' => $data
            ]]
        ];
    }

    private function dateCarbonType()
    {
        return $this->carbonTypes[$this->data][$this->type];
    }


    private function year()
    {
        $years = [];
        $currentYear = Carbon::now()->year;
        $startYear = $currentYear - 4;
        for ($y = $startYear; $y <= $currentYear + 3; $y++) {
            $years[] = $y;
        }
        return $years;
    }

    private function monthName()
    {
        $months = [];
        setlocale(LC_TIME, 'ru_RU', 'russian');
        for ($m = 1; $m <= 12; ++$m) {
            $str = strftime('%B', mktime(0, 0, 0, $m, 1));
            $months[] = iconv('windows-1251', 'utf-8', $str);
        }
        return $months;
    }


}

<?php


namespace App\Repositories;


use App\Models\Statement;
use Carbon\Carbon;


class StatementRepository
{
    protected $statement;
    private $dateType = ['day', 'month', 'year'];

    public function __construct()
    {
        $this->statement = new Statement();
    }


    public function getWithClient(bool $status = false)
    {
        return $this->statement
            ->whereStatus($status)
            ->with('client')
            ->get();
    }

    public function getByDateType($date = 'year')
    {

        if ($date == 'day') {
            return $this->statement
                ->orderBy('created_at', 'ASC')
                ->get()
                ->groupBy(function ($d) use ($date) {
                    return Carbon::parse($d->created_at)->format('d.m.Y');
                });
        }

        $where = 'where' . ucfirst($date);
        return $this->statement->{$where}('created_at', Carbon::now()->{$date})
            ->orderBy('created_at', 'ASC')
            ->get()
            ->groupBy(function ($d) use ($date) {
                return Carbon::parse($d->created_at)->format(substr($date, 0, 1));
            });

    }


}

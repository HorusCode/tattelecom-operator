<?php

namespace App\Http\Controllers;

use App\Events\StatementAdded;
use App\Http\Requests\IDsRequest;
use App\Models\Statement;
use App\Models\User;
use App\Models\Work;
use App\Notifications\WorkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;
use PhpOffice\PhpWord\TemplateProcessor;

class WorkController extends Controller
{
    protected $work;
    protected $user;
    protected $statement;

    public function __construct(Work $work, Statement $statement, User $user)
    {
        $this->work = $work;
        $this->user = $user;
        $this->statement = $statement;
    }

    public function store(IDsRequest $request)
    {
        $data = [];
        foreach ($request->ids as $id) {
            $data[] = [
                'service_user_id' => $id,
                'statement_id' => $request->current_id,
                'operator_user_id' => Auth::id(),
                'created_at' => date('Y-m-d H-i-s'),
                'updated_at' => date('Y-m-d H-i-s')
            ];
        }
        $stmt = $this->statement->find($request->current_id);
        $stmt->update(['status' => false]);
        $created = $this->work->insert($data);
        $user = $stmt->client;
        $fullNameClient = $user->lastname . ' ' . $user->firstname . ' ' . $user->patronymic;
        $filesArr = [];
        if ($created) {
            event(new StatementAdded($data));
            foreach ($request->ids as $id) {
                $service = $this->user->find($id);
                $service->notify(new WorkNotification('У вас появилась новая работа!'));
                $date = Carbon::now();
                $_doc = new TemplateProcessor(public_path('docx\template.docx'));
                $_doc->setValue('created_at', $date);
                $_doc->setValue('client_fullname', $fullNameClient);
                $_doc->setValue('client_address', $user->address);
                $_doc->setValue('passport_series', $user->passport_series);
                $_doc->setValue('passport_number', $user->passport_number);
                $_doc->setValue('client_phone', $user->phone);
                $_doc->setValue('client_operator_fullname', Auth::user()->getFullName());
                $_doc->setValue('client_problem', $stmt->problem);
                $_doc->setValue('service_fullname', $service->getFullName());
                $_doc->setValue('service_phone', $service->phone);
                $filename = "statement-{$stmt->id}-{$id}-{$date->day}-{$date->month}-{$date->year}.docx";
                $filesArr[] = [
                    'name' => $filename,
                    'url' => asset('files/' . $filename)
                ];
                $_doc->saveAs(public_path('files/' . $filename));
            }
        }

        return response()->json(['status' => $created, 'files' => $filesArr]);
    }


    public function start(Request $request)
    {
        $statement = $this->statement->find($request->statement_id);
        $statement->update(['status' => false]);
        $work = $this->work->find($request->work_id);
        $work->operatorUser->notify(new WorkNotification($work->serviceUser->getFullName() . ' приступил к работе!'));
        $status = $work->update(['status' => 1]);

        return response()->json(['status' => $status]);
    }

    public function stop(Request $request)
    {
        $work = $this->work->find($request->work_id);
        $work->operatorUser->notify(new WorkNotification($work->serviceUser->getFullName() . ' закончил выполнение работы!'));
        $status = $work->update(['status' => 2]);
        return response()->json(['status' => $status]);
    }
}

<?php

namespace App\DataTables\User;

use App\Models\User;
use App\Support\DataTableCommonFunction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    use DataTableCommonFunction;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     * @throws Exception
     */
    public function dataTable(mixed $query): DataTableAbstract
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                $this->buildQuerySearch($query);
            })
            ->addColumn('action', function (User $user) {
                return $this->buildAction($user);
            })
            ->editColumn('role_id', function (User $user) {
                return optional($user->role)->name;
            })
            ->editColumn('status', function (User $user) {
                return $this->buildStatus($user->status);
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * @param $status
     * @return string
     */
    private function buildStatus($status): string
    {
        return $status ? '<span class="badge badge-success on">' . __("generate.translate.button.on") . '</span>' :
            '<span class="badge badge-danger off">' . __("generate.translate.button.off") . '</span>';
    }

    /**
     * @param $station
     * @return string
     */
    private function buildAction($station): string
    {
        $action = Gate::allows(User::EDIT) ? '<a class="btn btn-secondary btn-hover-brown btn-sm edit" data-id="' . $station->id .
            '" id="inline_edit">' . __("generate.translate.button.edit") . '</a> ' : '';
        $action .= Gate::allows(User::DELETE) ? '<a class="btn btn-danger btn-sm delete" data-id="' . $station->id .
            '" id="inline_delete">' . __("generate.translate.button.delete") . '</a> ' : '';
        return $action;
    }

    /**
     * @param $query
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function buildQuerySearch(&$query): void
    {
        foreach (__('generate.user.filter') as $key => $value) {
            if (request()->filled($key)) {
                if($key == 'status'){
                    $query->where($key, request()->get($key));
                }else{
                    $query->where($key, 'LIKE', "%" . $this->escapeLike(request()->get($key) ). "%");

                }
            }
        }
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return Builder
     */
    public function query(User $model): Builder
    {
        return $model->newQuery()->with('role');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): \Yajra\DataTables\Html\Builder
    {
        return $this->builder()
            ->setTableId('petrol-table')
            ->columns($this->getColumns())
            ->parameters($this->buildParameters())

            ->ajax($this->buildAjaxData(User::class, 'user'))
            ->orderBy(0)
            ->buttons(
                Button::make('excel')
            );
    }

    /**
     * @return array
     */
    #[ArrayShape(['responsive' => "bool", 'autoWidth' => "false", 'searching' => "false", 'language' => "array", 'select' => "string[]"])]
    private function buildParameters(): array
    {
        return [
            'responsive' => true,
            'autoWidth' => false,
            'searching' => false,
            'language' => ['url' => $this->dataTableLanguage()],
            'select' => [
                'style' => 'single',
                'selector' => 'td:first-child',
            ],

        ];
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title(__('generate.translate.users.name')),
            Column::make('email')->title(__('generate.translate.users.email')),
            Column::make('phone')->title(__('generate.translate.users.phone')),
            Column::make('department')->title(__('generate.translate.users.department')),
            Column::make('role_id')->title(__('generate.translate.users.role_id')),
            Column::make('status')->title(__('generate.translate.users.status')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}

<?php

namespace App\DataTables\User;


use App\Models\Permission;
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

class PermissionDataTable extends DataTable
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
            ->addColumn('action', function (Permission $user) {
                return $this->buildAction($user);
            })
            ->rawColumns(['action']);
    }

    /**
     * @param $station
     * @return string
     */
    private function buildAction($station): string
    {
        $action = Gate::allows(Permission::EDIT) ? '<a class="btn btn-secondary btn-hover-brown btn-sm edit" data-id="' . $station->id .
            '" id="inline_edit">' . __("generate.translate.button.edit") . '</a> ' : '';
        $action .= Gate::allows(Permission::DELETE) ? '<a class="btn btn-danger btn-sm delete" data-id="' . $station->id .
            '" id="inline_delete">' . __("generate.translate.button.delete") . '</a> ' : '';
        return $action;
    }

    /**
     * @param $query
     */
    private function buildQuerySearch($query)
    {
        foreach (__('generate.permission.filter') as $key => $value) {
            if (request()->filled($key)) {
                try {
                    $query->where($key, 'ILIKE', "%" . $this->escapeLike(request()->get($key)) . "%");
                } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {

                }
            }
        }
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
     * Get query source of dataTable.
     *
     * @param Permission $model
     * @return Builder
     */
    public function query(Permission $model): Builder
    {
        return $model->newQuery();
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

            ->ajax($this->buildAjaxData(Permission::class, 'permission'))
            ->orderBy(0)
            ->buttons(
                Button::make('excel')
            );
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
            Column::make('name')->title(__('generate.translate.permissions.name')),
            Column::make('description')->title(__('generate.translate.permissions.description')),
            Column::computed('action')->title("Hoạt động")
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
        return 'Permission_' . date('YmdHis');
    }
}

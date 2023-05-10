<?php

namespace App\DataTables;

use App\Models\GroupHomeContents;
use App\Models\Post;
use App\Support\DataTableCommonFunction;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PostDataTable extends DataTable
{
    use DataTableCommonFunction;

    /**
     * @throws Exception
     */
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                $this->buildQuerySearch($query);
            })
            ->addColumn(__('generate.translate.button.action'), function (Post $post) {
                return $this->buildAction($post);
            })
            ->addColumn('checkbox', function (Post $post) {
                return $this->checkbox($post);
            })
            ->editColumn('post_type', function (Post $post) {
                return match (($post->category)->type) {
                    'ABOUT' => 'About',
                    'PRODUCT' => 'Product',
                    'NEWS' => 'News'
                };
            })
            ->editColumn('title', function (Post $post) {
                return Str::words(strip_tags($post->title), 10);
            })
            ->editColumn('description', function (Post $post) {
                return Str::words(strip_tags($post->description), 10);
            })
            ->editColumn('image', function (Post $post) {
                return $this->buildImage($post);
            })
            ->editColumn('status', function (Post $post) {
                return match ($post->status) {
                    'WAITING' => 'Waiting',
                    'ACTIVE' => 'Active',
                    'CLOSED' => 'Closed'
                };
            })
            ->rawColumns([__('generate.translate.button.action'), 'type', 'status']);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function buildQuerySearch($query)
    {
        foreach (__('generate.post.filter') as $key => $value) {
            if (request()->filled($key)) {
                if ($key == 'status') {
                    $query->where($key, request()->get($key));
                } else {
                    $query->where($key, 'LIKE', "%" . $this->escapeLike(request()->get($key)) . "%");
                }
            }
        }
    }

    /**
     * @param $post
     * @return string
     * .
     */
    private function buildAction($post): string
    {
        $action = Gate::allows(Post::EDIT) ? '<a href="javascript:void(0);" class="btn btn-light btn-xs d-inline-flex py-1 mx-1 inline_edit" data-id="' . $post->id . '">'
            . '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<path stroke="#29B0FF" stroke-linecap="round" stroke-linejoin="round" d="M9.13306 13.2654H13.3844" />'
            . '<path stroke="#29B0FF" stroke-linecap="round" stroke-linejoin="round" d="M8.57 3.30378C9.06131 2.67778 9.85531 2.71044 10.482 3.20178L11.4086 3.92844C12.0353 4.41978 12.2573 5.18178 11.766 5.80911L6.23997 12.8591C6.05531 13.0951 5.77331 13.2344 5.47331 13.2378L3.34197 13.2651L2.85931 11.1884C2.79131 10.8971 2.85931 10.5904 3.04397 10.3538L8.57 3.30378Z" />'
            . '<path stroke="#29B0FF" stroke-linecap="round" stroke-linejoin="round" d="M7.53516 4.62402L10.7312 7.12936" />'
            . '</svg>'
            . '</a>' : '';
        $action .= Gate::allows(Post::DELETE) ? '<a href="javascript:void(0);" class="btn btn-light btn-xs d-inline-flex py-1 mx-1 inline_delete" data-id="' . $post->id . '" >'
            . '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<path stroke="#E26258" stroke-linecap="round" stroke-linejoin="round" d="M12.8833 6.31213C12.8833 6.31213 12.5213 10.8021 12.3113 12.6935C12.2113 13.5968 11.6533 14.1261 10.7393 14.1428C8.99994 14.1741 7.25861 14.1761 5.51994 14.1395C4.64061 14.1215 4.09194 13.5855 3.99394 12.6981C3.78261 10.7901 3.42261 6.31213 3.42261 6.31213" />'
            . '<path stroke="#E26258" stroke-linecap="round" stroke-linejoin="round" d="M13.8056 4.15981H2.50024" />'
            . '<path stroke="#E26258" stroke-linecap="round" stroke-linejoin="round" d="M11.6271 4.1598C11.1038 4.1598 10.6531 3.7898 10.5505 3.27713L10.3885 2.46647C10.2885 2.09247 9.9498 1.8338 9.5638 1.8338H6.7418C6.3558 1.8338 6.01713 2.09247 5.91713 2.46647L5.75513 3.27713C5.65247 3.7898 5.2018 4.1598 4.67847 4.1598" />'
            . '</svg>'
            . '</a>' : '';
        return $action;
    }

    /**
     * @param $post
     * @return string
     */
    private function checkbox($post): string
    {
        return '<div class="custom-control custom-checkbox">'
            . '<input id="checkRow-' . $post->id . '" class="custom-control-input" type="checkbox" value="' . $post->id . '" />'
            . '<label for="checkRow-' . $post->id . '" class="custom-control-label"></label>'
            . '</div>';
    }

    /**
     * @param $post
     * @return string
     */
    private function buildImage($post): string
    {
        return $post->image ? '<img src="' . Storage::url($post->image) . '" alt="' . $post->title . '" height="50" />' : '';
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('dataTable')
            ->columns($this->getColumns())
            ->parameters($this->buildParameters())
            ->ajax($this->buildAjaxData(Post::class, 'post'))
            ->orderBy(1)
            ->buttons(['remove'])
            ->dom("Bt<'row my-4 pb-2'<'col-sm-6'l><'col-sm-6'p>>");
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::computed('checkbox')->width('1%')->exportable(false)->printable(false)->titleAttr('Check all')
                ->title('<div class="custom-control custom-checkbox">'
                    . '<input id="checkAll" class="custom-control-input" type="checkbox" name="checkAll">'
                    . '<label for="checkAll" class="custom-control-label"></label>'
                    . '</div>'),
            Column::make('id')->title('#ID')->width('1%'),
            Column::make('title')->title('Title')->orderable(false),
            Column::make('description')->title('Description')->orderable(false),
            Column::make('image')->title('Image')->orderable(false),
            Column::make('url')->title('Url')->orderable(false),
            Column::make('status')->title('Status')->orderable(false),
            Column::computed(__('generate.translate.button.action'))->exportable(false)->printable(false)->width('1%')->addClass('text-center text-nowrap'),
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([])] private function buildParameters(): array
    {
        return [
            'responsive' => true,
            'autoWidth' => false,
            'searching' => true,
            'language' => ['url' => $this->dataTableLanguage()],
            'pagingType' => 'input'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Post_' . date('YmdHis');
    }
}

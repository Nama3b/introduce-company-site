<?php

namespace App\Support;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\ArrayShape;

trait DataTableCommonFunction
{
    /**
     * @param $class
     * @param $routeName
     * @param string $method
     * @return array
     */
    #[ArrayShape([])] private function buildAjaxData($class, $routeName, string $method = 'GET'): array
    {

        $result = [
            "url" => route($routeName),
            "type" => $method,
            "error" => "function (xhr, error, code)
            {
                $('body').Toasts('create', {
                    autohide: true,
                    class: 'bg-danger',
                    title: 'Notice',
                    body: 'An error occurred while processing your request. Please check your internet connection and try again later'
                })
            }",
        ];
        $result['data'] = 'function(data) {';
        foreach (__('generate.' . $routeName . '.filter') as $key => $value) {
            if (is_array($value)) {
                // multi type here
                if ($value['type'] == 'select') {
                    $result['data'] .= 'var select_' . $key . ' = $("#filter select[name=' . $key . ']").val();';
                    $result['data'] .= 'data.' . $key . ' = select_' . $key . ' ? select_' . $key . ': null;';
                }
            } else {
                $result['data'] .= 'data.' . $key . ' = $("#filter input[name=' . $key . ']").val();';
            }
        }

        foreach (request()->all() as $key => $value) {
            if ($key != 'x_request_id') {
                $result['data'] .= 'data.' . $key . '="' . $value . '";';
            }
        }

        $result['data'] .= 'for (var i = 0, len = data.columns.length; i < len; i++) {
                            if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                            if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                            if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                        }
                        delete data.search.regex; }';
        return $result;
    }

    /**
     * @param string $value
     * @param string $char
     * @return string
     */
    public function escapeLike(string $value, string $char = '\\'): string
    {
        return str_replace(
            [$char, '%', '_'],
            [$char . $char, $char . '%', $char . '_'],
            $value
        );
    }


    /**
     * @return Application|UrlGenerator|string
     */
    private function dataTableLanguage(): string|UrlGenerator|Application
    {
        return match (App::getLocale()) {
            "vi" => url('vendor/datatables/lang_vi.json'),
            default => url('vendor/datatables/lang_en.json'),
        };
    }
}

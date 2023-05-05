<?php

namespace App\Support;

trait ApiResponseFormatter
{
    /**
     * Format data for API response.
     * @param array $data
     * @param  mixed $customMessage
     * @param null $statusCode
     * @param bool $error
     * @param null $meta
     * @return array
     */
    public function formatDataForApiResponse(array $data = [],  $customMessage = '', $statusCode = null, bool $error = false, $meta = null): array
    {
        $final = [];

        if (!empty($data)) {
            if ($error) {
                $final = array_merge($final, ['errors' => $data]);
            } else {
                $final = array_merge($final, ['data' => $data]);
            }
        }

        $final = array_merge($final, [
            'code' => $statusCode,
            'request_id' => request()->id(),
        ]);

        $message = null;

        if (!empty($customMessage)) {
            $message = $customMessage;
        }

        if ($message) {
            $final = array_merge($final, ['message' => $message]);
        }

        if ($meta) {
            $final = array_merge($final, ['meta' => $meta]);
        }

        return $final;
    }
}

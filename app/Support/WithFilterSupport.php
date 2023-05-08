<?php

namespace App\Support;


use App\Exceptions\SpamException;
use App\Models\Request\RequestLog;

trait WithFilterSupport
{

	/**
	 * @param $query
	 * @param array $variable
	 * @return void
	 */

	protected function filterLike($query, array $variable = []): void
	{
		foreach ($variable as $singleFilter) {
			if ($this->filledAndEmpty($singleFilter)) {
				$query->where($singleFilter, 'LIKE', '%' . $this->escapeLike($this->request->input($singleFilter)) . '%');
			}
		}
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
	 * @param $element
	 * @return bool
	 */
	protected function filledAndEmpty($element): bool
	{
		return $this->request->filled($element) && $this->request->input($element);
	}


	/**
	 * @param $query
	 * @param array $variable
	 * @return void
	 */
	protected function filter($query, array $variable = []): void
	{
		foreach ($variable as $singleFilter) {
			if ($this->filledAndEmpty($singleFilter)) {
				if ($singleFilter == 'team' && $this->request->input($singleFilter) == 'なし') {
					$query->where(function ($q) {
						$q->whereNull('team')
							->orWhere('team', 'なし')
							->orWhere('team', '');
					});
				} else {
					$query->where($singleFilter, $this->request->input($singleFilter));
				}
			}
		}
	}


	/**
	 * @param $query
	 * @param array $variable
	 * @return void
	 */
	protected function filterBool($query, array $variable = []): void
	{
		foreach ($variable as $singleFilter) {

			if ($this->request->filled($singleFilter)) {
				$query->where($singleFilter, $this->request->input($singleFilter));
			}
		}
	}

	/**
	 * @param $query
	 * @param array $variable
	 * @return void
	 */
	protected function filterDate($query, array $variable = []): void
	{
		foreach ($variable as $singleFilter) {
			if ($this->request->filled($singleFilter)) {
				$query->whereDate($singleFilter, $this->request->input($singleFilter));
			}
		}
	}


	/**
	 * @param $query
	 * @param string $sortBy
	 * @param string $sortType
	 * @param array $sortList
	 * @return void
	 * @throws SpamException
	 */
	protected function sortBy($query, string $sortBy, string $sortType, array $sortList = []): void
	{
		$this->validateSortBy($sortBy, $sortList);
		$this->validateSortType($sortType);

		$query->orderBy($sortBy, $sortType);
	}


	/**
	 * @param $sortBy
	 * @param $sortList
	 * @return void
	 * @throws SpamException
	 */
	private function validateSortBy($sortBy, $sortList): void
	{
		if (!in_array($sortBy, $sortList)) {
			throw new SpamException;
		}

	}

	/**
	 * @param $sortBy
	 * @return void
	 * @throws SpamException
	 */
	private function validateSortType($sortBy): void
	{
		if (!in_array(strtoupper($sortBy), ['DESC', 'ASC'])) {
			throw new SpamException;
		}

	}


	/**
	 * @param $query
	 * @param $colum
	 * @param $aliasRequest
	 * @return void
	 */
	protected function filterWhereInAlias($query, $colum, $aliasRequest): void
	{
		if ($this->filledAndEmpty($aliasRequest)) {
			$query->whereIn($colum, $this->buildWhereIn($aliasRequest));
		}
	}

	/**
	 * @param $variable
	 * @return mixed|string[]
	 */
	private function buildWhereIn($variable): mixed
	{
		return is_array($this->request->input($variable)) ? $this->request->input($variable) :
			explode(",", $this->request->input($variable));
	}


	/**
	 * @param $condition
	 * @param $date
	 * @param bool $first
	 * @return void
	 */
	protected function overlapDate($condition, $date, bool $first = false): void
	{
		$condition->{$first ? 'where' : 'orWhere'}(function ($query) use ($date) {
			$query->where(function ($sub) use ($date) {
				$sub->whereNotNull('valid_to')
					->whereRaw('? between valid_from and valid_to',
						[$date]);
			})->orWhere(function ($sub) use ($date) {
				$sub->whereNull('valid_to')
					->where('valid_from', '<=', $date);
			});
		});
	}


	/**
	 * @param $condition
	 * @param $from
	 * @param $to
	 * @param bool $first
	 * @return void
	 */
	protected function validDataTo($condition, $from, $to, bool $first = false): void
	{
		$condition->{$first ? 'where' : 'orWhere'}(function ($query) use ($from, $to) {
			$query->where(function ($sub) use ($from, $to) {
				$sub->whereNotNull('valid_to')
					->whereRaw('? between valid_from and valid_to',
						[$to])
					->orWhere(function ($query) use ($from, $to) {
						$query->where('valid_from', '>=', $from)
							->where('valid_to', '<=', $to);
					});
			})->orWhere(function ($sub) use ($to) {
				$sub->whereNull('valid_to')
					->where('valid_from', '<=', $to);
			});
		});
	}


	/**
	 * @param $logs
	 * @param $routeStatusId
	 * @param bool $search
	 * @param bool $reject
	 * @return void
	 */
	protected function filterStatus($logs, $routeStatusId, bool $search = false, bool $reject = false): void
	{
		$first = true;
		$listStatus = is_array($routeStatusId) ? $routeStatusId : explode(",", $routeStatusId);


		foreach ($listStatus as $status) {
			switch ($status) {
				case RequestLog::STATUS_UNAPPROVED:
					$logs->{$this->conditionWhere($first)}(function ($query) {
						$query->where('active', true)
							->where('route_status_id', RequestLog::STATUS_UNAPPROVED);
					});
					$first = false;
					break;
				case RequestLog::STATUS_OPERATION_MANAGER_APPROVED:
					$this->actionAndStatusFilter($logs, $first,
						$search, $reject, RequestLog::STATUS_OPERATION_MANAGER_APPROVED);
					$first = false;
					break;
				case RequestLog::STATUS_F_APPROVED :
					$this->actionAndStatusFilter($logs, $first, $search, $reject);
					$first = false;
					break;
				case RequestLog::STATUS_F_APPROVED_FINAL :
					$logs->{$this->conditionWhere($first)}(function ($query) {
						$query->where('active', true)
							->where('route_status_id', RequestLog::STATUS_F_APPROVED)
							->whereColumn('route_action_id', '=', 'actual_route_status_id');
					});
					$first = false;
					break;
			}
		}

	}


	/**
	 * @param $logs
	 * @param $first
	 * @param $search
	 * @param $reject
	 * @param int $routeStatusId
	 * @return void
	 */
	private function actionAndStatusFilter($logs, $first, $search, $reject, int $routeStatusId = RequestLog::STATUS_F_APPROVED): void
	{
		$logs->{$this->conditionWhere($first)}(function ($query) use ($reject, $routeStatusId, $search) {
			$query->where('active', true)
				->where('route_status_id', $routeStatusId)
				->where(function ($q) use ($reject, $search) {
					if ($search) {
						$this->allowReject($q, $reject);
					} else {
						$q->whereNull('actual_route_status_id');
					}
				});
		});
	}


	/**
	 * @param $q
	 * @param $reject
	 * @return void
	 */
	private function allowReject($q, $reject): void
	{

		if ($reject) {
			$q->whereColumn('route_action_id', '!=', 'actual_route_status_id')
				->orWhereNull('actual_route_status_id');
		} else {
			$q->whereNull('actual_route_status_id');
		}
	}

	/**
	 * @param bool $first
	 * @return string
	 */
	protected function conditionWhere(bool $first = false): string
	{
		return $first ? 'where' : 'orWhere';
	}
}

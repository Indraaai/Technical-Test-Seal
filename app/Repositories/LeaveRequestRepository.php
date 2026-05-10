<?php

namespace App\Repositories;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveRequestRepository
{
    public function getByUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return LeaveRequest::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return LeaveRequest::query()
            ->with(['user', 'reviewer'])
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): LeaveRequest
    {
        return LeaveRequest::create($data);
    }

    public function findById(int $id): ?LeaveRequest
    {
        return LeaveRequest::query()
            ->with(['user', 'reviewer'])
            ->find($id);
    }

    public function getApprovedDaysByUserAndYear(User $user, int $year): int
    {
        return (int) LeaveRequest::query()
            ->where('user_id', $user->id)
            ->where('status', LeaveRequest::STATUS_APPROVED)
            ->whereYear('start_date', $year)
            ->sum('total_days');
    }

    public function update(LeaveRequest $leaveRequest, array $data): LeaveRequest
    {
        $leaveRequest->update($data);

        return $leaveRequest->refresh();
    }
}

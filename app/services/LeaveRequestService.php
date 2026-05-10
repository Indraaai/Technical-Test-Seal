<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Repositories\LeaveRequestRepository;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class LeaveRequestService
{
    private const MAX_LEAVE_DAYS_PER_YEAR = 12;

    public function __construct(
        private readonly LeaveRequestRepository $leaveRequestRepository
    ) {}

    public function getUserLeaveRequests(User $user): mixed
    {
        return $this->leaveRequestRepository->getByUser($user);
    }

    public function getAllLeaveRequests(): mixed
    {
        return $this->leaveRequestRepository->getAll();
    }

    public function getLeaveRequestById(int $id): LeaveRequest
    {
        $leaveRequest = $this->leaveRequestRepository->findById($id);

        if (!$leaveRequest) {
            throw ValidationException::withMessages([
                'leave_request' => ['Pengajuan cuti tidak ditemukan.'],
            ]);
        }

        return $leaveRequest;
    }

    public function create(User $user, array $data, UploadedFile $attachment): LeaveRequest
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $year = $startDate->year;

        $usedDays = $this->leaveRequestRepository
            ->getApprovedDaysByUserAndYear($user, $year);

        $remainingDays = self::MAX_LEAVE_DAYS_PER_YEAR - $usedDays;

        if ($totalDays > $remainingDays) {
            throw ValidationException::withMessages([
                'quota' => [
                    "Kuota cuti tidak cukup. Sisa kuota kamu {$remainingDays} hari."
                ],
            ]);
        }

        $attachmentPath = $attachment->store('leave-attachments', 'public');

        return $this->leaveRequestRepository->create([
            'user_id' => $user->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_days' => $totalDays,
            'reason' => $data['reason'],
            'attachment_path' => $attachmentPath,
            'status' => LeaveRequest::STATUS_PENDING,
        ]);
    }

    public function approve(LeaveRequest $leaveRequest, User $admin): LeaveRequest
    {
        $this->ensureLeaveRequestIsPending($leaveRequest);

        return $this->leaveRequestRepository->update($leaveRequest, [
            'status' => LeaveRequest::STATUS_APPROVED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function reject(LeaveRequest $leaveRequest, User $admin, string $reason): LeaveRequest
    {
        $this->ensureLeaveRequestIsPending($leaveRequest);

        return $this->leaveRequestRepository->update($leaveRequest, [
            'status' => LeaveRequest::STATUS_REJECTED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    private function ensureLeaveRequestIsPending(LeaveRequest $leaveRequest): void
    {
        if ($leaveRequest->status !== LeaveRequest::STATUS_PENDING) {
            throw ValidationException::withMessages([
                'status' => ['Pengajuan cuti ini sudah diproses.'],
            ]);
        }
    }
}

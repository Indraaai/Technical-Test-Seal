<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\RejectLeaveRequestRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Services\LeaveRequestService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLeaveRequestController extends Controller
{
    public function __construct(
        private readonly LeaveRequestService $leaveRequestService
    ) {}

    public function index(): JsonResponse
    {
        $leaveRequests = $this->leaveRequestService->getAllLeaveRequests();

        return ApiResponse::success(
            LeaveRequestResource::collection($leaveRequests),
            'Data semua pengajuan cuti berhasil diambil.'
        );
    }

    public function show(int $id): JsonResponse
    {
        $leaveRequest = $this->leaveRequestService->getLeaveRequestById($id);

        return ApiResponse::success(
            new LeaveRequestResource($leaveRequest),
            'Detail pengajuan cuti berhasil diambil.'
        );
    }

    public function approve(Request $request, int $id): JsonResponse
    {
        $leaveRequest = $this->leaveRequestService->getLeaveRequestById($id);

        $approvedLeaveRequest = $this->leaveRequestService->approve(
            $leaveRequest,
            $request->user()
        );

        return ApiResponse::success(
            new LeaveRequestResource($approvedLeaveRequest->load(['user', 'reviewer'])),
            'Pengajuan cuti berhasil disetujui.'
        );
    }

    public function reject(RejectLeaveRequestRequest $request, int $id): JsonResponse
    {
        $leaveRequest = $this->leaveRequestService->getLeaveRequestById($id);

        $rejectedLeaveRequest = $this->leaveRequestService->reject(
            $leaveRequest,
            $request->user(),
            $request->validated()['rejection_reason']
        );

        return ApiResponse::success(
            new LeaveRequestResource($rejectedLeaveRequest->load(['user', 'reviewer'])),
            'Pengajuan cuti berhasil ditolak.'
        );
    }
}

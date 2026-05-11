<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\StoreLeaveRequestRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Services\LeaveRequestService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function __construct(
        private readonly LeaveRequestService $leaveRequestService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $leaveRequests = $this->leaveRequestService->getUserLeaveRequests(
            $request->user()
        );

        return ApiResponse::success(
            LeaveRequestResource::collection($leaveRequests),
            'Data pengajuan cuti berhasil diambil.'
        );
    }

    public function store(StoreLeaveRequestRequest $request): JsonResponse
    {
        $leaveRequest = $this->leaveRequestService->create(
            $request->user(),
            $request->validated(),
            $request->file('attachment')
        );

        return ApiResponse::success(
            new LeaveRequestResource($leaveRequest->load(['user', 'reviewer'])),
            'Pengajuan cuti berhasil dibuat.',
            201
        );
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $leaveRequest = $this->leaveRequestService->getLeaveRequestById($id);

        if ($leaveRequest->user_id !== $request->user()->id) {
            return ApiResponse::error(
                'Kamu tidak memiliki akses ke pengajuan cuti ini.',
                403
            );
        }

        return ApiResponse::success(
            new LeaveRequestResource($leaveRequest),
            'Detail pengajuan cuti berhasil diambil.'
        );
    }
}

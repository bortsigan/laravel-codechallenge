<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\VoucherCodeService;
use App\Models\VoucherCode;
use App\Http\Resources\VoucherCodeResource;


use Exception;

class VoucherController extends Controller
{
    private VoucherCodeService $voucherCodeService;
    private $user;

    public function __construct(VoucherCodeService $voucherCodeService) 
    {
        $this->voucherCodeService = $voucherCodeService;
    }

    /**
     * 
     * Can view
     * 
     * @return JsonResponse
     */
    public function index(): AnonymousResourceCollection
    {
        $vouchers = $this->voucherCodeService->getUserVouchers(auth('sanctum')->user());

        return VoucherCodeResource::collection($vouchers);
    }

    /**
     * Can delete
     * 
     * @param \App\Models\VoucherCode $voucherCode
     * @throws \Exception
     * @return JsonResponse|mixed
     */
    public function destroy(VoucherCode $voucherCode): JsonResponse
    {
        try {
            $this->voucherCodeService->deleteUserVoucher(auth('sanctum')->user(), $voucherCode);

            return response()->json(['message' => 'Resource deleted.'], 204);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Authenticated user can generate more unique voucher codes
     * 
     * @return JsonResponse|mixed
     */
    public function generate(): JsonResponse
    {
        $voucher = $this->voucherCodeService->generateUniqueCode(auth('sanctum')->user());

        if (!$voucher) {
            return response()->json(['message' => 'Limit reached.'], 422);
        }

        return response()->json(['code' => $voucher->code], 200);
    }
}

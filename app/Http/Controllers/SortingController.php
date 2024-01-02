<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SortingFactory;

class SortingController extends BaseController
{
    public function sortApplicants(Request $request)
    {
        return $this->tryCatch(function () use ($request) {
            $sorter = SortingFactory::make($request->input('applicants'));
            $sorter->sort($request->input('ids'));
            return response()->json([
                'status' => true,
                'message' => "Record Sorted Successfully",
            ]);
        });
    }
}
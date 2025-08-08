<?php

namespace App\Http\Controllers;

use App\Models\SheetRow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RowController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        $sheets = SheetRow::all();

        return response()->json($sheets);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('row/Create');
    }

    /**
     * @param SheetRow $row
     * @return Response
     */
    public function edit(SheetRow $row): Response
    {
        return Inertia::render('row/Edit', compact('row'));
    }

    /**
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $request->validate([
            'status' => 'required|string',
            'data' => 'array|required',
            'data.*.label' => 'required|string',
            'data.*.value' => 'required|string',
        ]);

        $rows = [];
        foreach($request->get('data') as $dataRow) {
            $rows[$dataRow['label']] = $dataRow['value'];
        }

        $sheetsData = new SheetRow();
        $sheetsData->status = $request->get("status");
        $sheetsData->data = $rows;
        $sheetsData->save();
    }

    /**
     * @param Request $request
     * @param SheetRow $row
     * @return JsonResponse
     */
    public function changeStatus(Request $request, SheetRow $row): JsonResponse
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $row->update([
            'status' => request('status')
        ]);

        return response()->json(['status' => 'success']);
    }

    public function destroy(SheetRow $row): JsonResponse
    {
        $row->delete();
        return response()->json(['status' => 'success']);
    }
}

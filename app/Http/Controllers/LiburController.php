<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;

class LiburController extends Controller
{
    public function index()
    {
        return view('libur.index');
    }
    public function get(Request $request, $mode = '')
    {
        if (empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if ($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search,
        ];

        if ($mode == "google-calendar") {
            $res = API::get('calendar/google-api/holidays');
            $body = $res->getBody()->getContents();
            $content = json_decode($body);
            $sources = $content->data;

            $rows = !empty($sources) ? $sources : [];
            $data = [];
            $no = 1 + $request->start;

            foreach ($rows as $row) {
                $is_checked = "";
                if ($row->is_exist == true) {
                    $is_checked = "checked";
                }
                $d = [];
                $d[] = '<input id="cb$no" type="checkbox" style="width: 20px; height: 20px; !important" data-nama_libur="'.$row->nama_libur.'" data-tanggal="'.$row->tanggal.'" '.$is_checked.' />';
                $d[] = $row->nama_libur;
                $d[] = $row->tanggal;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = count($sources);
            $result['recordsFiltered'] = count($sources);

            return response()->json($result);
        }

        $res = API::get('libur', $body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        if (!@$content->data) {
            $result['data'] = 0;
            $result['recordsTotal'] = 0;
            $result['recordsFiltered'] = 0;

            return response()->json($result);
        }
        $sources = $content->data;
        $rows = !empty($sources) ? $sources->rows : [];

        if ($mode == 'datatable') {
            $data = [];
            $no = 1 + $request->start;
            $test = '';
            foreach ($rows as $row) {
                $d = [];
                $d[] = $no;
                $d[] = $row->nama_libur;
                $d[] = \Carbon\Carbon::parse($row->tanggal)->format('d M Y');
                $buttons = '<a href="' . route('libur.edit', [$row->id_libur]) . '" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>
                            <a href="' . route('libur.destroy', [$row->id_libur]) . '" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"></i> </a>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if ($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Sistem Kerja'];
            foreach ($rows as $row) {
                $result['data'][] = [
                    'id' => $row->id_libur,
                    'text' => $row->nama_libur
                ];
            }
        }

        return response()->json($result);
    }

    public function create()
    {
        return view('libur.form');
    }

    public function edit($id)
    {
        $res = API::get('libur/' . $id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if ($data->code == 200) {
            return view('libur.form')->with([
                'libur' => $data->data
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $custom_msg = [
            'nama_libur.required' => 'Nama sistem kerja harus diisi!.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal salah',
        ];

        $validation = \Validator::make($input, [
            "nama_libur" => "required",
            "tanggal" => "required",
        ], $custom_msg);

        if ($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());

        $res = API::post('libur/create', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if ($data->code == 200) {
            return redirect()->route('libur.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $custom_msg = [
            'nama_libur.required' => 'Nama sistem kerja harus diisi!.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal salah.',
        ];

        $validation = \Validator::make($input, [
            "nama_libur" => "required",
            "tanggal" => "required",
        ], $custom_msg);

        if ($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());

        $res = API::post('libur/' . $id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if ($data->code == 200) {
            return redirect()->route('libur.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function destroy($id)
    {
        $res = API::delete('libur/' . $id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        return response()->json([
            'code' => $data->code,
            'message' => $data->message,
            'error' => $data->code == 200 ? false : true,
            'error_api' => $data->code == 200 ? null : $data->message,
            'errors_api' => $data->code == 200 ? [] : $data->errors
        ]);
    }

    public function store_bulk(Request $request) {
        $inputs = $request->all();

        $res = API::post('libur/store/bulk', ["libur" => $inputs]);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);

        if ($data->code == 200) {
            return response()->json($data);
        } else {
            return response()->json($data, $data->code);
        }

    }
}

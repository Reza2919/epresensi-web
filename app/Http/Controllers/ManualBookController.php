<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;

class ManualBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manual-book.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manual-book.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'description' => 'required',
        ]);

        $res = API::post('manual-book/create', [
            'name' => $request->name,
            'url' => $request->url,
            'description' => $request->description,
        ]);

        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return redirect()->to('manual-book')->with('success', "Berhasil");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = API::get('manual-book/'.$id.'/byid');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        $data['value'] = $sources;



        return view('manual-book.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'description' => 'required',
        ]);

        $res = API::post('manual-book/'.$id, [
            'name' => $request->name,
            'url' => $request->url,
            'description' => $request->description,
        ]);

        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return redirect()->to('manual-book')->with('success', "Berhasil");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getListManualBook(Request $request) {
        $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search,
        ];

        $res = API::get('manual-book', $body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        $rows = !empty($sources) ? $sources->rows : [];

        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;

            foreach($rows as $row){
                $d = [];
                $d[] = $no;
                $d[] = $row->name;
                $d[] = $row->url;
                $d[] = $row->description;
                $buttons = '<a href="'. url('manual-book/'.$row->id.'/edit') .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>';
                $buttons .= '
                    <a href="'. url('manual-book/destroy/'.$row->id) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"> </i> </a>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_kategori,
                    'text' => $row->kategori
                ];
            }
        }
        return response()->json($result);
    }

    public function destroyManualBook(Request $request, $id) {
        $res = API::delete('manual-book/'.$id);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        return redirect()->to('manual-book')->with('success', "Berhasil");
    }
}

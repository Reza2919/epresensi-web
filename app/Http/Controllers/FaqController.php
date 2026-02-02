<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index() {
        $res = API::get('faq');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;


        $arr = array();

        foreach ($sources->rows as $key => $item) {
            $arr[$item->faq_kategori->kategori][$key] = $item;
        }

        ksort($arr, SORT_NUMERIC);

        $res = API::get('manual-book', []);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        $rows = !empty($sources) ? $sources->rows : [];

        return view('faq.index', [
            'list' => $arr,
            'manual' => $rows
        ]);
    }

    public function listFAQ() {
        return view('faq.list');
    }

    public function listKategori() {
        return view('faq.list-kategori');
    }

    public function form() {
        return view('faq.form');
    }

    public function getListFaq(Request $request, $mode = '') {
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search,
        ];

        $res = API::get('faq', $body);
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
                $d[] = $row->faq_kategori->kategori;
                $d[] = $row->question;
                $d[] = strip_tags($row->answer);
                $buttons = '<a href="'. route('faq.edit', [$row->id_faq]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>';
                $buttons .= '
                    <a href="'. route('faq.destroy', [$row->id_faq]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"> </i> </a>';

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

    public function submitFaq(Request $request) {
//        dd($request->all());
        $res = API::post('faq/create', [
            'id_kategori' => $request->kategori,
            'question' => $request->pertanyaan,
            'answer' => $request->jawaban,
        ]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return redirect()->to('setting/faq')->with('success', "Berhasil");
    }

    public function updateFaq(Request $request, $id) {

        $res = API::post('faq/'.$id, [
            'id_kategori' => $request->kategori,
            'question' => $request->pertanyaan,
            'answer' => $request->jawaban,
        ]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return redirect()->to('setting/faq')->with('success', "Berhasil");
    }

    public function destroyFaq(Request $request, $id) {
        $res = API::delete('faq/'.$id);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        return redirect()->to('setting/faq')->with('success', "Berhasil");
    }

    public function editFaq($id) {
        $res = API::get('faq/'.$id.'/byid');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return view('faq.form', [
            'value' => $sources
        ]);
    }

    public function formKategori() {
        return view('faq.form-kategori');
    }

    public function editKategori($id) {
        $res = API::get('faq-kategori/'.$id);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;
        return view('faq.form-kategori', [
            'value' => $sources
        ]);
    }

    public function submitKategori(Request $request) {
        $res = API::post('faq-kategori/create', [
            'kategori' => $request->kategori
        ]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return redirect()->to('setting/kategori')->with('success', "Berhasil");
    }

    public function updateKategori(Request $request, $id) {
        $res = API::post('faq-kategori/'.$id, [
            'kategori' => $request->kategori
        ]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return redirect()->to('setting/kategori')->with('success', "Berhasil");
    }

    public function getKategori(Request $request, $mode = '') {
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search,
        ];

        $res = API::get('faq-kategori', $body);
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
                $d[] = $row->kategori;
                $buttons = '<a href="'. route('kategori.edit', [$row->id_kategori]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>';
                $buttons .= '
                    <a href="'. route('kategori.destroy', [$row->id_kategori]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"> </i> </a>';

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

    public function destroyKategori(Request $request, $id) {
        $res = API::delete('faq-kategori/'.$id);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        return redirect()->to('setting/kategori')->with('success', "Berhasil");
    }
}

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

        if (!$content || !isset($content->data)) {
            return view('faq.index', [
                'list' => [],
                'manual' => []
            ]);
        }

        $sources = $content->data ?? null;

        $arr = [];

        foreach ($sources->rows ?? [] as $item) {

            $kategori = optional($item->faq_kategori)->kategori ?? 'Uncategorized';

            $arr[$kategori][] = $item;
        }

        ksort($arr);

        // manual book
        $res = API::get('manual-book', []);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        $sources = $content->data ?? null;
        $rows = $sources->rows ?? [];

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

        $search = $request->search;
        $length = $request->length;
        $start  = $request->start;

        $res = API::get('faq');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        if (!$content || !isset($content->data)) {
            return response()->json([
                'draw' => intval($request->draw ?? 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        $sources = $content->data ?? null;
        $rows = $sources->rows ?? [];
        $count = $sources->count ?? 0;

        if($mode == 'datatable'){

            $data = [];
            $no = 1 + $start;

            foreach($rows as $row){

                $data[] = [
                    $no++,
                    optional($row->faq_kategori)->kategori ?? 'Uncategorized',
                    $row->question ?? '-',
                    strip_tags($row->answer ?? '-'),
                    '<a href="'.route('faq.edit', $row->id_faq).'" class="btn btn-sm btn-info">Edit</a>
                     <a href="'.route('faq.destroy', $row->id_faq).'" class="btn btn-sm btn-danger btn-delete">Hapus</a>'
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        }

        return response()->json([
            'data' => []
        ]);
    }

    public function submitFaq(Request $request) {

        API::post('faq/create', [
            'id_kategori' => $request->kategori,
            'question' => $request->pertanyaan,
            'answer' => $request->jawaban,
        ]);

        return redirect('setting/faq')->with('success', "Berhasil");
    }

    public function updateFaq(Request $request, $id) {

        API::post('faq/'.$id, [
            'id_kategori' => $request->kategori,
            'question' => $request->pertanyaan,
            'answer' => $request->jawaban,
        ]);

        return redirect('setting/faq')->with('success', "Berhasil");
    }

    public function destroyFaq($id) {

        API::delete('faq/'.$id);

        return redirect('setting/faq')->with('success', "Berhasil");
    }

    public function editFaq($id) {

        $res = API::get('faq/'.$id.'/byid');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        return view('faq.form', [
            'value' => $content->data ?? null
        ]);
    }

    public function formKategori() {
        return view('faq.form-kategori');
    }

    public function editKategori($id) {

        $res = API::get('faq-kategori/'.$id);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        return view('faq.form-kategori', [
            'value' => $content->data ?? null
        ]);
    }

    public function submitKategori(Request $request) {

        API::post('faq-kategori/create', [
            'kategori' => $request->kategori
        ]);

        return redirect('setting/kategori')->with('success', "Berhasil");
    }

    public function updateKategori(Request $request, $id) {

        API::post('faq-kategori/'.$id, [
            'kategori' => $request->kategori
        ]);

        return redirect('setting/kategori')->with('success', "Berhasil");
    }

    public function getKategori(Request $request, $mode = '') {

        $res = API::get('faq-kategori');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        if (!$content || !isset($content->data)) {
            return response()->json([
                'data' => []
            ]);
        }

        $sources = $content->data ?? null;
        $rows = $sources->rows ?? [];

        $data = [];

        foreach($rows as $row){
            $data[] = [
                'id' => $row->id_kategori ?? 0,
                'text' => $row->kategori ?? ''
            ];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function destroyKategori($id) {

        API::delete('faq-kategori/'.$id);

        return redirect('setting/kategori')->with('success', "Berhasil");
    }
}
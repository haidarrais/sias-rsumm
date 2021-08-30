<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Outbox;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OutboxController extends Controller
{
    private $pathImage = "upload/surat-masuk";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outboxes = Outbox::all();
        $types = Type::all();

        return view('pages.outbox.index', compact('outboxes', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'uploadfile' => 'required',
        ]);
        $files = $request->file('uploadfile');
        $fileName = $files->hashName();
        $files->move($this->pathImage,$fileName);
        // $store = $fileName->store($this->pathImage.time());
        Outbox::create([
            'user_id' => $request->user_id,
            'journal_id' => $request->journal_id,
            'outbox_number' => $request->outbox_number,
            'sender' => $request->sender,
            'destination' => $request->destination,
            'regarding' => $request->regarding,
            'entry_date' => $request->entry_date,
            'outbox_origin' => $request->outbox_origin,
            'type_id' => $request->type,
            'notes' => $request->notes,
            'status' => 0,
            'file' => $fileName
        ]);
        return redirect('/outbox');
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
        $outbox = Outbox::where('id', $id)->first();
        if ($request->file('uploadfile')) {
            $file = $outbox->file;
            $filename = $this->pathImage.'/' . $file;
            File::delete($filename);
            
            $files = $request->file('uploadfile');
            $fileName = $files->hashName();
            $files->move($this->pathImage , $fileName);
        }
        $newoutbox = [
            'user_id' => $request->user_id,
            'journal_id' => $request->journal_id,
            'outbox_number' => $request->outbox_number,
            'sender' => $request->sender,
            'destination' => $request->destination,
            'regarding' => $request->regarding,
            'entry_date' => $request->entry_date,
            'outbox_origin' => $request->outbox_origin,
            'type_id' => $request->type,
            'notes' => $request->notes,
            'status' => 0,
            'file' =>  $request->file('uploadfile')?$fileName:$outbox->file
        ];
        $outbox->update($newoutbox);
        return redirect('/outbox');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $outbox = Outbox::where('id', $id)->first();
        $file = $outbox->file;
        $filename = $this->pathImage.'/' . $file;
        File::delete($filename);


        $outbox->delete();
        return redirect('/outbox');
    }
}
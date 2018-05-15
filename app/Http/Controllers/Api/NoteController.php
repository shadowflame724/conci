<?php

namespace App\Http\Controllers\Api;

use App\Repositories\NoteRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    /**
     * @var NoteRepository
     */
    protected $repository;

    public function __construct(NoteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll()
    {
        $notes = $this->repository->all(['id', 'title', 'description']);

        return \Response::json([
            'status' => 1,
            'data' => $notes], 302);
    }

    public function create(Request $request)
    {
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->only('title', 'description'), [
            'title' => 'required|string|max:255',
            'description' => 'string|min:6'
        ]);

        if ($valid->fails()) {
            return \Response::json([
                'status' => 0,
                'error' => $valid->errors()->all()], 400);
        }

        $data = request()->only('title', 'description');

        try {
            $note = $this->repository->create([
                'title' => $data['title'],
                'description' => $data['description']
            ]);
        } catch (\Exception $exception) {
            return \Response::json([
                'status' => 0,
                'error' => $exception->getMessage()], $exception->getCode());
        }

        return \Response::json([
            'status' => 1,
            'data' => $note->id], 201);

    }

    public function update(Request $request)
    {
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->only('title', 'description'), [
            'note_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'string|min:6'
        ]);

        if ($valid->fails()) {
            return \Response::json([
                'status' => 0,
                'error' => $valid->errors()->all()], 400);
        }

        $data = request()->only('note_id', 'title', 'description');
        $note = $this->repository->find($data['note_id']);

        try {
            $note = $this->repository->update([
                'title' => $data['title'],
                'description' => $data['description']
            ], $note->id);
        } catch (\Exception $exception) {
            return \Response::json([
                'status' => 0,
                'error' => $exception->getMessage()], $exception->getCode());
        }

        return \Response::json([
            'status' => 1,
            'data' => $note->id], 200);
    }
}

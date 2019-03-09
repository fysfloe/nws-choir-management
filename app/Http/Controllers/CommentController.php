<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Concert;
use App\Http\Requests\StoreComment;
use App\Http\Resources\CommentResource;
use App\Project;
use App\Rehearsal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];

        foreach ($request->all() as $key => $value) {
            $where[] = [$key, '=', $value];
        }

        $comments = Comment::where($where)
            ->with('commentable')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json(CommentResource::collection($comments));
    }

    /**
     * @param StoreComment $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreComment $request)
    {
        $input = $request->all();

        $type = $request->get('type');
        $parent = null;

        switch ($type) {
            case 'project':
                $parent = Project::find($request->get('commentable_id'));
                break;
            case 'rehearsal':
                $parent = Rehearsal::find($request->get('commentable_id'));
                break;
            case 'concert':
                $parent = Concert::find($request->get('commentable_id'));
                break;
            default:
                break;
        }

        $input['user_id'] = Auth::user()->id;

        $comment = $parent->comments()->create($input);

        return response()->json(new CommentResource($comment));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        $comment->delete();

        return response()->json();
    }
}

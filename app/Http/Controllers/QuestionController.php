<?php

namespace App\Http\Controllers;

use App\Helpet\Helper;
use App\Http\Requests\AnswerQuestionRequest;
use App\Http\Requests\AskQuestionRequest;
use App\Http\Requests\CloseOpenRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\DeleteQuestionRequest;
use App\Http\Requests\FavouriteQuestionRequest;
use App\Http\Requests\FilterByCategoryRequest;
use App\Http\Requests\ShowRequest;
use App\Http\Requests\SpecificQuestionRequest;
use App\Http\Requests\UpdateAskQuestion;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\UserResource;
use App\Models\Notification;
use App\Models\Question;
use App\Models\QuestionFavourite;
use App\Models\QuestionReads;
use App\Models\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\u;

class QuestionController extends Controller
{
    public function index(ShowRequest $request)
    {
        // no policy needed
        $page = $request->has('page') ? $request->page : 1;
        if ($request->has('search')) {
            $data = Question::where('name', 'like', '%' . $request->search . '%')->paginate(8);
        }else{
            $data = Question::paginate(8);
        }
        $questions =QuestionResource::collection($data);

        if ($questions->isEmpty()){
            return response()->json([
                'status' => 'failed',
                'message'=> 'no questions to get ',
            ]);
        }

        return Helper::responseData('Retrieved', true,$questions, Response::HTTP_OK);
    }

    public function answerToQuestion(AnswerQuestionRequest $request) // .. this used for answer and edit answer also ..
    {
        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('update-question')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $question = Question::find($request->question_id);

        $input ['answer']  = $request->answer;
        $input ['answered_by'] = $user->id;
        $userId = $question->user_id;
        $question_update = $question->update($input);
        if ($question_update){
            $notification = Notification::create([
                'user_id'=>$user->id,
                'message'=>'One Of Admins Answered a Question',
                'title'=>'Answer',
                'type' => Question::class,
                'related_id' => $question->id,
            ]);
            DB::table('notification_users')->insert([
                'user_id'=>$userId,
                'notification_id'=> $notification->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $question_new = Question::find($request->question_id);
        return Helper::responseData('Updated', true,QuestionResource::make($question_new), Response::HTTP_CREATED);
    }

    public function askQuestion(AskQuestionRequest $request)
    {
        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('create-question')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $user = auth('sanctum')->user();
        $input ['title'] = $request->title;
        $input ['question'] = $request->question;
        $input ['category_id'] = $request->category_id;
        $input ['user_id'] = $user->id;
        $question = Question::create($input);
        return Helper::responseData('New Ask Created', true,QuestionResource::make($question), Response::HTTP_CREATED);
    }

    public  function updateAskQuestion(UpdateAskQuestion $request)
    {
        // policy
        $user = auth('sanctum')->user();
        $question = Question::find($request->question_id);
        if (!$user->hasPermissionTo('update-question')){
            if (!$user->id == $question->user_id){
                return  Helper::responseData('Not Allowed', true, 301);
            }
        }
        $input ['title'] = $request->title;
        $input ['question'] = $request->question;
        $question_update = $question->update([
            'title' => $input['title'],
            'question' => $input['question'],
        ]);
        $question = Question::find($request->question_id);
        return Helper::responseData('New Ask Updated', true,QuestionResource::make($question), Response::HTTP_CREATED);
    }

    public function comment(CommentRequest $request)
    {
        $question = Question::find($request->question_id);

        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('create-question-comment')){
            if (!$user->id == $question->user_id or !$user->id == $question->answered_by){
                return  Helper::responseData('Not Allowed', true, 301);
            }
        }
        // check if question were opened to reply or no
        if ($question->status == 'opened'){
            $addComment = DB::table('question_comment')
                ->insert([
                    'question_id'=>$request->question_id,
                    'comment' =>$request->comment,
                    'user_id' => $user->id,
                ]);
            if ($addComment){
                if ($user->role_id ==0){ // this means that authenticated is user . then notification to admin who answered
                    $adminId = $question->answered_by;
                    $adminName = User::where('id', $adminId)->select('name')->get();
                    Notification::create([
                        'user_id'=>$user->id,
                        'message'=>'User'.$adminName.'Replys To Your Answer',
                        'title'=>'Reply'
                    ]);
                }else{ // this means that authenticated is admin , then notification to user
                    $userId = $question->user_id;
                    $userName = User::where('id', $userId)->select('name')->get();
                    Notification::create([
                        'user_id'=>$request->user_id,
                        'message'=>'Admin'.$userName.'Replys To Your Question',
                        'title'=>'Answer'
                    ]);
                }
            }
            return  Helper::responseData('Comment Saved', true, Response::HTTP_OK);
        }else{
            return  Helper::responseData('Question are Closed', false);
        }
    }

    public function closeOpenQuestion(CloseOpenRequest $request)
    {
        $question = Question::find($request->question_id);
        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('update-question') && $user->hasAnyRole(['super-admin','admin'])){
            if (!$user->id == $question->answered_by){
                return  Helper::responseData('Not Allowed', true, 301);
            }
        }
        $input['status'] = $request->status;
        $question->update($input);
        if ($request->status == 'closed'){
            return  Helper::responseData('Question Closed', true);
        }else{
            return  Helper::responseData('Question Opened', true);
        }
    }
    public function show(SpecificQuestionRequest $request)
    {
        // no policy needed here
        $question = Question::find($request->id);
        $user = auth('sanctum')->user();
            DB::table('question_reads')->insert([
                'question_id' => $question->id,
                'user_id' => $user->id ?? null ,
            ]);
        $question = Question::where('id', $request->id)->first();
        if (!$question){
            return  Helper::responseData('Failed', false, 404);
        }
        $addRead = QuestionReads::create([
            'user_id'=>$user->id ,
            'question_id'=>$question->id,
        ]);
        $countReads = DB::table('question_reads')->where('question_id',$question->id)->count();
        $questionResource = new QuestionResource($question);
        $questionResource->additional(['count_reads' => $countReads]);
        $question['count_reads'] = $countReads;
        return  Helper::responseData('Retrieved', true,QuestionResource::make($question), Response::HTTP_OK);
    }

    public function destroy(DeleteQuestionRequest $request)
    {
        $question = Question::find($request->id);
        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasAnyRole(['admin','super-admin'] || !$user->id == $question->user_id &&$user->hasPermissionTo('delete-question'))){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $question->delete();
        return  Helper::responseData('Deleted', true,null, Response::HTTP_OK);
    }

    public function fetchByCategory(FilterByCategoryRequest $request)
    {
        // no policy needed here
        $categoryFilter = Question::where('category_id', $request->category_id)->get();
        if ($categoryFilter->isEmpty()){
            return  Helper::responseData('Failed', false, 404);
        }
        return  Helper::responseData('Retrieved', true,$categoryFilter, Response::HTTP_OK);
    }

    public function favoriteQuestion(FavouriteQuestionRequest $request)
    {
        // policy
        $user = auth('sanctum')->user();
        if (!$user){
            return  Helper::responseData('Log in to add it', false, 404);
        }
        $pivotFavourite = QuestionFavourite::create([
            'user_id' => $user->id,
            'question_id' => $request->question_id,
        ]);
        if ($pivotFavourite){
            return  Helper::responseData('Question Added To Favourites', true, Response::HTTP_OK);
        }
    }

    public function questionReaders(SpecificQuestionRequest $request)
    {
        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('question-reads-read')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $question = Question::find($request->id);

        if ($question){
            $readersIds = QuestionReads::where('question_id', $question->id)->pluck('user_id');
            $readers = User::whereIn('id',$readersIds)->get();
            return  Helper::responseData('Retrieved',
                true,UserResource::make($readers),
                Response::HTTP_OK
            );
        }
    }
}

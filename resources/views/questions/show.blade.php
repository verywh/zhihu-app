@extends('layouts.app')

@section('content')
    @include('vendor.ueditor.assets')
    <div class="question-panel">
        <div class="question-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-1">
                        <div class="question-panel-main">
                            <div class="question-panel-topics">
                                @foreach($question->topics as $topic)
                                    <a class="topic" href="/topic/{{$topic->id}}">{{$topic->name}}</a>
                                @endforeach
                            </div>
                            <h1 class="question-panel-title">
                                {{$question->title}}
                            </h1>
                            <div class="question-panel-detail">
                                {!! $question->body !!}
                            </div>
                            <div class="question-panel-footer">
                                <div class="question-panel-inner">
                                <comments type="question"
                                          model="{{$question->id}}"
                                          count="{{$question->comments()->count()}}">
                                </comments>

                                @if(Auth::check() && Auth::user()->owns($question))
                                    <div class="ui action-buttons">
                                        <a href="/questions/{{$question->id}}/edit" class="ui basic button green action-btn">编辑</a>
                                        <form href="/questions/{{$question->id}}" method="post" class="delete-form action-btn">
                                            {{method_field('DELETE')}}
                                            {!! csrf_field() !!}
                                            <a class="ui basic button red">删除</a>
                                        </form>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                            <div class="question-follow">
                                <h2>{{ $question->followers_count }}</h2>
                                <span>关注者</span>
                            </div>
                            <div class="">
                                <question-follow-button question="{{$question->id}}"></question-follow-button>
                                <a href="#editor" class="btn btn-primary pull-right">
                                    撰写答案
                                </a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{$question->answers_count}}个答案
                    </div>

                    <div class="panel-body">

                        @foreach($question->answers as $answer)
                            <div class="media answer-item">
                                <div class="media-body">
                                    <div class="answer-info-avatar">
                                        <img src="{{\Auth::user()->avatar}}">
                                    </div>
                                    <h4 class="media-heading answer-item-name">
                                        <a href="/questions/{{$answer->user->name}}">
                                            {{$answer->user->name}}
                                        </a>
                                    </h4>
                                    <div class="answer-item-content">
                                        {!! $answer->body !!}
                                    </div>
                                </div>
                                    <div class="answer-item-time">
                                    发布于 2017-02-05
                                </div>
                                <div class="answer-item-actions">
                                    <user-vote-button
                                            answer="{{$answer->id}}"
                                            count="{{$answer->votes_count}}"
                                    >
                                    </user-vote-button>
                                    <comments type="answer"
                                              model="{{$answer->id}}"
                                              count="{{$answer->comments()->count()}}">
                                    </comments>
                                    <div class="answer-item-action">
                                        <i class="fa fa-paper-plane fa-icon-sm"></i>
                                        分享
                                    </div>
                                    <div class="answer-item-action">
                                        <i class="fa fa-star fa-icon-sm"></i>
                                        收藏
                                    </div>
                                    <div class="answer-item-action">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading about-author">
                        <h5>关于作者</h5>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img width="36" src="{{$question->user->avatar}}" alt="{{$question->user->name}}">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="">
                                        {{$question->user->name}}
                                    </a>
                                </h4>
                            </div>
                            <div class="user-statics">
                                <div class="statics-item text-center">
                                    <div class="statics-text">问题</div>
                                    <div class="statics-count">
                                        {{$question->user->questions_count}}
                                    </div>
                                </div>

                                <div class="statics-item text-center">
                                    <div class="statics-text">回答</div>
                                    <div class="statics-count">
                                        {{$question->user->answers_count}}
                                    </div>
                                </div>
                                <div class="statics-item text-center">
                                    <div class="statics-text">关注者</div>
                                    <div class="statics-count">
                                        {{$question->user->followers_count}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <user-follow-button user="{{$question->user->id}}"></user-follow-button>
                        <send-message user="{{$question->user->id}}"></send-message>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    @if(Auth::check())
                    <div class="panel-heading">
                        <div class="answer-info-panel">
                            <div class="answer-info-avatar">
                                <img src="{{\Auth::user()->avatar}}">
                            </div>
                            <div class="answer-content">{{\Auth::user()->name}}</div>
                        </div>
                    </div>
                    @endif
                    <div class="panel-body">
                        @if(Auth::check())
                            <form action="/questions/{{$question->id}}/answer" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                    <ueditor></ueditor>
                                    @if ($errors->has('body'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="ui button teal pull-right">提交答案</button>
                            </form>
                        @else
                            <a href="/login" class="btn btn-success btn-block">登录提交答案</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
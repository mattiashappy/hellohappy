@extends('sendportal::layouts.app')

@section('heading')
    {{ __('Create Sequence') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">{{ __('Sequence Settings') }}</div>
                <div class="card-body">
                    <form action="{{ route('sequences.store') }}" method="post">
                        @csrf
                        @include('sequences.form', ['sequence' => new \App\Models\EmailSequence(), 'selectedTagId' => null])
                        <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Save') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

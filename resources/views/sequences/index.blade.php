@extends('sendportal::layouts.app')

@section('heading')
    {{ __('Email Sequences') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-table table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Emails') }}</th>
                        <th>{{ __('Active Subscribers') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sequences as $sequence)
                        <tr>
                            <td>{{ $sequence->name }}</td>
                            <td>{{ $sequence->total_emails }}</td>
                            <td>{{ $sequence->active_subscribers_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">{{ __('No email sequences found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

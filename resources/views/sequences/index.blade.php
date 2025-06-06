@extends('sendportal::layouts.app')

@section('heading')
    {{ __('Email Sequences') }}
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{ route('sequences.create') }}" class="btn btn-primary btn-sm">+ {{ __('Create Sequence') }}</a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-table table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Trigger Tag') }}</th>
                        <th>{{ __('Emails') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sequences as $sequence)
                        <tr>
                            <td>{{ $sequence->name }}</td>
                            <td>{{ optional($sequence->tagMapping->tag)->name }}</td>
                            <td>{{ $sequence->total_emails }}</td>
                            <td>{{ $sequence->is_active ? __('Active') : __('Inactive') }}</td>
                            <td class="td-fit">
                                <a href="{{ route('sequences.edit', $sequence) }}" class="btn btn-light btn-sm">{{ __('Edit') }}</a>
                                <form action="{{ route('sequences.destroy', $sequence) }}" method="post" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Delete this sequence?') }}')">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('No email sequences found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

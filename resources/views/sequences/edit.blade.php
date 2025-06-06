@extends('sendportal::layouts.app')

@section('heading')
    {{ __('Edit Sequence') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card mb-3">
                <div class="card-header">{{ __('Sequence Settings') }}</div>
                <div class="card-body">
                    <form action="{{ route('sequences.update', $sequence) }}" method="post">
                        @csrf
                        @method('PUT')
                        @include('sequences.form', ['sequence' => $sequence, 'selectedTagId' => $sequence->tagMapping->tag_id ?? null])
                        <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Save') }}">
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">{{ __('Email Steps') }}</div>
                <div class="card-table table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Delay Days') }}</th>
                            <th>{{ __('Template') }}</th>
                            <th>{{ __('Order') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sequence->emails as $email)
                            <tr>
                                <td>{{ $email->subject }}</td>
                                <td>{{ $email->delay_days }}</td>
                                <td>{{ optional($email->template)->name }}</td>
                                <td>{{ $email->send_order }}</td>
                                <td class="td-fit">
                                    <form action="{{ route('sequences.steps.destroy', [$sequence, $email]) }}" method="post" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Remove this step?') }}')">{{ __('Remove') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ __('Add Email Step') }}</div>
                <div class="card-body">
                    <form action="{{ route('sequences.steps.store', $sequence) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="subject">{{ __('Email Subject') }}</label>
                            <input type="text" id="subject" name="subject" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="send_order">{{ __('Send Order') }}</label>
                            <input type="number" id="send_order" name="send_order" class="form-control" min="1" value="{{ $sequence->emails->count() + 1 }}">
                        </div>
                        <div class="form-group">
                            <label for="delay_days">{{ __('Delay in Days') }}</label>
                            <input type="number" id="delay_days" name="delay_days" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label for="template_id">{{ __('Select Template') }}</label>
                            <select id="template_id" name="template_id" class="form-control">
                                @foreach($templates as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Save') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

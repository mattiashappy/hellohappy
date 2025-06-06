<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\EmailSequence;
use App\Models\SequenceEmail;
use App\Models\TagEmailSequence;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sendportal\Base\Facades\Sendportal;
use Sendportal\Base\Models\Tag;
use Sendportal\Base\Models\Template;

class EmailSequencesController extends Controller
{
    public function index(): View
    {
        $sequences = EmailSequence::with('tagMapping.tag')
            ->where('workspace_id', Sendportal::currentWorkspaceId())
            ->get();

        return view('sequences.index', compact('sequences'));
    }

    public function create(): View
    {
        $tags = Tag::pluck('name', 'id');

        return view('sequences.create', [
            'tags' => $tags,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'tag_id' => ['required', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $sequence = EmailSequence::create([
            'workspace_id' => Sendportal::currentWorkspaceId(),
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        TagEmailSequence::create([
            'tag_id' => $data['tag_id'],
            'email_sequence_id' => $sequence->id,
        ]);

        return redirect()->route('sequences.edit', $sequence);
    }

    public function edit(EmailSequence $sequence): View
    {
        $sequence->load(['emails', 'tagMapping.tag']);
        $tags = Tag::pluck('name', 'id');
        $templates = Template::pluck('name', 'id');

        return view('sequences.edit', [
            'sequence' => $sequence,
            'tags' => $tags,
            'templates' => $templates,
        ]);
    }

    public function update(Request $request, EmailSequence $sequence): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'tag_id' => ['required', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $sequence->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        TagEmailSequence::updateOrCreate(
            ['email_sequence_id' => $sequence->id],
            ['tag_id' => $data['tag_id']]
        );

        return redirect()->route('sequences.edit', $sequence);
    }

    public function destroy(EmailSequence $sequence): RedirectResponse
    {
        $sequence->delete();

        return redirect()->route('sequences.index');
    }

    public function storeStep(Request $request, EmailSequence $sequence): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string'],
            'send_order' => ['required', 'integer', 'min:1'],
            'delay_days' => ['required', 'integer', 'min:0'],
            'template_id' => ['nullable', 'integer'],
        ]);

        $sequence->emails()->create($data);

        return redirect()->route('sequences.edit', $sequence);
    }

    public function destroyStep(EmailSequence $sequence, SequenceEmail $step): RedirectResponse
    {
        if ($step->email_sequence_id !== $sequence->id) {
            abort(404);
        }

        $step->delete();

        return redirect()->route('sequences.edit', $sequence);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\EmailSequence;
use Illuminate\Contracts\View\View;
use Sendportal\Base\Facades\Sendportal;

class EmailSequencesController extends Controller
{
    public function index(): View
    {
        $sequences = EmailSequence::where('workspace_id', Sendportal::currentWorkspaceId())->get();

        return view('sequences.index', compact('sequences'));
    }
}

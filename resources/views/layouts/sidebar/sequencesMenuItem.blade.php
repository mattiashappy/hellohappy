<li class="nav-item {{ request()->is('*sequences*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('sequences.index') }}">
        <i class="fa-fw fas fa-stream mr-2"></i><span>{{ __('Email Sequences') }}</span>
    </a>
</li>
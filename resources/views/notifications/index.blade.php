@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-studio-ink flex items-center gap-2">
                <i class="fa-solid fa-bell text-brand-600" aria-hidden="true"></i>
                <span>Notifications</span>
            </h1>
            <p class="text-sm text-studio-muted mt-1">Updates on your video projects and finished shorts.</p>
        </div>

        @if(auth()->user()->unreadNotificationsCount() > 0)
            <form action="{{ route('notifications.read_all') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 bg-studio-surface border border-studio-line px-4 py-2 rounded-xl transition hover:bg-studio-lavender">
                    <i class="fa-solid fa-check-double"></i>
                    <span>Mark all as read</span>
                </button>
            </form>
        @endif
    </div>

    <div class="bg-studio-surface border border-studio-line rounded-3xl shadow-[0_20px_70px_rgba(48,40,166,0.08)] overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-studio-lavender text-brand-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-regular fa-bell-slash"></i>
                </div>
                <h3 class="text-base font-semibold text-studio-ink">No notifications yet</h3>
                <p class="text-sm text-studio-muted mt-1">Project updates and finished shorts will appear here.</p>
            </div>
        @else
            <div class="divide-y divide-studio-line">
                @foreach($notifications as $notif)
                    <div class="p-5 flex items-start justify-between gap-4 {{ $notif->is_read ? 'bg-transparent' : 'bg-studio-lavender/45' }} hover:bg-studio-canvas transition">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-base flex-shrink-0 {{ $notif->type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-studio-lavender text-brand-600 border border-studio-line' }}">
                                <i class="{{ $notif->type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-info' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-studio-ink flex items-center gap-2">
                                    <span>{{ $notif->title }}</span>
                                    @if(!$notif->is_read)
                                        <span class="w-2 h-2 rounded-full bg-brand-600 inline-block" aria-label="Unread"></span>
                                    @endif
                                </h4>
                                <p class="text-sm text-studio-muted mt-1">{{ $notif->message }}</p>
                                <span class="text-xs text-studio-muted mt-2 block">
                                    <i class="fa-regular fa-clock mr-1"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($notif->video_project_id)
                                <a href="{{ route('projects.show', $notif->video_project_id) }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 px-3 py-2 rounded-lg bg-studio-lavender border border-studio-line transition">
                                    View Shorts
                                </a>
                            @endif

                            @if(!$notif->is_read)
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-studio-muted hover:text-brand-600 rounded-lg transition" title="Mark as read" aria-label="Mark as read">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($notifications->hasPages())
                <div class="p-4 border-t border-studio-line">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

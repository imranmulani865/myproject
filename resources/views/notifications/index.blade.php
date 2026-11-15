@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-bell text-indigo-400"></i>
                <span>Notifications</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Updates on your video processing and completed shorts</p>
        </div>

        @if(auth()->user()->unreadNotificationsCount() > 0)
            <form action="{{ route('notifications.read_all') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold text-indigo-400 hover:text-indigo-300 bg-slate-900 border border-slate-800 px-4 py-2 rounded-xl transition hover:bg-slate-800">
                    <i class="fa-solid fa-check-double"></i>
                    <span>Mark all as read</span>
                </button>
            </form>
        @endif
    </div>

    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-regular fa-bell-slash"></i>
                </div>
                <h3 class="text-base font-semibold text-white">No notifications yet</h3>
                <p class="text-xs text-slate-400 mt-1">When your video shorts finish processing in the background, you will see alerts here.</p>
            </div>
        @else
            <div class="divide-y divide-slate-800/80">
                @foreach($notifications as $notif)
                    <div class="p-5 flex items-start justify-between gap-4 {{ $notif->is_read ? 'bg-transparent' : 'bg-indigo-950/20' }} hover:bg-slate-800/40 transition">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-base flex-shrink-0 {{ $notif->type === 'success' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-800' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-800' }}">
                                <i class="{{ $notif->type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-info' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white flex items-center gap-2">
                                    <span>{{ $notif->title }}</span>
                                    @if(!$notif->is_read)
                                        <span class="w-2 h-2 rounded-full bg-pink-500 inline-block"></span>
                                    @endif
                                </h4>
                                <p class="text-xs text-slate-300 mt-1">{{ $notif->message }}</p>
                                <span class="text-[11px] text-slate-500 mt-2 block">
                                    <i class="fa-regular fa-clock mr-1"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($notif->video_project_id)
                                <a href="{{ route('projects.show', $notif->video_project_id) }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 px-3 py-1.5 rounded-lg bg-indigo-950/60 border border-indigo-800/60 transition">
                                    View Shorts
                                </a>
                            @endif

                            @if(!$notif->is_read)
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-white rounded-lg transition" title="Mark as read">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($notifications->hasPages())
                <div class="p-4 border-t border-slate-800">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

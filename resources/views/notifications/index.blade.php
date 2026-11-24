@extends('layouts.app')

@section('title', 'Notifications • AI Shorts Creator')

@section('content')
<section class="mx-auto max-w-4xl py-6" aria-labelledby="notifications-title">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-2 text-xs font-bold text-[#4D5CFF]">YOUR ACTIVITY</p>
            <h1 id="notifications-title" class="font-['Space_Grotesk'] text-3xl font-bold tracking-[-.05em] text-[#152238] sm:text-4xl">Notifications</h1>
            <p class="mt-2 text-sm text-[#687684]">Updates on your projects and finished shorts.</p>
        </div>
        @if(auth()->user()->unreadNotificationsCount() > 0)
            <form action="{{ route('notifications.read_all') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-[#D8DFFF] bg-white px-4 py-2 text-sm font-semibold text-[#4251CE] transition-colors hover:bg-[#EEF0FF]">
                    <i class="fa-solid fa-check-double" aria-hidden="true"></i> Mark all as read
                </button>
            </form>
        @endif
    </div>

    <div class="overflow-hidden rounded-[1.5rem] border border-[#DDE4F0] bg-white shadow-[0_16px_50px_rgba(21,34,56,.05)]">
        @if($notifications->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EEF0FF] text-xl text-[#4D5CFF]"><i class="fa-regular fa-bell-slash" aria-hidden="true"></i></div>
                <h2 class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">You’re all caught up</h2>
                <p class="mt-2 text-sm text-[#687684]">Project updates and finished shorts will appear here.</p>
            </div>
        @else
            <div class="divide-y divide-[#E8ECF3]">
                @foreach($notifications as $notif)
                    <div class="flex flex-col gap-4 px-5 py-5 transition-colors hover:bg-[#FAFBFE] sm:flex-row sm:items-start sm:justify-between sm:px-6 {{ $notif->is_read ? '' : 'bg-[#F6F7FF]' }}">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $notif->type === 'success' ? 'bg-[#E9F9F0] text-[#208052]' : 'bg-[#EEF0FF] text-[#4D5CFF]' }}">
                                <i class="{{ $notif->type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-info' }}" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h2 class="flex items-center gap-2 text-sm font-bold text-[#152238]">
                                    {{ $notif->title }}
                                    @if(!$notif->is_read)<span class="h-2 w-2 rounded-full bg-[#FF735B]" aria-label="Unread"></span>@endif
                                </h2>
                                <p class="mt-1 text-sm leading-relaxed text-[#687684]">{{ $notif->message }}</p>
                                <span class="mt-2 block text-xs text-[#8A96A8]"><i class="fa-regular fa-clock mr-1" aria-hidden="true"></i>{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-2 pl-14 sm:pl-0">
                            @if($notif->video_project_id)
                                <a href="{{ route('projects.show', $notif->video_project_id) }}" class="inline-flex min-h-10 items-center rounded-xl border border-[#D8DFFF] bg-white px-3 py-2 text-xs font-bold text-[#4251CE] hover:bg-[#EEF0FF]">View shorts</a>
                            @endif
                            @if(!$notif->is_read)
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-xl text-[#687684] hover:bg-[#EEF0FF] hover:text-[#4251CE]" title="Mark as read" aria-label="Mark as read"><i class="fa-solid fa-check text-xs" aria-hidden="true"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($notifications->hasPages())
                <div class="border-t border-[#E8ECF3] p-4">{{ $notifications->links() }}</div>
            @endif
        @endif
    </div>
</section>
@endsection

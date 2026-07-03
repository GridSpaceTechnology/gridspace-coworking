@extends('layouts.admin')

@section('title', 'User Management | GridSpace')

@section('admin_content')
<section class="mb-6 md:mb-8">
    <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">User Management</h1>
    <p class="font-inter text-sm text-on-surface-variant mt-1">View and manage all user accounts on the platform</p>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-800 font-inter">{{ session('error') }}</div>
@endif

<div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
    <x-admin.filters-bar :action="route('admin.users.index')">
        <div class="flex flex-col gap-1 min-w-[160px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone…"
                   class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container">
        </div>
        <div class="flex flex-col gap-1 min-w-[120px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Role</label>
            <select name="role" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="">All</option>
                @foreach(['admin', 'host', 'user'] as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1 min-w-[120px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</label>
            <select name="approved" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="">All</option>
                <option value="1" @selected(request('approved') === '1')>Active</option>
                <option value="0" @selected(request('approved') === '0')>Inactive</option>
            </select>
        </div>
    </x-admin.filters-bar>

    @if($users->isEmpty())
        <div class="p-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">group</span>
            </div>
            <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-2">No users yet</h3>
            <p class="font-inter text-sm text-on-surface-variant">Users will appear here once they register.</p>
        </div>
    @else
        <form method="POST" action="{{ route('admin.users.bulk-delete') }}">
            @csrf
            @include('admin.partials.bulk-toolbar', [
                'bulkAction' => route('admin.users.bulk-delete'),
                'paginator' => $users,
            ])
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                        <th class="px-5 py-3 w-10"></th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">User</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Type</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Joined</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Phone</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @foreach($users as $user)
                        @php
                            $initial = strtoupper(substr($user->firstname ?? 'U', 0, 1));
                            $avatarUrl = $user->profile_photo_url;
                            $canManage = $user->id !== auth()->id() && $user->role !== 'admin';
                        @endphp
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4">
                                @if($canManage)
                                    <input type="checkbox" name="ids[]" value="{{ $user->id }}"
                                           class="bulk-row-check rounded border-outline-variant text-primary-container focus:ring-primary-container/30">
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-primary-container/10 flex items-center justify-center shrink-0">
                                        @if($avatarUrl)
                                            <img src="{{ $avatarUrl }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="font-manrope font-bold text-primary-container text-sm">{{ $initial }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-inter text-sm font-medium text-[#1c2c40]">{{ $user->display_name }}</p>
                                        <p class="font-inter text-xs text-on-surface-variant truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold capitalize
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'host' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                    {{ $user->approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->approved ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant whitespace-nowrap">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $user->phone ?? '—' }}</td>
                            <td class="px-5 py-4 text-right">
                                <button type="button"
                                        class="w-9 h-9 inline-flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-[#1c2c40] transition-colors"
                                        onclick="openUserModal({{ json_encode([
                                            'name' => $user->display_name,
                                            'email' => $user->email,
                                            'phone' => $user->phone ?? '—',
                                            'role' => ucfirst($user->role),
                                            'status' => $user->approved ? 'Active' : 'Inactive',
                                            'joined' => $user->created_at->format('M d, Y'),
                                            'avatar' => $avatarUrl,
                                            'initial' => $initial,
                                            'canManage' => $canManage,
                                            'toggleUrl' => $canManage ? route('admin.users.toggle', $user) : null,
                                            'deleteUrl' => $canManage ? route('admin.users.delete', $user) : null,
                                            'approved' => $user->approved,
                                        ]) }})">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $users])
        </form>
    @endif
</div>

<div id="user-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeUserModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl max-w-md w-full pointer-events-auto shadow-2xl p-6">
            <div class="flex items-center gap-4 mb-6">
                <div id="user-modal-avatar" class="w-16 h-16 rounded-full overflow-hidden bg-primary-container/10 flex items-center justify-center shrink-0"></div>
                <div>
                    <h2 class="font-manrope text-xl font-bold text-[#1c2c40]" id="user-modal-name"></h2>
                    <p class="font-inter text-sm text-on-surface-variant" id="user-modal-email"></p>
                </div>
            </div>
            <dl class="space-y-3 mb-6">
                <div class="flex justify-between"><dt class="font-inter text-sm text-on-surface-variant">Phone</dt><dd class="font-inter text-sm font-medium" id="user-modal-phone"></dd></div>
                <div class="flex justify-between"><dt class="font-inter text-sm text-on-surface-variant">Type</dt><dd class="font-inter text-sm font-medium" id="user-modal-role"></dd></div>
                <div class="flex justify-between"><dt class="font-inter text-sm text-on-surface-variant">Status</dt><dd class="font-inter text-sm font-medium" id="user-modal-status"></dd></div>
                <div class="flex justify-between"><dt class="font-inter text-sm text-on-surface-variant">Joined</dt><dd class="font-inter text-sm font-medium" id="user-modal-joined"></dd></div>
            </dl>
            <div id="user-modal-actions" class="space-y-2"></div>
            <button type="button" onclick="closeUserModal()"
                    class="mt-4 w-full py-2.5 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openUserModal(data) {
    document.getElementById('user-modal-name').textContent = data.name;
    document.getElementById('user-modal-email').textContent = data.email;
    document.getElementById('user-modal-phone').textContent = data.phone;
    document.getElementById('user-modal-role').textContent = data.role;
    document.getElementById('user-modal-status').textContent = data.status;
    document.getElementById('user-modal-joined').textContent = data.joined;

    const avatar = document.getElementById('user-modal-avatar');
    avatar.innerHTML = data.avatar
        ? `<img src="${data.avatar}" class="w-full h-full object-cover" alt="">`
        : `<span class="font-manrope font-bold text-primary-container text-xl">${data.initial}</span>`;

    const actions = document.getElementById('user-modal-actions');
    if (data.canManage) {
        actions.innerHTML = `
            <form method="POST" action="${data.toggleUrl}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PATCH">
                <button type="submit" class="w-full py-2.5 rounded-lg border border-red-300 text-red-600 font-inter text-sm font-semibold hover:bg-red-50">
                    ${data.approved ? 'Suspend User' : 'Activate User'}
                </button>
            </form>
            <form method="POST" action="${data.deleteUrl}" onsubmit="return confirm('Permanently delete this user?')">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="w-full py-2.5 rounded-lg border border-red-500 text-red-600 font-inter text-sm font-semibold hover:bg-red-50">Delete User</button>
            </form>`;
    } else {
        actions.innerHTML = '<p class="font-inter text-sm text-on-surface-variant text-center">This account cannot be modified.</p>';
    }

    document.getElementById('user-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUserModal() {
    document.getElementById('user-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@endpush
@endsection

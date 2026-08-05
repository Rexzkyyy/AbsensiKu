@extends('layouts.app')

@section('title', 'Kelola User')
@section('header_title', 'Kelola Pengguna Sistem')

@section('content')
<!-- Filter & Add User Action -->
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 p-5 md:p-8 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div>
        <h3 class="font-extrabold text-slate-800 text-xl flex items-center gap-2">
            <i class="fas fa-users-cog text-blue-500"></i> Daftar Pengguna
        </h3>
        <p class="text-sm text-slate-500 font-medium mt-1">Kelola data peserta magang, mentor, dan admin.</p>
    </div>
    <div class="flex gap-3 w-full sm:w-auto">
        <button onclick="openAddModal()" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-1 flex items-center justify-center gap-2">
            <i class="fas fa-user-plus"></i> Tambah User
        </button>
    </div>
</div>

<!-- Users Table Card -->
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden">
    @if ($users->isEmpty())
        <div class="text-center py-16 flex flex-col justify-center items-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                <i class="fas fa-user-slash text-3xl"></i>
            </div>
            <h4 class="font-medium text-gray-600">Belum ada pengguna</h4>
            <p class="text-sm text-gray-400">Silakan tambah pengguna baru.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-sm">
                        <th class="py-4 px-6 font-semibold text-gray-600 whitespace-nowrap">ID / Username</th>
                        <th class="py-4 px-6 font-semibold text-gray-600 whitespace-nowrap">Email</th>
                        <th class="py-4 px-6 font-semibold text-gray-600 whitespace-nowrap">Role</th>
                        <th class="py-4 px-6 font-semibold text-gray-600 whitespace-nowrap">Keterangan</th>
                        <th class="py-4 px-6 font-semibold text-gray-600 whitespace-nowrap">Tgl Daftar</th>
                        <th class="py-4 px-6 font-semibold text-gray-600 whitespace-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($users as $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($u->username, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ htmlspecialchars($u->username) }}</div>
                                        <div class="text-xs text-gray-400 font-mono">ID: {{ $u->id_user }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ htmlspecialchars($u->email) }}</td>
                            <td class="py-4 px-6">
                                @php
                                    $roleColor = $u->role === 'mentor' ? 'bg-primary-100 text-primary-700 border-primary-200' : 
                                                ($u->role === 'admin' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                                                'bg-amber-100 text-amber-700 border-amber-200');
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $roleColor }} uppercase inline-block">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600 max-w-[200px] truncate">{{ htmlspecialchars($u->keterangan ?? '-') }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600 whitespace-nowrap">{{ Carbon\Carbon::parse($u->created_at)->isoFormat('D MMM Y') }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openEditModal('{{ $u->id_user }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition flex items-center justify-center" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="{{ route('admin.users.delete', $u->id_user) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini? Seluruh data profil & riwayat absensi terkait akan dihapus permanen.')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex items-center justify-center" title="Hapus User">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- MODAL ADD USER -->
<div id="add-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/40 backdrop-blur-md transition-opacity duration-300 opacity-0" aria-hidden="true">
    <div class="bg-white/90 backdrop-blur-2xl rounded-3xl w-[90%] max-w-lg shadow-[0_25px_60px_-15px_rgba(0,0,0,0.3)] border border-white/60 transform scale-95 translate-y-4 transition-all duration-300 overflow-hidden flex flex-col max-h-[90vh]" id="addModalContent">
        
        <div class="bg-white/50 backdrop-blur-md border-b border-white/40 px-6 py-5 flex justify-between items-center text-slate-800 shrink-0">
            <h3 class="font-extrabold text-lg flex items-center gap-2"><i class="fas fa-user-plus text-blue-500"></i> Tambah User Baru</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-800 hover:rotate-90 transition-all outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="Contoh: user@gmail.com" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="password">Password</label>
                    <input type="text" id="password" name="password" placeholder="Minimal 4 karakter" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 mb-1">
                    <p class="text-[11px] text-gray-500"><i class="fas fa-info-circle text-primary-400"></i> Password disimpan dan digunakan login langsung oleh user.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="role">Hak Akses / Role</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800">
                        <option value="magang">Magang (Peserta)</option>
                        <option value="mentor">Mentor (Pembimbing)</option>
                        <option value="admin">Admin (Super Admin)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="keterangan">Keterangan Tambahan</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Batch Magang, Penempatan Divisi, dsb"
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm flex items-center justify-center">
                        Daftarkan User
                    </button>
                    <button type="button" onclick="closeAddModal()" class="w-1/3 bg-white hover:bg-gray-50 border border-gray-200 text-gray-600 font-bold py-3 px-4 rounded-xl transition flex items-center justify-center text-center">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT USER -->
<div id="edit-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/40 backdrop-blur-md transition-opacity duration-300 opacity-0" aria-hidden="true">
    <div class="bg-white/90 backdrop-blur-2xl rounded-3xl w-[90%] max-w-lg shadow-[0_25px_60px_-15px_rgba(0,0,0,0.3)] border border-white/60 transform scale-95 translate-y-4 transition-all duration-300 overflow-hidden flex flex-col max-h-[90vh]" id="editModalContent">
        
        <div class="bg-white/50 backdrop-blur-md border-b border-white/40 px-6 py-5 flex justify-between items-center text-slate-800 shrink-0">
            <h3 class="font-extrabold text-lg flex items-center gap-2"><i class="fas fa-user-edit text-blue-500"></i> Edit Data User</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-800 hover:rotate-90 transition-all outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <form method="POST" action="" id="edit-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="username" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none text-gray-800">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="edit_email">Alamat Email</label>
                    <input type="email" id="edit_email" name="email" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none text-gray-800">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="edit_password">Password Baru</label>
                    <input type="text" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none text-gray-800 mb-1">
                    <p class="text-[11px] text-gray-500"><i class="fas fa-info-circle text-blue-400"></i> Biarkan kosong kecuali ingin merubah password pengguna.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="edit_role">Hak Akses / Role</label>
                    <select id="edit_role" name="role" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none text-gray-800">
                        <option value="magang">Magang (Peserta)</option>
                        <option value="mentor">Mentor (Pembimbing)</option>
                        <option value="admin">Admin (Super Admin)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="edit_keterangan">Keterangan Tambahan</label>
                    <textarea id="edit_keterangan" name="keterangan" rows="2"
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none text-gray-800"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm flex items-center justify-center">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditModal()" class="w-1/3 bg-white hover:bg-gray-50 border border-gray-200 text-gray-600 font-bold py-3 px-4 rounded-xl transition flex items-center justify-center text-center">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Modal Add functions
    function openAddModal() {
        const modal = document.getElementById('add-modal');
        const content = document.getElementById('addModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        void modal.offsetWidth; // trigger reflow
        
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'translate-y-4');
        content.classList.add('scale-100', 'translate-y-0');
    }
    
    function closeAddModal() {
        const modal = document.getElementById('add-modal');
        const content = document.getElementById('addModalContent');
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'translate-y-0');
        content.classList.add('scale-95', 'translate-y-4');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Modal Edit functions
    function openEditModal(id) {
        fetch(`{{ url('admin/users/detail') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_username').value = data.username;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_keterangan').value = data.keterangan || '';
                document.getElementById('edit_password').value = '';
                
                document.getElementById('edit-form').action = `{{ url('admin/users/update') }}/${id}`;
                
                const modal = document.getElementById('edit-modal');
                const content = document.getElementById('editModalContent');
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                void modal.offsetWidth;
                
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'translate-y-4');
                content.classList.add('scale-100', 'translate-y-0');
            })
            .catch(err => {
                console.error("Gagal mengambil detail user:", err);
                alert("Gagal mengambil data user!");
            });
    }
    
    function closeEditModal() {
        const modal = document.getElementById('edit-modal');
        const content = document.getElementById('editModalContent');
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'translate-y-0');
        content.classList.add('scale-95', 'translate-y-4');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    window.addEventListener('click', function(e) {
        const addModal = document.getElementById('add-modal');
        const editModal = document.getElementById('edit-modal');
        if (e.target === addModal) closeAddModal();
        if (e.target === editModal) closeEditModal();
    });
</script>
@endsection

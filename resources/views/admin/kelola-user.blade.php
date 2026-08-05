@extends('layouts.app')

@section('title', 'Kelola User')

@section('header_title', 'Kelola Pengguna Sistem')

@section('styles')
<style>
    /* Table Styling */
    .table-container {
        width: 100%;
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        text-align: left;
    }
    
    th, td {
        padding: 15px 20px;
        border-bottom: 1px solid var(--light-gray);
    }
    
    th {
        background-color: var(--light);
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
    }
    
    tr:hover {
        background-color: rgba(67, 97, 238, 0.02);
    }
    
    .role-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .badge-mentor {
        background: rgba(67, 97, 238, 0.15);
        color: var(--primary);
    }
    
    .badge-admin {
        background: rgba(40, 167, 69, 0.15);
        color: var(--hadir);
    }
    
    .badge-magang {
        background: rgba(255, 193, 7, 0.15);
        color: #856404;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        margin-right: 5px;
    }

    .btn-edit {
        background: var(--primary);
    }
    .btn-edit:hover {
        background: var(--primary-dark);
    }
    .btn-delete {
        background: var(--tidak-hadir);
    }
    .btn-delete:hover {
        background: #bd2130;
    }

    /* Modal Backdrop */
    .modal-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1100;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
        transition: var(--transition);
    }

    .modal-card {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        opacity: 0.8;
        transition: var(--transition);
    }

    .modal-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 25px;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap: wrap; gap: 15px;">
        <h3 class="section-title" style="margin-bottom:0;"><i class="fas fa-users-cog"></i> Daftar Pengguna</h3>
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Tambah Pengguna
        </button>
    </div>
    
    <p style="margin-bottom: 25px; color: #666; font-size: 0.95rem;">
        Berikut adalah daftar seluruh akun pengguna sistem AbsensiKu. Anda dapat mendaftarkan mentor, admin, atau peserta magang baru di sini.
    </p>

    <div class="table-container">
        @if ($users->isEmpty())
            <div style="text-align:center; color:#999; padding: 40px;">
                <i class="fas fa-user-slash fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Belum ada pengguna terdaftar.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Keterangan</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $u)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td><strong>{{ htmlspecialchars($u->username) }}</strong></td>
                            <td>{{ htmlspecialchars($u->email) }}</td>
                            <td>
                                <span class="role-badge badge-{{ $u->role }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td>{{ htmlspecialchars($u->keterangan ?? '-') }}</td>
                            <td>{{ Carbon\Carbon::parse($u->created_at)->isoFormat('D MMMM Y') }}</td>
                            <td>
                                <button onclick="openEditModal('{{ $u->id_user }}')" class="btn-action btn-edit" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="{{ route('admin.users.delete', $u->id_user) }}" class="btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini? Seluruh data profil magang & riwayat absensi terkait akan dihapus permanen.')" title="Hapus User">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination Wrapper -->
            <div class="pagination-wrapper" style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- MODAL ADD USER -->
<div class="modal-backdrop" id="add-modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Tambah User Baru</h3>
            <button onclick="closeAddModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" class="form-input" id="username" name="username" placeholder="Masukkan username" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="email" class="form-input" id="email" name="email" placeholder="Contoh: user@gmail.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" class="form-input" id="password" name="password" placeholder="Minimal 4 karakter" required>
                    <small style="color:#666;">Password akan disimpan dan digunakan login langsung oleh user.</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Hak Akses / Role</label>
                    <select class="form-input" id="role" name="role" required>
                        <option value="magang">Magang (Peserta)</option>
                        <option value="mentor">Mentor (Pembimbing)</option>
                        <option value="admin">Admin (Super Admin)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="keterangan">Keterangan Tambahan</label>
                    <textarea class="form-input" id="keterangan" name="keterangan" rows="3" placeholder="Batch Magang, Penempatan Divisi, dsb"></textarea>
                </div>

                <div style="margin-top:20px; display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Daftarkan User</button>
                    <button type="button" onclick="closeAddModal()" class="btn btn-outline">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT USER -->
<div class="modal-backdrop" id="edit-modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> Edit Data User</h3>
            <button onclick="closeEditModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="" id="edit-form">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="edit_username">Username</label>
                    <input type="text" class="form-input" id="edit_username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="edit_email">Alamat Email</label>
                    <input type="email" class="form-input" id="edit_email" name="email" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_password">Password Baru</label>
                    <input type="text" class="form-input" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                    <small style="color:#666;">Biarkan kosong kecuali ingin merubah password pengguna tersebut.</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_role">Hak Akses / Role</label>
                    <select class="form-input" id="edit_role" name="role" required>
                        <option value="magang">Magang (Peserta)</option>
                        <option value="mentor">Mentor (Pembimbing)</option>
                        <option value="admin">Admin (Super Admin)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_keterangan">Keterangan Tambahan</label>
                    <textarea class="form-input" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                </div>

                <div style="margin-top:20px; display:flex; gap:10px;">
                    <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">Simpan Perubahan</button>
                    <button type="button" onclick="closeEditModal()" class="btn btn-outline">Batal</button>
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
        document.getElementById('add-modal').style.display = 'flex';
    }
    
    function closeAddModal() {
        document.getElementById('add-modal').style.display = 'none';
    }
    
    // Modal Edit functions
    function openEditModal(id) {
        // Fetch user data via AJAX
        fetch(`{{ url('admin/users/detail') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                // Populate form
                document.getElementById('edit_username').value = data.username;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_keterangan').value = data.keterangan || '';
                document.getElementById('edit_password').value = ''; // Reset password field
                
                // Set action URL
                document.getElementById('edit-form').action = `{{ url('admin/users/update') }}/${id}`;
                
                // Show modal
                document.getElementById('edit-modal').style.display = 'flex';
            })
            .catch(err => {
                console.error("Gagal mengambil detail user:", err);
                alert("Gagal mengambil data user!");
            });
    }
    
    function closeEditModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }

    // Close modals on clicking backdrop
    window.addEventListener('click', function(e) {
        const addModal = document.getElementById('add-modal');
        const editModal = document.getElementById('edit-modal');
        if (e.target === addModal) {
            closeAddModal();
        }
        if (e.target === editModal) {
            closeEditModal();
        }
    });
</script>
@endsection

@extends('admin.layout')

@section('title', 'Kelola Kost')
@section('page-title', 'Kelola Kost')

@section('page-actions')
    <a href="{{ route('admin.kosts.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kost
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        @if($kosts->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Gambar</th>
                            <th>Nama Kost</th>
                            <th>Harga/Bulan</th>
                            <th width="10%">Kamar</th>
                            <th width="10%">Tersedia</th>
                            <th width="10%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kosts as $kost)
                            <tr>
                                <td>{{ $loop->iteration + ($kosts->currentPage() - 1) * $kosts->perPage() }}</td>
                                <td>
                                    @if($kost->images->first())
                                        <img src="{{ $kost->images->first()->image_url }}" 
                                             alt="{{ $kost->name }}" class="rounded" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $kost->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($kost->address, 50) }}</small>
                                    @if($kost->categories->count() > 0)
                                        <br>
                                        @foreach($kost->categories as $category)
                                            <span class="badge bg-info">{{ $category->name }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($kost->price_per_month, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $kost->room_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $kost->available_rooms > 0 ? 'success' : 'danger' }}">
                                        {{ $kost->available_rooms }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $kost->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($kost->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.kosts.show', $kost) }}" 
                                           class="btn btn-sm btn-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.kosts.edit', $kost) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                title="Hapus" onclick="deleteKost({{ $kost->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $kosts->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Kost</h5>
                <p class="text-muted">Mulai dengan menambahkan kost pertama Anda.</p>
                <a href="{{ route('admin.kosts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Kost
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus kost ini? Semua gambar dan data terkait akan ikut terhapus. 
                Aksi ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteKost(kostId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/kosts/${kostId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endpush

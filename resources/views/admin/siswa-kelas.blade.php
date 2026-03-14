@extends('layouts.app')

@section('title','Data siswa kelas')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/siswa-kelas.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data siswa kelas</h5>
        </div>

            <a href="{{ route('kelas') }}" class="btn btn-kembali mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="card mb-3 p-4">

            <div class="info-kelas">

                <div class="info-row">
                    <label>Kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="1-A" readonly>
                </div>

                <div class="info-row">
                    <label>Wali kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="Arif Nasution" readonly>
                </div>

                <div class="info-row">
                    <label>Tanggal</label>
                    <span>:</span>
                    <input type="date" class="form-control">
                </div>

            </div>

        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>NIS</th>
                            <th>Nama lengkap</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>00987643</td>
                            <td>Arif Nasution</td>
                            <td>
                                <div class="status-group">
                                    <span class="status-btn active">H</span>
                                    <span class="status-btn">I</span>
                                    <span class="status-btn">S</span>
                                    <span class="status-btn">A</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>00985651</td>
                            <td>Radita Nabila</td>
                            <td>
                                <div class="status-group">
                                    <span class="status-btn active">H</span>
                                    <span class="status-btn">I</span>
                                    <span class="status-btn">S</span>
                                    <span class="status-btn">A</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>00952763</td>
                            <td>Arif Rahman</td>
                            <td>
                                <div class="status-group">
                                    <span class="status-btn active">H</span>
                                    <span class="status-btn">I</span>
                                    <span class="status-btn">S</span>
                                    <span class="status-btn">A</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>00936121</td>
                            <td>Ismatul Hawa</td>
                            <td>
                                <div class="status-group">
                                    <span class="status-btn active">H</span>
                                    <span class="status-btn">I</span>
                                    <span class="status-btn">S</span>
                                    <span class="status-btn">A</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>00864041</td>
                            <td>Ilham Basudara</td>
                            <td>
                                <div class="status-group">
                                    <span class="status-btn active">H</span>
                                    <span class="status-btn">I</span>
                                    <span class="status-btn">S</span>
                                    <span class="status-btn">A</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </table>
                </div>

                <div class="card-footer-absen">

                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-save"></i> Simpan
                    </button>

                </div>

                </div>

        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.status-group').forEach(group => {
        const buttons = group.querySelectorAll('.status-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    });
</script>

@endsection

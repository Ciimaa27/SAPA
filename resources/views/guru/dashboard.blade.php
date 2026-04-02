<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Guru</title>

<!-- FONT -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    background: #f4f6fb;
    display: flex;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    height: 100vh;
    background: #fff;
    border-right: 1px solid #e5e7eb;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.logo {
    font-size: 20px;
    font-weight: 600;
    color: #6c5ce7;
}

.sub-logo {
    font-size: 11px;
    color: #999;
    margin-bottom: 30px;
}

/* MENU */
.menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    margin-bottom: 8px;
    text-decoration: none;
    color: #555;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.2s;
}

.menu a i {
    width: 18px;
    text-align: center;
}

.menu a:hover {
    background: #f3f4f6;
}

.menu a.active {
    background: #ede9fe;
    color: #6c5ce7;
}

/* LOGOUT */
.logout {
    font-size: 14px;
    color: #555;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* MAIN */
.main {
    flex: 1;
    padding: 20px 25px;
}

.topbar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 13px;
    color: #666;
}

/* TITLE */
.title-box {
    background: #fff;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-weight: 500;
}

/* CARDS */
.cards {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
}

.card {
    flex: 1;
    background: #fff;
    padding: 14px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card h4 {
    font-size: 11px;
    color: #888;
}

.card h2 {
    font-size: 18px;
    color: #333;
}

.card i {
    color: #6c5ce7;
}

/* PICKUP */
.pickup {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
}

.pickup-card {
    flex: 1;
    background: #fff;
    padding: 14px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pickup-card h4 {
    font-size: 11px;
    color: #888;
}

.pickup-card h2 {
    font-size: 18px;
    color: #333;
}

.check {
    color: #22c55e;
}

.cross {
    color: #ef4444;
}

/* TABLE */
.table-box {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
}

.table-box h4 {
    margin-bottom: 10px;
    font-size: 13px;
    color: #6c5ce7;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

th, td {
    padding: 10px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

th {
    color: #888;
}

.empty {
    text-align: center;
    color: #aaa;
    padding: 20px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div>
        <div class="logo">SAPA</div>
        <div class="sub-logo">Dashboard Guru</div>

        <div class="menu">
            <a class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a><i class="fa-solid fa-clipboard-check"></i> Input Kehadiran</a>
            <a><i class="fa-solid fa-user-check"></i> Riwayat Penjemputan</a>
            <a><i class="fa-solid fa-database"></i> Data Penjemputan</a>
        </div>
    </div>

    <a class="logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
</div>

<!-- MAIN -->
<div class="main">

    <div class="topbar">
        <div>Dashboard Guru</div>
        <div><i class="fa-solid fa-user"></i></div>
    </div>

    <!-- RINGKASAN -->
    <div class="title-box">Ringkasan Hari Ini</div>

    <div class="cards">
        <div class="card">
            <div>
                <h4>Total Siswa</h4>
                <h2>30</h2>
            </div>
            <i class="fa-solid fa-users"></i>
        </div>

        <div class="card">
            <div>
                <h4>Hadir</h4>
                <h2>25</h2>
            </div>
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="card">
            <div>
                <h4>Tidak Hadir</h4>
                <h2>5</h2>
            </div>
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
    </div>

    <!-- PENJEMPUTAN -->
    <div class="title-box">Data Penjemputan Hari Ini</div>

    <div class="pickup">
        <div class="pickup-card">
            <div>
                <h4>Sudah dijemput</h4>
                <h2>20</h2>
            </div>
            <i class="fa-solid fa-circle-check check"></i>
        </div>

        <div class="pickup-card">
            <div>
                <h4>Belum dijemput</h4>
                <h2>10</h2>
            </div>
            <i class="fa-solid fa-circle-xmark cross"></i>
        </div>
    </div>

    <!-- TABEL -->
    <div class="table-box">
        <h4>Data Kehadiran Siswa</h4>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ahmad</td>
                    <td>1A</td>
                    <td>Hadir</td>
                    <td>07:30</td>
                </tr>
                <tr>
                    <td>Siti</td>
                    <td>1B</td>
                    <td>Tidak Hadir</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>

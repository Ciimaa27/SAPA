<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard SAPA</title>

<!-- FONT -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- ICON -->
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
    margin-bottom: 5px;
}

.sub-logo {
    font-size: 11px;
    color: #999;
    margin-bottom: 30px;
}

.menu a {
    display: block;
    padding: 10px 12px;
    margin-bottom: 8px;
    text-decoration: none;
    color: #555;
    border-radius: 8px;
    font-size: 14px;
}

.menu a i {
    margin-right: 8px;
}

.menu a.active {
    background: #f1f2ff;
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

.logout:hover {
    color: #6c5ce7;
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

/* SECTION TITLE */
.section-title {
    background: #fff;
    padding: 10px 15px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 15px;
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
    margin-bottom: 5px;
}

.card h2 {
    font-size: 18px;
    color: #6c5ce7;
}

.card i {
    font-size: 18px;
    color: #f4b400;
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
    color: #6c5ce7;
}

.check {
    color: #22c55e;
}

.cross {
    color: #ef4444;
}

/* CHART */
.chart {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
}

.chart h4 {
    font-size: 12px;
    color: #666;
    margin-bottom: 15px;
}

/* GRID KOSONG */
.chart-area {
    height: 200px;
    border-radius: 8px;
    background: repeating-linear-gradient(
        to right,
        #f1f2f6 0px,
        #f1f2f6 1px,
        transparent 1px,
        transparent 40px
    ),
    repeating-linear-gradient(
        to top,
        #f1f2f6 0px,
        #f1f2f6 1px,
        transparent 1px,
        transparent 40px
    );
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div>
        <div class="logo">SAPA</div>
        <div class="sub-logo">Absensi & Penjemputan</div>

        <div class="menu">
            <a class="active"><i class="fa fa-home"></i> Dashboard</a>
            <a><i class="fa fa-chart-bar"></i> Statistik Kehadiran</a>
            <a><i class="fa fa-file-alt"></i> Laporan</a>
        </div>
    </div>

    <!-- LOGOUT -->
    <a href="#" class="logout">
        <i class="fa fa-sign-out-alt"></i> Keluar
    </a>
</div>

<!-- MAIN -->
<div class="main">

    <div class="topbar">
        <div>Senin, 16 Februari 2026</div>
        <div><i class="fa fa-user"></i></div>
    </div>

    <div class="section-title">Statistik ringkas</div>

    <!-- CARDS -->
    <div class="cards">
        <div class="card">
            <div>
                <h4>Total akun</h4>
                <h2>450</h2>
            </div>
            <i class="fa fa-users"></i>
        </div>

        <div class="card">
            <div>
                <h4>Total akun wali</h4>
                <h2>478</h2>
            </div>
            <i class="fa fa-user-friends"></i>
        </div>

        <div class="card">
            <div>
                <h4>Total kehadiran siswa</h4>
                <h2>443</h2>
            </div>
            <i class="fa fa-chart-line"></i>
        </div>

        <div class="card">
            <div>
                <h4>Siswa tidak hadir</h4>
                <h2>7</h2>
            </div>
            <i class="fa fa-user-times"></i>
        </div>
    </div>

    <div class="section-title">Data penjemputan hari ini</div>

    <!-- PICKUP -->
    <div class="pickup">
        <div class="pickup-card">
            <div>
                <h4>Sudah dijemput</h4>
                <h2>206</h2>
            </div>
            <i class="fa fa-check-circle check"></i>
        </div>

        <div class="pickup-card">
            <div>
                <h4>Belum dijemput</h4>
                <h2>244</h2>
            </div>
            <i class="fa fa-times-circle cross"></i>
        </div>
    </div>

    <!-- CHART -->
    <div class="chart">
        <h4>Grafik Kehadiran mingguan</h4>
        <div class="chart-area"></div>
    </div>

</div>

</body>
</html>

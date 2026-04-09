<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Orang Tua</title>

<!-- FONT -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- ICON -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    display: flex;
    background: #f5f7fb;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    height: 100vh;
    background: #fff;
    border-right: 1px solid #eee;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.logo {
    font-size: 20px;
    font-weight: 600;
    color: #6c63ff;
}

.menu {
    margin-top: 25px;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    margin-bottom: 8px;
    border-radius: 10px;
    text-decoration: none;
    color: #555;
    font-size: 14px;
}

.menu a.active {
    background: #ecebff;
    color: #6c63ff;
}

/* LOGOUT */
.logout {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
    text-decoration: none;
}

/* MAIN */
.main {
    flex: 1;
    padding: 20px 25px;
}

/* TOPBAR */
.topbar {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #777;
    margin-bottom: 10px;
}

/* BOX */
.box {
    background: #fff;
    padding: 14px;
    border-radius: 10px;
    margin-bottom: 12px;
}

/* HEADER BOX */
.header-box {
    font-weight: 500;
}

/* INFO */
.info {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 14px;
}

.info div span {
    display: block;
    font-size: 12px;
    color: #999;
}

/* STATUS */
.status {
    margin-top: 10px;
    font-size: 13px;
}

.status strong {
    color: #22c55e;
}

/* RIWAYAT */
.riwayat table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.riwayat th, .riwayat td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.riwayat th {
    color: #999;
    text-align: left;
}

/* EMPTY */
.empty {
    text-align: center;
    color: #bbb;
    padding: 15px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div>
        <div class="logo">SAPA</div>

        <div class="menu">
            <a class="active"><i class="fa fa-home"></i> Dashboard</a>
            <a><i class="fa fa-user"></i> Profil Anak</a>
            <a><i class="fa fa-clipboard"></i> Laporan</a>
        </div>
    </div>

    <a class="logout"><i class="fa fa-sign-out-alt"></i> Keluar</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOP -->
    <div class="topbar">
        <div>Dashboard Orang Tua</div>
        <div><i class="fa fa-user-circle"></i></div>
    </div>

    <!-- DATA ANAK -->
    <div class="box header-box">
        Data Anak
    </div>

    <div class="box">
        <div class="info">
            <div>
                <span>Nama</span>
                -
            </div>
            <div>
                <span>Kelas</span>
                -
            </div>
        </div>

        <div class="status">
            Status Hari Ini: <strong>-</strong>
        </div>
    </div>

    <!-- PENJEMPUTAN -->
    <div class="box header-box">
        Penjemputan Hari Ini
    </div>

    <div class="box">
        <div class="info">
            <div>
                <span>Jam Dijemput</span>
                -
            </div>
            <div>
                <span>Status</span>
                -
            </div>
        </div>
    </div>

    <!-- RIWAYAT -->
    <div class="box header-box">
        Riwayat Kehadiran
    </div>

    <div class="box riwayat">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <div class="empty">Belum ada data</div>
    </div>

</div>

</body>
</html>

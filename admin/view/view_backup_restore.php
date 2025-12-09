    <script type="text/javascript" src="script/script_backup_restore.js"></script>

    <?php
    session_start();
    if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="admin"){
    header('location: login.php');
    }

    include "../../library/config.php";
    include "../../library/function_view.php";

    create_title("database", "Backup & Restore Database");
    ?>
    <div class="app-content mt-2" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="row align-items-stretch">
                <div class="col-md-6">
                    <div class="card shadow-sm bg-body rounded card-primary h-100">
                        <div class="card-header"><h3 class="card-title">Backup Database</h3></div>
                        <div class="card-body">       
                            <div class="alert alert-info" role="alert">
                                Proses backup mungkin membutuhkan waktu beberapa saat. Jangan menutup atau merefresh halaman saat proses berlangsung.
                            </div>             
                            <button class="btn btn-success" onclick="backup_database()">
                                <i class="fa fa-download"></i> Backup Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm bg-body rounded card-danger h-100">
                        <div class="card-header"><h3 class="card-title">Restore Database</h3></div>
                            <div class="card-body">
                                <div class="alert alert-info" role="alert">
                                    Pastikan file yang diunggah adalah file <b>backup database (.sql)</b> yang valid.
                                    Melakukan restore akan <b>menimpa</b> data yang ada saat ini.
                                </div>
                                <input type="file" id="restore_file" class="form-control">
                                <br>
                                <button class="btn btn-warning" onclick="restore_database()">
                                    <i class="fa fa-upload"></i> Restore
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

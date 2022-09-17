<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "testhni";

$koneksi = mysqli_connect($server, $user, $pass, $database) or die(mysqli_error($koneksi));

//jika klik simpan
if (isset($_POST['submit'])) {
    //jika edit
    if ($_GET['hal'] == "edit") {
        //edit
        $edit = mysqli_query($koneksi, "UPDATE account set
                password = '$_POST[password]',
                name = '$_POST[name]',
                role = '$_POST[role]'
                WHERE username = '$_GET[id]'
                ");
        //TAMBAH
        if ($edit) {
            echo "<script>
            alert('sukses diedit');
            document.location='index.php'
            </script>";
        } else {
            echo "<script>
            alert('gagal diedit');
            document.location='index.php'
            </script>";
        }
    } else {
        //simpan
        $submit = mysqli_query($koneksi, "INSERT INTO account (password, name, role)
        VALUES ('$_POST[password]',
                '$_POST[name]',
                '$_POST[role]')
                ");
        //TAMBAH
        if ($submit) {
            echo "<script>
                alert('sukses ditambahkan');
                document.location='index.php'
                </script>";
        } else {
            echo "<script>
            alert('gagal ditambahkan');
            document.location='index.php'
            </script>";
        }
    }

    //

}
//EDIT
if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        //tampil data edit
        $tampil = mysqli_query($koneksi, "SELECT * from account WHERE username = '$_GET[id]'");
        $data = mysqli_fetch_array($tampil);
        if ($data) {
            //jika ada data, tampung variabel
            $varUsername = $data['username'];
            $varPassword = $data['password'];
            $varName = $data['name'];
            $varRole = $data['role'];
        }
    } else if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM account WHERE username = '$_GET[id]'");
        if ($hapus) {
            echo "<script>
            alert('berhasil dihapus');
            document.location='index.php'
            </script>";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>hnitest2</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">SPA</h1>
        <h2 class="text-center">Test (2) HNI</h2>
        <div class="card">
            <h5 class="card-header">Buat User Baru</h5>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="name" value="<?= @$varName ?>" class="form-control"
                            placeholder="tulis nama user baru..." required>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="text" name="password" value="<?= @$varPassword ?>" class="form-control"
                            placeholder="tulis password baru..." required>
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="<?= @$varRole ?>"><?php if (@$varRole == 1) {
                                                                    echo "Admin";
                                                                } else {
                                                                    echo "User";
                                                                }  ?></option>
                            <option value="2">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" name="submit">Tambah</button>
                        <button type="reset" class="btn btn-warning" name="reset">Bersihkan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- tabel -->
        <div class="card mt-4">
            <h5 class="card-header ">Tabel </h5>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Act</th>
                    </tr>
                    <?php
                    $no = 1;
                    $tampil = mysqli_query($koneksi, "SELECT * from account order by username asc");
                    while ($data = mysqli_fetch_array($tampil)) :
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['name'] ?></td>
                        <td>
                            <?php
                                if ($data['role'] == 1) {
                                    echo "admin";
                                } else {
                                    echo "user";
                                } ?>
                        </td>
                        <td>
                            <a href="index.php?hal=edit&id=<?= $data['username'] ?>" class="btn-btn-warning">Edit</a>
                            <?php if ($data['role'] == 1) { ?>

                            <?php } else { ?>
                            <a href="index.php?hal=hapus&id=<?= $data['username'] ?>"
                                onclick="return confirm('yakin <?= $data['name'] ?> dihapus ?')"
                                class="btn-btn-danger">Hapus</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php endwhile ?>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/bootstrap.min.js">
    // < /> < /body >

    // <
    // /html>
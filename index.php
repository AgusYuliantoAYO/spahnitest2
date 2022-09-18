<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "testhni";

$koneksi = mysqli_connect($server, $user, $pass, $database) or die(mysqli_error($koneksi));

error_reporting(0);

session_start();



//  Session Login
if (isset($_SESSION['username'])) {
    header("Location: ");
}
//logout
if (isset($_POST['logout'])) {
    // session_start();
    session_destroy();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // $password = md5($_POST['password']);
    // var_dump($username);
    // var_dump($password);
    // die;

    $sql = "SELECT * FROM account WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['name'];
        $_SESSION['role'] = $row['role'];

        header("Location: ");
    } else {
        echo "<script>alert('Email atau password Anda salah. Silahkan coba lagi!')</script>";
    }
}


//jika klik simpan
if (isset($_POST['submit'])) {
    //jika edit
    if ($_GET['hal'] == "edit") {
        //edit
        // $password = md5($_POST['password']);
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
        // $password = md5($_POST['password']);
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
            // $password = md5($_POST['password']);
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
        <?php if (!isset($_SESSION['username'])) { ?>
        <div id="login">
            <form action="" method="POST" class="login-email">
                <p class="login-text" style="font-size: 2rem; font-weight: 800;">Login</p>
                <div class="input-group">
                    <input type="text" placeholder="username" name="username" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="input-group">
                    <button name="login" id="btnLogin" class="btn btn-success">Login</button>
                </div>
            </form>
        </div>
        <?php } ?>
        <?php if (isset($_SESSION['username'])) { ?>
        <div class="container-logout" id="loginSukses">
            <form action="" method="POST" class="login-email">
                <?php echo "<h1>Selamat Datang, " . $_SESSION['username'] . "!" . "</h1>"; ?>

                <div class="input-group">
                    <button name="logout" id="btnLogout" class="btn btn-danger">Logout</button>
                </div>
            </form>
        </div>

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
                        <th>Username</th>
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
                        <td><?= $data['username'] ?></td>
                        <td><?= $data['name'] ?></td>
                        <td>
                            <?php
                                    if ($data['role'] == 1) {
                                        echo "admin";
                                    } else {
                                        echo "user";
                                    } ?>
                        </td>
                        <?php if ($_SESSION['role'] === '1') { ?>
                        <td>
                            <a href="index.php?hal=edit&id=<?= $data['username'] ?>" class="btn-btn-warning">Edit</a>
                            <?php if ($data['role'] == 1) { ?>

                            <?php } else { ?>
                            <a href="index.php?hal=hapus&id=<?= $data['username'] ?>"
                                onclick="return confirm('yakin <?= $data['name'] ?> dihapus ?')"
                                class="btn-btn-danger">Hapus</a>
                            <?php } ?>
                        </td>
                        <?php } else if ($_SESSION['role'] === '2') { ?>
                        <td>
                            <a href="index.php?hal=edit&id=<?= $data['username'] ?>" class="btn-btn-warning">Edit</a>

                        </td>
                        <?php } else { ?>
                        <td></td>
                        <?php } ?>
                    </tr>
                    <?php endwhile ?>
                </table>
            </div>
        </div>
        <?php } ?>




    </div>


    <script type="text/javascript" src="js/bootstrap.min.js">
    // < /> < /body >

    // <
    // /html>
<?php
    require_once('../db.php');

    if (!$_SESSION['logged']) {
        header('Location: /?not_logged=true');
    }

    if (isset($_POST['submit'])) {
        $organizer_id = $_SESSION['logged']['id'];

        $name = $_POST['name'];
        $slug = $_POST['slug'];
        $date = $_POST['date'];

        $name_err = false;
        $slug_err = false;
        $date_err = false;

        $error = false;

        if (!preg_match('/^[a-zA-Z0-9-]{3,20}$/', $slug)) {
            $slug_err = 'Slug не должно быть пустым и содержать только a-z, 0-9 и "-"';
            $error = true;
        }
        if ($name == '') {
            $name_err = 'Это поле обезятельное';
            $error = true;
        }
        if ($date == '') {
            $date_err = 'Это поле обезятельное';
            $error = true;
        }
        $result = mysqli_query($connection, "SELECT * FROM `events` WHERE `slug` = '$slug'");
        if(mysqli_num_rows($result)) {
            $slug_err = 'Событие с таким Slug уже существует';
        }


        if ($error == false) {
            mysqli_query($connection, "INSERT INTO `events`(`organizer_id`,`name`,`slug`,`date`)
                VALUES('$organizer_id','$name','$slug','$date')");

            header('Location: detail.php?id='.mysqli_insert_id($connection).'&success=true');
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Event Backend</title>

    <base href="../">
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="events/">Event Platform</a>
    <span class="navbar-organizer w-100"><?php echo $_SESSION['logged']['name']; ?></span>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" id="logout" href="../logout.php">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="events/">Manage Events</a></li>
                </ul>
            </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Events</h1>
            </div>

            <div class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Create new event</h2>
                </div>
            </div>

            <form class="needs-validation" method="POST" novalidate action="">

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputName">Name</label>
                        <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                        <input type="text" class="form-control <?php if ($name_err) {echo('is-invalid');} ?>" id="inputName" name="name" placeholder="" value="<?php echo($name); ?>">
                        <div class="invalid-feedback">
                            <?php if ($name_err) {
                                echo $name_err;
                            } ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputSlug">Slug</label>
                        <input type="text" class="form-control <?php if ($slug_err) {echo('is-invalid');} ?>" id="inputSlug" name="slug" placeholder="" value="<?php echo($slug); ?>">
                        <div class="invalid-feedback">
                            <?php if ($slug_err) {
                                echo $slug_err;
                            } ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputDate">Date</label>
                        <input type="text"
                               class="form-control <?php if ($date_err) {echo('is-invalid');} ?>"
                               id="inputDate"
                               name="date"
                               placeholder="yyyy-mm-dd"
                               value="<?php echo($date); ?>">
                               <div class="invalid-feedback">
                                <?php if ($date_err) {
                                    echo $date_err;
                                } ?>
                        </div>
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary" type="submit" name="submit">Save event</button>
                <a href="events/" class="btn btn-link">Cancel</a>
            </form>

        </main>
    </div>
</div>

</body>
</html>

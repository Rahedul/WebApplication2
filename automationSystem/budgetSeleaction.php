<?php
    include 'header.php';
    include 'user.php';
?>
<?php
    $user = new user();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        $userRegi = $user->userRegistration($_POST);
    }
?>
<?php
    $db = mysqli_connect("localhost","root","","db_lr");

    if (isset($_POST['submit'])) {
        $budget_type = $_POST['budget_type'];
        $budgetType = $_POST['budgetType'];
        $comment = $_POST['comment'];

        $query = "INSERT INTO budgetseleaction (budget_type,budgetType,comment) VALUES ('$budget_type','$budgetType','$comment')";
        $run = mysqli_query($db, $query);

        if ($run) {
            $_SESSION['status'] = "Data Inserted";
            header("Location: DescriptionOfDemand.php");
        }
        else{
            $_SESSION['status'] = "Data Not Inserted";
            header("Location: budgetSeleaction.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>বাজেটের বিভাগ</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-light bg-light navbar-expand-sm">
    <div class="container">
        <ul class="navbar-nav">
            <?php
                $id = session::get("id");
                $userlogin = session::get("login");
                if ($userlogin == true) {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="profile.php?id=<?php echo $id; ?>">প্রোফাইল</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">পূর্ববর্তী পৃষ্ঠা</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?action=logout">প্রস্থান</a>
            </li>
            <?php }
            else{ ?>
            <li class="nav-item">
                <a class="nav-link" href="login.php">প্রবেশ করুণ</a>
            </li>
            <?php } ?>
        </ul>
    </div>
  </nav>
    
    <div class="container" style="max-width: 700px;">
        <form action="budgetSeleaction.php" method="POST">
            <div class="row">
                <div class="col-6">
                        <h4 class="text-center mt-5">
                            বাজেটের বিভাগ বাছাই করুন
                        </h4>
                    <div style="max-width: 200px; margin: 0 auto">
                        <div class="form-group btn btn-outline-primary mt-2 d-block">
                            <input id="revenue" name="budget_type" type="radio" value="revenue">
                            <label for="revenue">রাজস্ব</label>
                        </div>
                        <div class="form-group btn btn-outline-primary mt-2 d-block">
                            <input id="development" name="budget_type" type="radio" value="development">
                            <label for="development">উন্নায়ন</label>
                        </div>
                        <div class="form-group btn btn-outline-primary mt-2 d-block">
                            <input id="others" name="budget_type" type="radio" value="others">
                            <label for="others">অন্যান্য</label>    
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <h4 class="text-center mt-5">
                        বাজেটের প্রকৃতি বাছাই করুন
                    </h4>

                    <div style="max-width: 200px; margin: 0 auto">
                        <div class="form-group btn btn-outline-primary mt-2 d-block">
                            <input id="work" name="budgetType" type="radio" value="work">
                            <label for="work">কাজ</label>
                        </div>
                        <div class="form-group btn btn-outline-primary mt-2 d-block">
                            <input id="service" name="budgetType" type="radio" value="service">
                            <label for="service">সেবা</label>
                        </div>
                        <div class="form-group btn btn-outline-primary mt-2 d-block">
                            <input id="buying" name="budgetType" type="radio" value="buyingProduct">
                            <label for="buying">মালামাল ক্রয়</label>
                        </div>
                    </div>
                </div>
            </div>
        <h4 class="text-center mt-5 my-4">
            বাজেটের প্রয়োজনীয়তা বর্ননা করুন
        </h4>
        <div style="max-width: 650px; margin: 0 auto">
            <div class="form-group mt-3">
                <textarea class="form-control" name="comment" cols="71" rows="7" placeholder="লিখুন..."></textarea>
            </div>
            <div class="text-center form-group m-3">
                <input class="btn btn-success" type="submit" name="submit" value="নিশ্চিত করুন">
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>
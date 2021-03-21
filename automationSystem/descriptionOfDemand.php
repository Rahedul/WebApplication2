<?php
    include 'header.php';
    include 'user.php';
?>

<?php
    $db = mysqli_connect("localhost","root","","db_lr");

    if (isset($_POST['submit'])) {
        $item = $_POST['item'];
        $qty = $_POST['qty'];
        $price = $_POST['price'];
        $item_total = $_POST['item_total'];
        $total = $_POST['total'];
        $query = "INSERT INTO demand (item,qty,price,item_total,total) VALUES ('$item','$qty','$price','$item_total','$total')";
        
        $run = mysqli_query($db, $query);
        if ($run) {
            $_SESSION['status'] = "Data Inserted";
            header("Location: generalInformation.php");
        }
        else{
            $_SESSION['status'] = "Data Not Inserted";
            header("Location: descriptionOfDemand.php");
        }
    }
?>

<html>
<head>
    <meta charset="UTF-8">
        <title>চাহিদার বিবারণ</title>
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="dist/jautocalc.js"></script>
        <script type="text/javascript">
        
            $(document).ready(function() {

                function autoCalcSetup() {
                    $('form[name=cart]').jAutoCalc('destroy');
                    $('form[name=cart] tr[name=line_items]').jAutoCalc({keyEventsFire: true, decimalPlaces: 2, emptyAsZero: true});
                    $('form[name=cart]').jAutoCalc({decimalPlaces: 2});
                }
                autoCalcSetup();


                $('button[name=remove]').click(function(e) {
                    e.preventDefault();

                    var form = $(this).parents('form')
                    $(this).parents('tr').remove();
                    autoCalcSetup();

                });

                $('button[name=add]').click(function(e) {
                    e.preventDefault();

                    var $table = $(this).parents('table');
                    var $top = $table.find('tr[name=line_items]').first();
                    var $new = $top.clone(true);

                    $new.jAutoCalc('destroy');
                    $new.insertBefore($top);
                    $new.find('input[type=text]').val('');
                    autoCalcSetup();

                });

            });

        </script>
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
                    <a class="nav-link" href="budgetSeleaction.php">পূর্ববর্তী পৃষ্ঠা</a>
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
    <div class="h4 text-center my-4">
            চাহিদার বিবারণী ছকঃ 
    </div>
        <div class="container">
            <form action="descriptionOfDemand.php" method="POST" name="cart">
                <table class="table table-striped table-bordered table-hover" name="cart">
                    <tr class="table-secondary">
                        <th></th>
                        <th>কাজ/সেবা/মালামালের বিবারণ</th>
                        <th>পরিমাণ(একক)</th>
                        <th>একক মূল্য(প্রযোজ্য হারে আয়কর ও ভ্যাটসহ)</th>
                        <th>মোট মূল্য(প্রযোজ্য হারে আয়কর ও ভ্যাটসহ)</th>
                    </tr>
                    
                    <tr name="line_items">
                        <td><button name="remove" class="btn btn-danger">Remove</button></td>
                        <td><input type="text" name="item" class="input form-control"></td>
                        <td><input type="number" name="qty" class="input form-control" value="1"></td>
                        <td><input type="text" name="price" class="input form-control" value="0.00"></td>
                        <td><input type="text" name="item_total" class="input form-control" value="" jAutoCalc="{qty} * {price}"></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                        <td style="text-align: right">সর্বমোট প্রাক্কলিত মূল্য</td>
                        <td><input type="text" name="total" class="input form-control" value="" jAutoCalc="SUM({item_total})"></td>
                    </tr>
                    <tr>
                        <td colspan="99"><button class="btn btn-dark" name="add">Add Row</button></td>
                    </tr>
                </table>
                <div class="text-center mt-4">
                    <input type="submit" class="btn btn-success" name="submit" value="নিশ্চিত করুন">
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>

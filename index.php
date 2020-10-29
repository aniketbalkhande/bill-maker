<?php 
  require_once('config.php');
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

    <script>
        var cashSound = new Audio();
        cashSound.src = "cashSound.mp3";
        var receiptPrinter = new Audio();
        receiptPrinter.src = "receiptPrinter.mp3";
    </script>
</head>
<body>
    <div class="container">
        <form action="index.php" method="POST">    
        <h1>Bill Maker</h1>
            <div class="form">
            <h2>Description</h2><!-- maxlenght to keep imput in limit -->
                <input type="text" name="description" maxlength="15" required>
                <h2>Qty.</h2>
                <input type="number" name="qty" required>
                <h2>Price</h2>
                <input type="number" name="price" required><br>
                <button type="submit" id="add-button" onmousedown = "cashSound.play()" name="add">Add</button>
            </div>
        </form>
        <hr>
        <div class="display">
            <table>
                <tr>
                    <th>SI.</th>
                    <th>Description</th>
                    <th>Qty.</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
                <?php
                require_once('config.php');
                $sql = "SELECT * FROM bill";
                $result = $db->prepare($sql);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){
                ?>
                <tr>
                    <td><label><?php echo $row['SI']; ?></label></td>
                    <td><label><?php echo $row['description']; ?></label></td>
                    <td><label><?php echo $row['qty']; ?></label></td>
                    <td><label><?php echo $row['price']; ?></label></td>
                    <td><label><?php echo $row['qty']*$row['price'];?></td>
                </tr>

            <?php } ?>
            </table>
            <hr id="total">
            <h3 id="total">Total: 
            <?php
                $sql = "SELECT * FROM bill";
                $result = $db->prepare($sql);
                $result->execute();
                
                $total = 0;
                for($i=0; $row = $result->fetch(); $i++)
                {
                    $total = $total + $row['qty'] * $row['price'];
                }
                if($total > 0)
                { 
                    echo $total,"/-";
                }
                ?>
            </h3><hr id="total">
            <a setInterval(4000)id="print" href="pdf.php" onmousedown="receiptPrinter.play()"><i>Print</i></a>
        </div>
    </div>
</body>         
</html>
<!-- PHP query for insert -->
<?php
    if(isset($_POST['add']))
    {
        $description = $_POST['description'];
        $qty = $_POST['qty'];
        $price = $_POST['price'];


        $sql = "INSERT INTO  bill(description,qty,price)  VALUES(?,?,?)"; 
        $stmtinsert = $db->prepare($sql);
        $result = $stmtinsert->execute([$description,$qty,$price]);
        
        if($result){  header("Location:index.php");
                      exit(); 
                    }
        else   { echo "there were errors"; }
    }            
?>


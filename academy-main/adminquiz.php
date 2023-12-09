<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_product'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $total = $_POST['total'];
   $total = filter_var($total, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $old_image = $_POST['old_image'];

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $details, $price, $pid]);

   $message[] = 'product updated successfully!';

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{

         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);

         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'image updated successfully!';
         }
      }
   }


   

}










if(isset($_POST['update_product'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $total = $_POST['total'];
   $total = filter_var($total, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $old_image = $_POST['old_image'];

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $details, $price, $pid]);

   $message[] = 'product updated successfully!';

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{

         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);

         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'image updated successfully!';
         }
      }
   }


   

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quiz</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
<style>
* {
    box-sizing: border-box;
}
:root {
    --switches-bg-color: goldenrod;
    --switches-label-color: white ;
    --switch-bg-color: white;
    .container {
    border: 1px solid plum;
    width: 20rem;
    padding: 1rem;
    margin-left: auto;
    margin-right: auto;
    margin-top: 5%;
}
/* p - decorative, not required */
p {
  margin-top:2rem;
  font-size:0.75rem;
  text-align:center;
}

/* container for all of the switch elements 
    - adjust "width" to fit the content accordingly 
*/
.switches-container {
    width: 16rem;
    position: relative;
    display: flex;
    padding: 0;
    position: relative;
    background: var(--switches-bg-color);
    line-height: 3rem;
    border-radius: 3rem;
    margin-left: auto;
    margin-right: auto;
}

/* input (radio) for toggling. hidden - use labels for clicking on */
.switches-container input {
    visibility: hidden;
    position: absolute;
    top: 0;
}

/* labels for the input (radio) boxes - something to click on */
.switches-container label {
    width: 50%;
    padding: 0;
    margin: 0;
    text-align: center;
    cursor: pointer;
    color: var(--switches-label-color);
}

/* switch highlighters wrapper (sliding left / right) 
    - need wrapper to enable the even margins around the highlight box
*/
.switch-wrapper {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 50%;
    padding: 0.15rem;
    z-index: 3;
    transition: transform .5s cubic-bezier(.77, 0, .175, 1);
    /* transition: transform 1s; */
}

/* switch box highlighter */
.switch {
    border-radius: 3rem;
    background: var(--switch-bg-color);
    height: 100%;
}

/* switch box labels
    - default setup
    - toggle afterwards based on radio:checked status 
*/
.switch div {
    width: 100%;
    text-align: center;
    opacity: 0;
    display: block;
    color: var(--switch-text-color) ;
    transition: opacity .2s cubic-bezier(.77, 0, .175, 1) .125s;
    will-change: opacity;
    position: absolute;
    top: 0;
    left: 0;
}

/* slide the switch box from right to left */
.switches-container input:nth-of-type(1):checked~.switch-wrapper {
    transform: translateX(0%);
}

/* slide the switch box from left to right */
.switches-container input:nth-of-type(2):checked~.switch-wrapper {
    transform: translateX(100%);
}

/* toggle the switch box labels - first checkbox:checked - show first switch div */
.switches-container input:nth-of-type(1):checked~.switch-wrapper .switch div:nth-of-type(1) {
    opacity: 1;
}

/* toggle the switch box labels - second checkbox:checked - show second switch div */
.switches-container input:nth-of-type(2):checked~.switch-wrapper .switch div:nth-of-type(2) {
    opacity: 1;
}
</style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-product">

   <h1 class="title">Quiz Control</h1>   

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `coursebucket` WHERE pid = ?");
      $select_products->execute([$update_id]);

      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <h2 class="name"> <?= $fetch_products['name']; ?></h2>
      <h2 class="name">course id =<?= $fetch_products['id']; ?></h2>
  <!--

  <div class="container">
  <div class="switches-container">
    <input type="radio" id="switchMonthly" name="switchPlan" value="Monthly" checked="checked" />
    <input type="radio" id="switchYearly" name="switchPlan" value="Yearly" />
    <label for="switchMonthly">ON</label>
    <label for="switchYearly">OFF</label>
    <div class="switch-wrapper">
      <div class="switch">
        <div>ON</div>
        <div>OFF</div>
      </div>
    </div>
  </div>
  <p><small>Switch on /off Courses</small></p>
</div>




      <input type="text" name="name" placeholder="enter product name" required class="box" value="<?= $fetch_products['name']; ?>"> -->
      <div class="flex-btn">
         <input type="submit" class="btn" value="update product" name="update_product">
         <a href="admin_products.php" class="option-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>';
      }
   ?>














</section>


<section class="update-profile">

   <h1 class="title">ADD QUIZ</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/ca2a1459304199.5a1e9ce2073de.gif" alt="">
      <div class="flex">
         <div class="inputBox">
            <span>Quiz Title :</span>
            <input type="text" name="name" value="<?= $fetch_products['title']; ?>" placeholder="add Title" required class="box">
            <span>Total number of Question :</span>
            <input type="number" name="noq" value="<?= $fetch_profile['total']; ?>" placeholder="add no of question" required class="box">
            <span>Time cap for this test in mins</span>
            <input type="number" name="email" value="<?= $fetch_profile['total']; ?>" placeholder="add no of question" required class="box">
            <div class="box"> <div class="cat">  <span>Upload excel with the matched format</span>
            <input type="file" name="image" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.FILETYPE,.xlsx, .xls, .csv" class="box">

            
  ⚠️⚠️⚠️OR skip upload questions and frame questions manually⚠️⚠️⚠️

  <br>
  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
  <label for="vehicle1"> I read this</label><br>
  </div> </div> </div>
         <div class="inputBox">
            <span>Marks on Right answer : :</span>
            <input type="number" name="update_pass" placeholder="enter number without sign +/-" class="box">
            <span>-ve Marks on wrong answer :</span>
            <input type="number" name="new_pass" placeholder="enter number without sign +/-" class="box">
            <span>#Tag Used for searching</span>
            <input type="text" name="confirm_pass" placeholder="Enter tags for searching #c++ ,#python" class="box">
            <span>Description/instruction</span>
            <input type="text" name="confirm_pass" placeholder="Enter description of instruction" class="box">
         </div>
      </div>
      <div class="flex-btn">
         <input type="submit" class="btn" value="Continue" name="update_profile">
         <a href="admin_page.php" class="option-btn">go back</a>
      </div>
   </form>

</section>





<section class="show-products">

   <h1 class="title">Quiz added</h1>

   <div class="box-container">

   <?php
    $show_products = $conn->prepare("SELECT * FROM quiz WHERE courseid='$update_id' ORDER BY date DESC ");
 //       $show_products = $conn->prepare("SELECT * FROM quiz ORDER BY date DESC ");
      $show_products->execute();
      $c=1;

      if($show_products->rowCount() > 0){ 
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">

      <div class="price">S.no<?= $c++; ?>/-</div> 
      <img src="https://custom-images.strikinglycdn.com/res/hrscywv4p/image/upload/c_limit,fl_lossy,h_9000,w_1200,f_auto,q_60/405230/846150_74718.gif" alt="">
      <div class="name"><?= $fetch_products['title']; ?></div>

     <div class="flex-btn">
      <h class="m-btn">Total Q.no</h>  
       <h class="delete-btn"><?= $fetch_products['total']; ?></h> 
       </div>
          <div class="flex-btn">
      <h class="m-btn">Time</h>  
       <h class="delete-btn"><?= $fetch_products['time']; ?></h> 
       </div>
             <div class="flex-btn">
      <h class="m-btn">Course id</h>  
       <h class="delete-btn"><?= $fetch_products['courseid']; ?></h> 
       </div>
               <div class="flex-btn">
      <h class="m-btn">Marks</h>  
       <h class="delete-btn"><?= $fetch_products['sahi']; ?></h> 
       </div>
               <div class="flex-btn">
      <h class="m-btn">Time limit</h>  
       <h class="delete-btn"><?= $fetch_products['time']; ?> mins</h> 
       </div>
       
      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no Quiz added yet!</p>';
   }
   ?>

   </div>

</section>













<script src="js/script.js"></script>

</body>
</html>
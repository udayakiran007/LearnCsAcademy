<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
<style>
.content-wrapper-header {
  display: flex;
  align-items: center;
  width: 100%;
  justify-content: space-between;
  background-image: url("https://www.transparenttextures.com/patterns/cubes.png"), linear-gradient(to right top, #cf4af3, #e73bd7, #f631bc, #fd31a2, #ff3a8b, #ff4b78, #ff5e68, #ff705c, #ff8c51, #ffaa49, #ffc848, #ffe652);
  border-radius: 14px;
  padding: 20px 40px;
    opacity: 0.9;
}
@media screen and (max-width: 415px) {
  .content-wrapper-header {
    padding: 20px;
  }
}

.img-content {
  font-weight: 500;
  font-size: 17px;
  display: flex;
  align-items: center;
  margin: 0;
}
.img-content svg {
  width: 28px;
  margin-right: 14px;
}

.content-text {
  font-weight: 400;
  font-size: 14px;
  margin-top: 16px;
  line-height: 1.7em;
  color: #ebecec;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.content-wrapper-context {
  max-width: 350px;
}

.content-button {
  background-color: #3a6df0;
  border: none;
  padding: 8px 26px;
  color: #fff;
  border-radius: 20px;
  margin-top: 16px;
  cursor: pointer;
  transition: 0.3s;
  white-space: nowrap;
}

.content-wrapper-img {
  width: 186px;
  -o-object-fit: cover;
     object-fit: cover;
  margin-top: -25px;
  -o-object-position: center;
     object-position: center;
}
@media screen and (max-width: 570px) {
  .content-wrapper-img {
    width: 110px;
  }
}
</style>
</head>
<body>
   
<?php include 'header.php'; ?>
<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Unlock Your Potential with Endless Opportunities</span>
         <h3> Grab yourself start your programming carrier with Learn Cs Academy </h3>
         <p>Learn ,Practice,Debug Dopamine</p>
      </div>

   </section>

</div>
<section class="home-category">

   <h1 class="title">Courses</h1>

   <div class="box-container">

      <div class="box">
         <img src="https://logos-world.net/wp-content/uploads/2020/11/GitHub-Logo.png" height="100px" width="150px" alt="">
         <h3>Bridge Course</h3>
         <p>Version control,onboard ing softwares ,extension ,environments and installations</p>
         <a href="category.php?category=bridge" class="btn">Bridge Course</a>
      </div>

      <div class="box">
         <img src="https://miro.medium.com/v2/resize:fit:2560/1*sMryEXZVPKFjGNcfSzE8Mw.jpeg" height="100px" width="150px" alt="">
         <h3>DSA</h3>
         <p>Full Basic to advance Data structures and algorithms.</p>
         <br /><br />
         <a href="category.php?category=dsa" class="btn">DSA</a>
      </div>

      <div class="box">
         <img src="https://cdn-images-1.medium.com/max/2000/1*SSutxOFoBUaUmgeNWAPeBA.jpeg" height="100px" width="150px" alt="">
         <h3>Python Master Class</h3>
         <p>Python Master Class for competitive exams.</p>
         <a href="category.php?category=python" class="btn">Python </a>
      </div>

      <div class="box">
         <img src="https://technosoups.com/wp-content/uploads/2021/03/R-programming-language.jpg" height="100px" width="150px" alt="">
         <h3>R-Programming</h3>
         <p>R-programming advance concepts for competitive exams.</p>
        
         <a href="category.php?category=rp" class="btn">R-Programming</a>
      </div>

   </div>

</section>










<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
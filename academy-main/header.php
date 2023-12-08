<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<header class="header">

   <div class="flex">
<img src="./1.png" height="60px" width="60px"> </img>
      <a href="home.php" class="logo">Learn<span>CS</span>Academy</a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="shop.php">Courses</a>
         <a href="orders.php">Purchase</a>
         <!--<a href="about.php">about</a> -->
           <a href="contact.php">Feedback</a>
          <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
      
         ?>
         
         <a href="cart.php">Cart <i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
       <a>   Profile <div id="user-btn" class="fas fa-user"></div></a>
   
      </nav>

      <div class="icons">
            <a href="search_page.php" class="fas fa-search"></a>
         <div id="menu-btn" class="fas fa-bars"></div>
      <div id="user-btn" class="fas fa-user"></div></a>
        
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <a href="user_profile_update.php" class="btn">update profile</a>
         <a href="logout.php" class="delete-btn">logout</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </div>


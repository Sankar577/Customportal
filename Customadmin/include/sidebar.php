<?php
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];


if($currentFile=="setting.php"){ $setting="class='active'"; } else {$setting=""; }
if($currentFile=="services.php"){ $services="class='active'"; } else {$services=""; }
if($currentFile=="Product_management.php"){ $about_us="class='active'"; } else {$about_us=""; }
if($currentFile=="contend.php"){ $contend_us="class='active'"; } else {$contend_us=""; }
if($currentFile=="join.php"){ $join_us="class='active'"; } else {$join_us=""; }
if($currentFile=="OurTeam.php"){ $team_us="class='active'"; } else {$team_us=""; }
if($currentFile=="client.php"){ $client="class='active'"; } else {$client=""; }
if($currentFile=="Customer_management.php"){ $Customer_management="class='active'"; } else {$Customer_management=""; }
if($currentFile=="Inventory_Management.php"){ $inventery_manage="class='active'"; } else {$inventery_manage=""; }
if($currentFile=="Order_Management.php"){ $order_management="class='active'"; } else {$order_management=""; }
// if(($currentFile== "banner.php")|| ($currentFile== "home_con.php") || ($currentFile== "home_ads.php")||($currentFile== "home_sideads.php")){$home="active";} else { $home="";}
// if($currentFile== "home_sideads.php"){$home_sideads="class='active'";} else { $home_sideads="";}
// if($currentFile=="banner.php"){ $home_banner="class='active'"; } else {$home_banner=""; } 
// if($currentFile=="inner_banner.php"){ $inner_banner="class='active'"; } else {$inner_banner=""; }
// if(($currentFile== "article.php") || ($currentFile== "terms.php") || ($currentFile== "privacy.php")|| ($currentFile== "about.php") || ($currentFile=="article_ads.php")|| ($currentFile=="foot_menu.php")|| ($currentFile=="manage_menu.php")){$home_content="active";} else { $home_content="";}
// if(($currentFile=="foot_menu.php")|| ($currentFile=="manage_menu.php")) { $foot_menu="class='active'"; } else {$foot_menu=""; }
// if(($currentFile== "client.php")|| ($currentFile== "services.php") || ($currentFile== "contend.php")||($currentFile== "join.php") ||($currentFile== "OurTeam.php")){$contend="active";} else { $contend="";}

?>
<aside class="main-sidebar" style="top: 50px;">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
<!--     
            <li <?php echo $index;?>>
                <a href="index.php">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li> -->
		<li class="treeview <?php echo $home;?>" <?php if($home_banner) { echo "style='display:block'"; }else{ echo ""; } ?> >
                <a href="#">
               <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Home</span>
                </a>
                
            </li>
            
			
	  <li <?php echo $Customer_management;?> ><a href="Customer_management.php"><i class="fa fas fa-building" aria-hidden="true"></i>Customer Management</a></li>
      <li <?php echo $about_us;?>><a href="Product_management.php"><i class="fa fas fa-cube  pictofixedwidth" aria-hidden="true"></i>Product Management</a></li>
            <!-- <li <?php echo $setting;?>><a href="setting.php"><i class="fa fa-gears" aria-hidden="true"></i>Site Settings</a></li> -->
            <li <?php echo $inventery_manage;?>><a href="Inventory_Management.php"><i class="fa fa-id-card" aria-hidden="true"></i>Inventory Management</a></li>
            <li <?php echo $order_management;?>><a href="Order_Management.php"><i class="fa fa-suitcase" aria-hidden="true"></i>Order Management</a></li>
           
          
                
   
			
		
            


              
        </ul>
    </section>
</aside>
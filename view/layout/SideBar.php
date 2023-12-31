<!-- ======= Toast ======= -->
<div id="toastAction" class="toast m-4" style="position: absolute; top: 0; right: 0; z-index: 999;">
    <div id="toastMessage" class="alert alert-primary alert-dismissible fade show m-0">
        This message !
    </div>
</div>
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <img src="assets/img/main-icon.png" alt="">
            <span class="d-none d-lg-block">Student Attendance</span>
        </a>
        <!-- <i class="bi bi-list toggle-sidebar-btn"></i> -->
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $global->nameUser ?></span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo $global->nameUser ?></h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="controller/ajax.php?action=logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <?php
        
        foreach ($global->arrayRule as $keyRule => $valueRule) :
        ?>
            <li class="nav-item">
                <a class="nav-link collapsed" id="<?= $keyRule; ?>" href="index.php?page=<?= $keyRule; ?>">
                    <i class="<?php echo $global->arrayIcon[$keyRule]; ?>"></i>
                    <span><?= $valueRule; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</aside><!-- End Sidebar-->